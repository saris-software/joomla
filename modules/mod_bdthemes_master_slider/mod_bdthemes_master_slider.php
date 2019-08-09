<?php

/**
* BdThemes Master Slider - main PHP file
* @package Joomla!
* @Copyright (C) 2011-2014 bdthemes.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @ version $Revision: Master Slider 1.0 $
**/

// no direct access
defined('_JEXEC') or die;
if(!defined('DS')){
   define('DS',DIRECTORY_SEPARATOR);
}

define('BDT_MS_URI',dirname(__FILE__));
define('BDT_MS_URL',JURI::root().'modules/mod_bdthemes_master_slider/');
define('BDT_MS_CORE_URL',BDT_MS_URL.'core/');
define('BDT_MS_STYLE_URL',BDT_MS_URL.'styles/');
define('BDT_MS_SKIN_URL',BDT_MS_URL. 'core/skins/');

// helper loading
require_once (BDT_MS_URI . '/helper.php');
// create class instance with params
$helper = new BDTMasterSliderHelper($module, $params); 
// creating XHTML code	
$helper->render();

/* eof */