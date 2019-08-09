<?php
/**
 * ------------------------------------------------------------------------
 * JA Login module for J25 & J3x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

$app = JFactory::getApplication();

$basepath = JURI::root(true).'/modules/' . $module->module . '/assets/';

$doc = JFactory::getDocument();
$doc->addStyleSheet($basepath.'style.css');
//load override css
$templatepath = 'templates/'.$app->getTemplate().'/css/'.$module->module.'.css';
if(file_exists(JPATH_SITE . '/' . $templatepath)) {	
	$doc->addStyleSheet(JURI::root(true).'/'.$templatepath);
}

//script
if (version_compare(JVERSION, '4.0', 'ge'))
	$doc->addScript($basepath.'script_j4.js');
else
	$doc->addScript($basepath.'script.js');