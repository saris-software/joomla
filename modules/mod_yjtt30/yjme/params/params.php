<?php
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
/*Those are changable module params.They will not affect the news engines.These params are dinamic. You can add more or remove the ones that are here. Do not forget to edit/remove the xml param tags for the params changed/added. Also remove the conditions for the param in module template default.php file*/
defined('_JEXEC') or die('Restricted access');
	
	
		$show_title   			 	= $params->get ('show_title');			// Disable/enable item title
		$width 						= $params->get ('width','150px');
		$height 					= $params->get ('height','20px');
		$textalig 					= $params->get ('textalig','center');
		$title_w 					= $params->get ('title_w','90px');
		$show_custom_title 			= $params->get ('show_custom_title','1');
		$custom_text 				= $params->get ('custom_text','Breaking News');
		$time 						= $params->get ('time',5000);
		$duration 					= $params->get ('duration',1000);
		$orient 					= $params->get ('orient','1');
		$type 						= $params->get ('type','scroll'); // scroll  | fade | scrollfade
		$shownav  					= $params->get ('shownav','1');
		$nitems  					= $params->get ('nitems');

		if($orient == 1){
			$float = "float:left;";
			$upd = "width";
		}else{
			$float ="";
			$upd = "height";
		}
		
		
		if ($shownav == 1 ){
			$hold_w = $title_w + $width + 40;
		}elseif ($show_title == 1){
			$hold_w = $title_w + $width;
		}else{
			$hold_w = $width + 0;
		}
		$tpad = $height -0 ;
		$main_w = $width * $nitems ;


/*the headfile.php is moved here in case you need to do some calulations before output or you have params created for your inline JS. This way the headfiles.php sees the params before the load.*/
	require('modules/'.$yj_mod_name.'/yjme/headfiles.php');
?>