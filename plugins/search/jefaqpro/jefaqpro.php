<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

/**
 * JE FAQPro Search plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Search.jefaqpro
 * @since		1.7
 */

class plgSearchJefaqpro extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	function onContentSearchAreas()
	{
		static $areas = array(
			'jefaqpro' => 'PLG_SEARCH_JEFAQPRO'
			);
		return $areas;
	}

	function onContentSearch( $text, $phrase='', $ordering='', $areas=null )
	{
		// Joomla predefined function for db connection
		
			$db    		= JFactory::getDBO();
			$app		= JFactory::getApplication();
			$user		= JFactory::getUser();
			$groups		= implode(',', $user->getAuthorisedViewLevels());
			$tag 		= JFactory::getLanguage()->getTag();

			require_once JPATH_SITE.'/components/com_jefaqpro/helpers/route.php';
			require_once JPATH_SITE.'/administrator/components/com_search/helpers/search.php';

		 	$searchText = $text;

		//If the array is not correct, return it:
		
			if (is_array( $areas )) {
					if (!array_intersect( $areas, array_keys( $this->onContentSearchAreas() ) )) {
						return array();
					}
			}

			//Now define the parameters like this:
			
			$limit 			= $this->params->get( 'searchlimit',	50 );
			$categorised	= $this->params->get( 'searchcategory',	0 );

			$nullDate		= $db->getNullDate();
			$date 			= JFactory::getDate();
			$now 			= $date->toSql();

			$text = trim( $text );
			if ($text == '') {
				return array();
			}

			$query	= $db->getQuery(true);
			$lists = array();
			$wheres = array();
			switch ($phrase) {

				case 'exact':
						$text		= $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
						$wheres2	= array();

						if ( $categorised ) {
							 $wheres2[]	= 'LOWER(c.description) LIKE '.$text;
						} else {
							$wheres2[]	= 'LOWER(a.questions) LIKE '.$text;
							$wheres2[]	= 'LOWER(a.answers) LIKE '.$text;
						}
						$where		= '(' . implode( ') OR (', $wheres2 ) . ')';
						break;

			//search all or any
				case 'all':
				case 'any':

			//set default
				default:
						$words		= explode( ' ', $text );
						$wheres		= array();
						foreach ($words as $word)
						{
								$word		= $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
								$wheres2	= array();

								if ( $categorised ) {
									$wheres2[]	= 'LOWER(c.description) LIKE '.$word;
								} else {
									$wheres2[]	= 'LOWER(a.questions) LIKE '.$word;
									$wheres2[]	= 'LOWER(a.answers) LIKE '.$word;
								}
								$wheres[]	= implode( ' OR ', $wheres2 );
						}
						$where	= '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
						break;
			}

		//ordering of the results
			switch ( $ordering ) {

				//alphabetic, ascending
					case 'alpha':
							if ( $categorised ) {
								$order = 'c.description ASC';
							} else {
								$order = 'a.questions ASC';
							}
							break;

				//oldest first
					case 'oldest':
							if ( $categorised ) {
								$order = 'c.description ASC';
							} else {
								$order = 'a.ordering ASC';
							}
							break;

				//popular first
					case 'popular':

				//newest first
					case 'newest':
							if ( $categorised ) {
								$order = 'c.description DESC';
							} else {
								$order = 'a.ordering DESC';
							}
							break;

				//default setting: alphabetic, ascending
					default:
							if ( $categorised ) {
								$order = 'c.description ASC';
							} else {
								$order = 'a.questions ASC';
							}
			}

			if ( $categorised ) {
				$query->select('a.questions AS title, a.posted_date AS created, '
						.'CONCAT(a.questions, a.answers) AS text, '
						.'CASE WHEN CHAR_LENGTH(a.questions) THEN CONCAT_WS(":", a.id) ELSE a.id END as slug, '
						.'CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id) ELSE c.id END as catslug, '
						.'CONCAT_WS("/", c.title) AS section, "2" AS browsernav' );
				$query->from('#__jefaqpro_faq AS a');
				$query->innerJoin('#__categories AS c ON c.id=a.catid' );
				$query->where('a.published = 1 AND c.published = 1');

			} else {
				$query->select('a.questions AS title, a.posted_date AS created, '
						.'CONCAT(a.questions, a.answers) AS text, '
						.'CASE WHEN CHAR_LENGTH(a.questions) THEN CONCAT_WS(":", a.id) ELSE a.id END as slug, '
						.'CASE WHEN CHAR_LENGTH(c.title) THEN CONCAT_WS(":", c.id) ELSE c.id END as catslug, '
						.'CONCAT_WS("/", c.title) AS section, "2" AS browsernav' );
				$query->from('#__jefaqpro_faq AS a');
				$query->innerJoin('#__categories AS c ON a.catid = c.id');
				$query->where('('. $where .') AND a.published = 1 AND c.published = 1');
				$query->order($order);
			}

		//Set query
			$db->setQuery( $query, 0, $limit );
			$lists = $db->loadObjectlist();

		//The 'output' of the displayed title with link & text
		 $itemid	= JRequest::getVar('Itemid', 1);

			if (isset($lists))
			{
				foreach($lists as $key => $item)
				{
					if ( $categorised ){
						$lists[$key]->href = jefaqproHelperRoute::getCategoryRoute($item->catslug);
					}
					else
						$lists[$key]->href = jefaqproHelperRoute::getFaqRoute($item->slug);
				}
			}
			$rows[] = $lists;

			$results = array();
			if (count($rows))
			{
				foreach($rows as $row)
				{
					$new_row = array();
					foreach($row AS $key => $article) {
						if (searchHelper::checkNoHTML($article, $searchText, array('text', 'title', 'metadesc', 'metakey'))) {
							$new_row[] = $article;
						}
					}
					$results = array_merge($results, (array) $new_row);
				}
			}
		return $results;
	}
}