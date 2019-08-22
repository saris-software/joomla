<?php
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
//Title: 			$yj_get_items['item_title']
//Author: 			$yj_get_items['item_author'] = username || $yj_get_items['item_author_rn'] = real name
//Image:			$yj_get_items['img_url'] = use isset to check before output
//Intro text:		$yj_get_items['item_intro']
//Create date:		$yj_get_items['item_date']
//Category:			$yj_get_items['cat_title']
//Item url:			$yj_get_items['item_url']
//Author url: 		$yj_get_items['author_url']
//Cat url:			$yj_get_items['cat_url']
//Foreach to be used =  foreach ($main_yj_arr as $yj_get_items){ echo each part here }

/*Image sizing: The images are inside div that is resizing when you enter the values in module parameters. this way there is no image disortion. For those who dont like that , you can add this
style="width:<?php echo $img_width ?>;height:<?php echo $img_height ?>;"
within image tag after alt="" (space it please) and have the images resized */
defined('_JEXEC') or die('Restricted access'); ?>
<!-- Powered by YJ Module Engine find out more at www.youjoomla.com -->
Please edit your new module template.