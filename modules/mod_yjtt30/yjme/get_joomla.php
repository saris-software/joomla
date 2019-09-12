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
require_once (JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_content'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'route.php');

		$get_items             		= $params->get   ('get_items',NULL);
		$nitems                		= $params->get   ('nitems',4);
		$chars                 		= $params->get   ('chars',40);
		$ordering              		= $params->get   ('ordering');
		$getspecific 	         	= $params->get ('getspecific');
		$allow_tags					= $params->get ('allow_tags');
		$allow_tags = str_replace(" />", ">", $allow_tags);	
		$show_frontpage				= $params->get ('show_frontpage');
		global $access;
		global $access_redirect;
		$access_redirect 			= $params->get ('access_redirect',1); //  0 dont show | 1 show reg only items with redirect
		if($access_redirect == 1){
			$access = 1; //	0 dont show restricted | 1 show restricted
		}else{
			$access = 0;
		}
		$frontpage  = $show_frontpage == 0 ? 'AND f.content_id IS NULL':'';
		$a_accsess 	= 'AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid;
		$where		= 'a.state = 1'
			. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;
		// select specific items
		if(!empty($getspecific)){
		$countitems = count($getspecific);
		}
		if(!empty($getspecific) && $countitems > 0 ){
			$specificitems = implode(",", $getspecific);
			$specific_order= 'field(a.id,'.$specificitems.')';
			$where .= ' AND a.id IN ('.$specificitems.')';
		}else{
			$specificitems='';
			$specific_order='NULL';
			// 10-8-2011 multicats addon |  was $where .= ' AND cc.id = '.$get_items.'';
			$count_cats = count($get_items);
			if($count_cats > 1){
				$multi_cats = implode(",", $get_items);
				$where .= ' AND cc.id IN ('.$multi_cats.')';
			}elseif($count_cats == 1){
				if(is_array($get_items)){
					$multi_cats = implode(",", $get_items);
					$where .= ' AND cc.id ='.$multi_cats.'';
				}else{
					$where .= ' AND cc.id ='.$get_items.'';
				}
			}else{
				$where .= '';
			}
		}   // end multicats addon
		/* set items order */
		$ord = array(
			1=>'ordering',
			2=>'hits',
			3=>'RAND()',
			4=>'created ASC',
			5=>'created DESC',
			6=>$specific_order
		);
		$order = $ord[$ordering];
		/* get items */
		$sql = 	'SELECT a.*, ' .
				' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'. 
				' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug,'.
				'cc.title as cattitle,'.
				'u.username as username,'.
				'u.name as realname,'.
				'f.content_id as frontpageid'.
				
				' FROM #__content AS a' .
				' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
				' INNER JOIN #__users AS u ON u.id = a.created_by' .
				' LEFT JOIN #__content_frontpage f ON f.content_id = a.id' .
				' WHERE '. $where .'' .
				($access == 0 ? $a_accsess : '').
				' AND cc.published = 1' .
				' '.$frontpage.
				' ORDER BY '.$order .' LIMIT 0,'.$nitems.'';
					
		$db->setQuery( $sql );
		$load_items = $db->loadObjectList();
	
		
?>