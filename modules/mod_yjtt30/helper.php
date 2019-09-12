<?php
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
if(!class_exists('YJmeHelp') && !function_exists('YJmeItems'))// lets reuse them!
{	


	class YJmeHelp
	{
		
		static function YJmeItems(&$params)
		{
			/* prepare database */
			$db					= JFactory::getDBO();
			$user				= JFactory::getUser();
			$userId				= (int) $user->get('id');
			global $aid;
			$aid				= $user->get('aid', 0);
			$contentConfig 		= JComponentHelper::getParams( 'com_content' );
			$access2			= !$contentConfig->get('shownoauth');
			$nullDate			= $db->getNullDate();
			$date 				= JFactory::getDate();
			if(intval(JVERSION) >= 3 ){	
				$now =         $date->toSql() ; 
			}else{
				$now =         $date->toMySQL(); 
			}
			/* prepare default module params */
			$yj_mod_name		= basename(dirname(__FILE__));// 10-8-2011
			$item_source		= $params->get   ('item_source',1);// 10-8-2011
			switch ($item_source) {
				case 1 :   
					require('modules/'.$yj_mod_name.'/yjme/get_joomla.php');
					require_once('modules/'.$yj_mod_name.'/yjme/jomfunctions.php');
					break; 
				case 2:  
					require('modules/'.$yj_mod_name.'/yjme/get_k2.php');
					require_once('modules/'.$yj_mod_name.'/yjme/k2functions.php');
					break;
			}

			//  this is the main array for k2/joomla news items. both use same vars for ouptut
			$main_yj_arr = array();
			foreach ( $load_items as $row ) {

				switch ($item_source) {
					case 1 :
						$item_url 		= yjme_get_url($row);
						$img_url  		= yjme_art_image($row);
						$cat_url 		= yjme_get_cat_url($row);
						$author_url		= yjme_get_author_url($row);
					break;
						case 2:
						$img_url 		= k2_yjme_art_image($row);
						$item_url		= k2_yjme_get_url($row);
						$cat_url 		= k2_yjme_get_cat_url($row);
						$author_url		= k2_yjme_get_author_url($row);
					break;
				}

				$yj_get_items = array(
						'item_title' 		=> htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'),
						'item_url' 			=> $item_url,
						'item_intro' 		=> substr(strip_tags($row->introtext,''.$allow_tags.''),0,$chars),
						'img_url' 			=> $img_url,
						'cat_title' 		=> htmlspecialchars($row->cattitle, ENT_QUOTES, 'UTF-8') ,
						'cat_url' 			=> $cat_url,
						'item_author' 		=> $row->username,
						'item_author_rn' 	=> $row->realname,
						'author_url' 		=> $author_url,
						'item_date' 		=> JHTML::_('date', $row->created,JText::_('CREATEDATE')),
						'item_id'	 		=> $row->id,
					);
					$main_yj_arr[] = $yj_get_items;
			}
			
					return $main_yj_arr;

		}
		
	}
}

?>