<?php
/**
 * ------------------------------------------------------------------------
 * JA Slideshow Module for Joomla 2.5 & 3.x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

$app = JFactory::getApplication();

$basepath = 'modules/' . $module->module . '/assets/';

//load override css
$templatepath = 'templates/'.$app->getTemplate().'/css/'.$module->module;


if (!defined('_MODE_JASLIDESHOW2_ASSETS_')) {
	define('_MODE_JASLIDESHOW2_ASSETS_', 1);
	
	
	JHtml::_('stylesheet', $basepath . 'themes/default/style.css');
	JHtml::_('script',  $basepath . 'script/script.js');
	if (!empty($skin)) {
		if(JFile::exists( JPATH_SITE . '/' . $basepath . 'themes/' . $skin . '/style.css')){
			JHtml::_('stylesheet', $basepath . 'themes/' . $skin . '/style.css');
		}
		if(JFile::exists(JPATH_SITE . '/' . $basepath . 'themes/' . $skin . '/' . $module->module . '.css')){
			JHtml::_('stylesheet',  $basepath . 'themes/' . $skin . '/' . $module->module . '.css');
		}
	
		//add style for T3 v3
		if (JFile::exists(JPATH_SITE . '/' . $templatepath . '-'. $skin .'.css')){
			JHtml::_('stylesheet', $templatepath . '-'. $skin .'.css');
		}
	}
	
	if (JFile::exists(JPATH_SITE . '/' . $templatepath . '.css')){
		JHtml::_('stylesheet', $templatepath . '.css');
	}
	
} elseif (!empty($skin)) {
	if(JFile::exists(JPATH_SITE . '/' . $basepath . 'themes/' . $skin . '/style.css')){
		JHtml::_('stylesheet',  $basepath . 'themes/' . $skin . '/style.css');
	}
	if(JFile::exists(JPATH_SITE . '/' . $basepath . 'themes/' . $skin . '/' . $module->module . '.css')){
		JHtml::_('stylesheet',  $basepath . 'themes/' . $skin . '/' . $module->module . '.css');
	}

	//add style for T3 v3
	if (JFile::exists(JPATH_SITE . '/' . $templatepath . '-'. $skin .'.css')){
		JHtml::_('stylesheet', $templatepath . '-'. $skin .'.css');
	}
}