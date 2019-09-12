<?php

/**
* BDTheme Master Slider - main PHP file
* @package Joomla!
* @Copyright (C) 2011-2014 bdthemes.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @ version $Revision: Master Slider 1.0 $
**/

// no direct access
defined('_JEXEC') or die;
// Model class loading

// Image class loading
require_once (dirname(__FILE__).DS.'class.image.php');

class bdthemes_ms_core_Controller {
	// configuration array
	private $config;
	// module info
	private $module;
	// article data
	private $articles;
    private $articlesK2;
	// constructor
	function __construct($module, $config) {
		// init the style config
		$this->config = $config;
		// init the module info
		$this->module = $module;

		$this->generateView();
	}

	// generate view
	function generateView() {
		// generate the head section
		$document = JFactory::getDocument();
		$uri = JURI::getInstance();
		$lang = JFactory::getLanguage();
		// get the head data
		$headData = $document->getHeadData();
		// generate keys of script section
		$headData_js_keys = array_keys($headData["scripts"]);
		// generate keys of css section
		$headData_css_keys = array_keys($headData["style"]);
		// set variables for false
		$engine_founded = false;
		$css_founded = false;
		// searching engine in scripts paths
		if(array_search(BDT_MS_CORE_URL.'masterslider.min.js', $headData_js_keys) > 0) {
			$engine_founded = true;
		}
		// searching css in CSSs paths
		if(array_search(BDT_MS_CORE_URL.'style/masterslider.css', $headData_css_keys) > 0) {
			$css_founded = true;
		}

		$document->addScript(BDT_MS_CORE_URL.'jquery.easing.min.js');
		
		// if mootools file doesn't exists in document head section
		if(!$engine_founded){ 
			// add new script tag connected with mootools from module
			$document->addScript(BDT_MS_CORE_URL.'masterslider.min.js');
		}
		// if CSS not found
		if(!$css_founded == 1) {
			// add stylesheets to document header
			$document->addStyleSheet(BDT_MS_CORE_URL.'style/masterslider.css' );
			if ($lang->isRTL()) {
				$document->addStyleSheet(BDT_MS_CORE_URL.'style/rtl.css' );
			}
		}

		// Responsive css 
		$document->addStyleSheet(BDT_MS_CORE_URL.'style/media-query.css' );
	}
}

// EOF