<?php
/**
 * ------------------------------------------------------------------------
 * JA Bulletin Module for J3.x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
 
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

require_once JPATH_SITE . '/components/com_content/helpers/route.php';
jimport('joomla.application.component.model');
if (version_compare(JVERSION, '3.0', 'ge'))
{
	JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models');
}
else if (version_compare(JVERSION, '2.5', 'ge'))
{
	JModel::addIncludePath(JPATH_SITE . '/components/com_content/models');
}
else
{
	JModel::addIncludePath(JPATH_SITE . '/components/com_content/models');
}

if (file_exists(JPATH_SITE . DS . 'components' . DS . 'com_k2' . DS . 'helpers' . DS . 'route.php')) {
    require_once (JPATH_SITE . DS . 'components' . DS . 'com_k2' . DS . 'helpers' . DS . 'route.php');
}

//end check
if (!class_exists('modJABulletin')) {
    /**
     *
     * JA BULLETIN HELPER CLASS
     * @author JoomlArt
     *
     */
	class modJABulletin {
        /**
         *
         * Get list articles data
         * Get from database or cache
         * @param object $params
         * @return array
         */
		public function getListArticles($params)
		{
			// check cache was endable ?
			$app = JFactory::getApplication();
			$use_cache = $app->getCfg("caching");
		    if ( $params->get('cache') == "1" && $use_cache == "1") {
				$cache = JFactory::getCache();
				$cache->setCaching( true);
                $cache->setLifeTime($params->get('cache_time', 30) * 60);
                $rows = $cache->get(array($this, 'getAticles'), array($params));
            } else {
                $using_mode = $params->get('using_mode', 'catids');

                if ($using_mode == "com_k2") {
                    $rows = $this->getListK2($params);
                } else {
                    $rows = $this->getAticles($params);
                }
            }
            return $rows;
        }


        /**
         * get list k2 items follow setting configuration.
         *
         * @param JParameter $param
         * @return array
         */
        public function getListK2($params)
        {
            
        	global $mainframe;
			if (! file_exists(JPATH_SITE . DS . 'components' . DS . 'com_k2' . DS . 'k2.php')) {
				return ;
			}
            $catsid = $params->get('k2catsid');
            $catids = array();
            if (!is_array($catsid)) {
                $catids[] = $catsid;
            } else {
                $catids = $catsid;
            }

            JArrayHelper::toInteger($catids);
            if ($catids) {
                if ($catids && count($catids) > 0) {
                    foreach ($catids as $k => $catid) {
                        if (!$catid)
                            unset($catids[$k]);
                    }
                }
            }

            jimport('joomla.filesystem.file');

            $user = JFactory::getUser();
            $app = JFactory::getApplication();
            $aid = $user->get('aid') ? $user->get('aid') : 1;
            $db = JFactory::getDBO();

            $jnow = JFactory::getDate();
           
         	if (version_compare(JVERSION, '3.0', 'ge'))
				{
					$now = $jnow->toSql();
				}
			else if (version_compare(JVERSION, '2.5', 'ge'))
			{
				$now = $jnow->toMySQL();
			}
			else
			{
				$now = $jnow->toMySQL();
			}
            $nullDate = $db->getNullDate();

			$query 	= "SELECT i.*, c.name AS categoryname,c.id AS categoryid, c.alias AS categoryalias, c.name as cattitle, c.params AS categoryparams";
            $query .= "\n FROM #__k2_items as i LEFT JOIN #__k2_categories c ON c.id = i.catid";
            $query .= "\n WHERE i.published = 1 AND i.access <= {$aid} AND i.trash = 0 AND c.published = 1 AND c.access <= {$aid} AND c.trash = 0";
            $query .= "\n AND ( i.publish_up = " . $db->Quote($nullDate) . " OR i.publish_up <= " . $db->Quote($now) . " )";
            $query .= "\n AND ( i.publish_down = " . $db->Quote($nullDate) . " OR i.publish_down >= " . $db->Quote($now) . " )";

            if ($catids) {
                $catids_new = $catids;
                foreach ($catids as $k => $catid) {
                    $subcatids = modJABulletin::getK2CategoryChildren($catid, true);
                    if ($subcatids) {
                        $catids_new = array_merge($catids_new, array_diff($subcatids, $catids_new));
                    }
                }
                $catids = implode(',', $catids_new);
                $query .= "\n AND i.catid IN ($catids)";
            }
			
			$featured = $params->get('show_featured', 1);
			
			// language filter
			$lang = JFactory::getLanguage();
			$languages = JLanguageHelper::getLanguages('lang_code');
			$languageTag = $lang->getTag();
			if ($app->getLanguageFilter()) {
				$query .= " AND i.language IN ('{$languageTag}','*') ";
			}
			
			switch ($featured) {
				case 0:
					$query .= " AND i.featured = 0 ";
					break;
				case 2: 
					$query .= " AND i.featured = 1 ";
					break;
			}

            // Set ordering
            $ordering = $params->get('type', 'latest');
            if ($ordering == 'latest') {
                // Set ordering
                $order_map = array('m_dsc' => 'i.modified DESC, i.created', 'mc_dsc' => 'CASE WHEN (a.modified = ' . $db->quote($db->getNullDate()) . ') THEN i.created ELSE i.modified END', 'c_dsc' => 'i.created', 'p_dsc' => 'i.publish_up');
                $ordering = JArrayHelper::getValue($order_map, $params->get('ordering', 'm_dsc'), 'i.publish_up');
            } else {
                $ordering = 'i.hits';
            }
			
			if($ordering == "i.hits" && $params->get('timerange')){
			    $datenow = JFactory::getDate();				
				 if (version_compare(JVERSION, '3.0', 'ge'))
					{
						$date = $datenow->toSql();
					}
				else if (version_compare(JVERSION, '2.5', 'ge'))
				{
					$date = $datenow->toMySQL();
				}
				else
				{
					$date = $datenow->toMySQL();
				}
				$query.=" AND i.created > DATE_SUB('{$date}',INTERVAL ".$params->get('timerange')." DAY) ";
			
			
			}
			
            $query .= ' ORDER BY ' . $ordering . ' DESC ';
			
			if ((int) trim($params->get('count', 5))==0) {
				$query = str_replace("i.published = 1 AND", "i.published = 10 AND", $query);
			}
			
            $db->setQuery($query, 0, (int) trim($params->get('count', 5)));
            $items = $db->loadObjectList();

            if ($items) {

                $i = 0;
                $showHits = $params->get('show_hits', 0);
                $showHits = $showHits == "1" ? true : false;
                $showimg = $params->get('show_image', 1);
                $w = (int) $params->get('width', 80);
                $h = (int) $params->get('height', 96);
                $showdate = $params->get('show_date', 1);

                $thumbnailMode = $params->get('thumbnail_mode', 'crop');
                $aspect = $params->get('use_ratio', '1');
                $crop = $thumbnailMode == 'crop' ? true : false;
                $lists = array();
                $jaimage = JAImage::getInstance();

                foreach ($items as &$item) {

                    $item->link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($item->id . ':' . urlencode($item->alias), $item->catid . ':' . urlencode($item->categoryalias))));

                    $item->text = htmlspecialchars($item->title);

                    if ($showdate) {
                        $item->date = $item->modified == null || $item->modified == "" || $item->modified == "0000-00-00 00:00:00" ? $item->created : $item->modified;
                    }
                    $item->image = '';
                    if ($showimg) {
                        $imageSource = $this->parseImages($item,$params,'k2');
                        if ($imageSource) {
                            if ($thumbnailMode != 'none') {
                                $imageURL = $jaimage->resize($imageSource, $w, $h, $crop, $aspect);
                                if ($imageURL) {
                                    if ($imageURL == $imageSource) {
                                        $width = $w ? "width=\"$w\"" : "";
                                        $height = $h ? "height=\"$h\"" : "";
                                        $item->image = "<img src=\"$imageURL\" alt=\"{$item->text}\" title=\"{$item->text}\" $width $height />";
                                    } else {
                                        $item->image = "<img src=\"$imageURL\" alt=\"{$item->text}\" title=\"{$item->text}\" />";
                                    }
                                } else {
                                    $item->image = '';
                                }
                            } else {
                                $width = $w ? "width=\"$w\"" : "";
                                $height = $h ? "height=\"$h\"" : "";
                                $item->image = "<img src=\"$imageSource\" alt=\"{$item->text}\" title=\"{$item->text}\" $width $height />";
                            }
                        }
                    }
                    //Author
                    $author = JFactory::getUser($item->created_by);
                    $item->creater = $author->name;

                    if ($showHits) {
                        $item->hits = isset($item->hits) ? $item->hits : 0;
                    } else {
                        $item->hits = null;
                    }
                }
            }

            return $items;
        }
         /**
		 * parser a image in the content.
		 * @param object $row object content
		 * @param object $params
		 * @return string image
		 */
		function parseImages(&$row, $params,$context = 'content')
		{
			if($context == 'k2'){
				$arrImages = $this->getK2Images($row, $context);
				if(!empty($arrImages)){
					
					return $arrImages['imageGeneric'];
				}
			}
			
			$jaimage = JAImage::getInstance();
			return $jaimage->parseImage($row ); 
			
			return;
		}
	   /**
		 *
		 * Get image in k2 item
		 * @param object $item
		 * @param string $context
		 * @return array
		 */
		function getK2Images($item, $context = 'content')
		{
			jimport('joomla.filesystem.file');
			//Image
			$arr_return = array();

			if ($context == 'k2') {
				if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_XS.jpg'))
					$arr_return['imageXSmall'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_XS.jpg';

				if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_S.jpg'))
					$arr_return['imageSmall'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_S.jpg';

				if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_M.jpg'))
					$arr_return['imageMedium'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_M.jpg';

				if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_L.jpg'))
					$arr_return['imageLarge'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_L.jpg';

				if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_XL.jpg'))
					$arr_return['imageXLarge'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_XL.jpg';

				if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_Generic.jpg'))
					$arr_return['imageGeneric'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_Generic.jpg';
			} else {
				//com content
			}

			return $arr_return;
		}
        /**
         *
         * Get K2 category children
         * @param int $catid
         * @param boolean $clear if true return array which is removed value construction
         * @return array
         */
	    public static function getK2CategoryChildren($catid, $clear = false) {

			static $array = array();
			if ($clear)
			$array = array();
			$user = JFactory::getUser();
			$aid = $user->get('aid') ? $user->get('aid') : 1;
			$catid = (int) $catid;
			$db = JFactory::getDBO();
			$query = "SELECT * FROM #__k2_categories WHERE parent={$catid} AND published=1 AND trash=0 AND access<={$aid} ORDER BY ordering ";
			$db->setQuery($query);
			$rows = $db->loadObjectList();

			foreach ($rows as $row) {
				array_push($array, $row->id);
				if (modJABulletin::hasK2Children($row->id)) {
					modJABulletin::getK2CategoryChildren($row->id);
				}
			}
			return $array;
		}


		/**
		 *
		 * Check category has children
		 * @param int $id
		 * @return boolean
		 */
		public static function hasK2Children($id) {

			$user = JFactory::getUser();
			$aid = $user->get('aid') ? $user->get('aid') : 1;
			$id = (int) $id;
			$db = JFactory::getDBO();
			$query = "SELECT * FROM #__k2_categories WHERE parent={$id} AND published=1 AND trash=0 AND access<={$aid} ";
			$db->setQuery($query);
			$rows = $db->loadObjectList();

			if (count($rows)) {
				return true;
			} else {
				return false;
			}
		}

        /**
         *
         * Get list articles
         * @param object $params
         * @return array
         */
        public function getAticles($params)
        {
            // Get the dbo
            $db = JFactory::getDbo();
			$app = JFactory::getApplication();
			
			// Get an instance of the generic articles model
			
	        if (version_compare(JVERSION, '3.0', 'ge'))
			{
				$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
			}
			else if (version_compare(JVERSION, '2.5', 'ge'))
			{
				$model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
			}
			else
			{
				$model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
			}
			// Set application parameters in model
			$appParams = JFactory::getApplication()->getParams();
			$model->setState('params', $appParams);

			$model->setState('list.select', 'a.fulltext, a.id, a.title, a.alias, a.introtext, a.state, a.images, a.catid, a.created, a.created_by, a.created_by_alias,' .
								' a.modified, a.modified_by,a.publish_up, a.publish_down, a.attribs, a.metadata, a.metakey, a.metadesc, a.access,' .
								' a.hits, a.featured, a.ordering, c.alias AS category_alias, ' .
								' LENGTH(a.fulltext) AS readmore');

			// Set the filters based on the module params
			$model->setState('list.start', 0);
			$model->setState('list.limit', (int)trim($params->get('count', 5)));
			
			if ((int)trim($params->get('count', 5))==0) {
				$model->setState('filter.published', 10);
			}
			else {
				$model->setState('filter.published', 1);
			}

			// Access filter
			$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
			$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
			$model->setState('filter.access', $access);

			// Category filter
			if($params->get('category')){
				$model->setState('filter.category_id', $params->get('category'));
			}

			$featured = $params->get('show_featured', 1);
			if(!$featured){
				$model->setState('filter.featured', 'hide');
			}
			elseif($featured==2){
				$model->setState('filter.featured', 'only');
			}
			
			// Filter by language
			$model->setState('filter.language', $app->getLanguageFilter());

			// Set ordering
			$ordering = $params->get('type', 'latest');
			if($ordering=='latest'){
				// Set ordering
        		$order_map = array(
        			'm_dsc' => 'a.modified DESC, a.created',
        			'mc_dsc' => 'CASE WHEN (a.modified = '.$db->quote($db->getNullDate()).') THEN a.created ELSE a.modified END',
        			'c_dsc' => 'a.created',
        			'p_dsc' => 'a.publish_up',
        		);
        		$ordering = JArrayHelper::getValue($order_map, $params->get('ordering', 'm_dsc'), 'a.publish_up');
			}
			else{
				$ordering =  'a.hits';
			}
			$dir = 'DESC';
            if($ordering !== 'latest' && $params->get('timerange')){
			 
			   $model->setState('filter.date_filtering', 'relative');
			   $model->setState('filter.relative_date', $params->get('timerange'));
			}  
			$model->setState('list.ordering', $ordering);
			$model->setState('list.direction', $dir);

			$items = $model->getItems();

			if($items){

				$i = 0;
				$showHits = $params->get('show_hits',0);
				$showHits = $showHits == "1"?true:false;
				$showimg = $params->get ( 'show_image', 1 );
				$w = ( int ) $params->get ( 'width', 80 );
				$h = ( int ) $params->get ( 'height', 96 );
				$showdate = $params->get ( 'show_date', 1 );

				$thumbnailMode = $params->get( 'thumbnail_mode', 'crop' );
				$aspect 	   = $params->get( 'use_ratio', '1' );
				$crop = $thumbnailMode == 'crop' ? true:false;
				$lists = array ();
				$jaimage = JAImage::getInstance();


				foreach ($items as &$item) {
					$item->slug = $item->id.':'.$item->alias;
					$item->catslug = $item->catid.':'.$item->category_alias;

					if ($access || in_array($item->access, $authorised))
					{
						// We know that user has the privilege to view the article
						$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
					}
					else {
						$item->link = JRoute::_('index.php?option=com_user&view=login');
					}

					$item->introtext = JHtml::_('content.prepare', $item->introtext);
					$item->text = htmlspecialchars ( $item->title );

					if ($showdate) {
						$item->date = $item->modified == null||$item->modified==""||$item->modified=="0000-00-00 00:00:00" ? $item->created : $item->modified;
					}
					$item->image = '';
					if ($showimg) {
							$imageSource = $jaimage->parseImage( $item );
							if ( $imageSource ) {
								if( $thumbnailMode != 'none' ) {
									$imageURL = $jaimage->resize( $imageSource, $w, $h, $crop, $aspect );
									if( $imageURL ){
										if ( $imageURL == $imageSource ) {
											$width = $w ? "width=\"$w\"" : "";
											$height = $h ? "height=\"$h\"" : "";
											$item->image = "<img src=\"$imageURL\" alt=\"{$item->text}\" title=\"{$item->text}\" $width $height />";
										} else {
											$item->image = "<img src=\"$imageURL\" alt=\"{$item->text}\" title=\"{$item->text}\" />";
										}
									} else {
										$item->image = '';
									}
								} else {
									$width = $w ? "width=\"$w\"" : "";
									$height = $h ? "height=\"$h\"" : "";
									$item->image = "<img src=\"$imageSource\" alt=\"{$item->text}\" title=\"{$item->text}\" $width $height />";
								}
							}
						}

						$item->creater = isset($item->author)?$item->author:$item->created_by_alias;
						if($showHits)
						{
							$item->hits = isset($item->hits)?$item->hits:0;
						}
						else
						{
							$item->hits = null;
						}
				}
			}

			return $items;
		}
	}
}
