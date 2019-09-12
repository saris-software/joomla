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
require_once (BDT_MS_URI.'/core/model.php');

class BDT_bdt_tab_slider_Controller {
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
		// init the articles array
		$this->articles = array();
        $this->articlesK2 = array();
		// check the module images
		$this->checkImages();
		// get the articles data
		$this->getArticleData();
		// generate the view
		$this->generateView();
	}
	// check the images
	function checkImages() {
		// if the thumbnail generation is enabled
		if($this->config['generate_thumbnails'] == 1) {
			// basic images params		
			$img_width = $this->config['config']->bdt_tab_slider->bdt_tab_slider_image_width;
			$img_height = $this->config['config']->bdt_tab_slider->bdt_tab_slider_image_height;
			$img_bg = $this->config['config']->bdt_tab_slider->bdt_tab_slider_image_bg;
			$quality = $this->config['config']->bdt_tab_slider->bdt_tab_slider_quality;
			// check the slides
			$bdt_image_resize = new bdt_image_resize();
			foreach($this->config['image_show_data'] as $slide) {
				$stretch = ($slide->stretch == 'nostretch') ? false : true;
				$bdt_image_resize->createThumbnail($slide->image, $this->config, $img_width, $img_height, $img_bg, $stretch, $quality);	
			}
		}
	}
	// get the articles data
	function getArticleData() {
		// create the array
		$ids = array();
        $idsK2 = array();
		// generate the content of the array
		foreach($this->config['image_show_data'] as $slide) {
			if($slide->type == 'article') {
				array_push($ids, $slide->art_id);
			}
            if($slide->type == 'k2') {
				array_push($idsK2, $slide->artK2_id);
			}
		}
		// get the data
		if(count($idsK2) > 0) {
			$this->articlesK2 = BDT_Master_Slider_Model::getDataK2($idsK2);
		}
		if(count($ids) > 0) {
			$this->articles = BDT_Master_Slider_Model::getData($ids);
		}
	}
	// generate view
	function generateView() {
		// generate the head section
		$document = JFactory::getDocument();
		$uri = JURI::getInstance();
		// get the head data
		$headData = $document->getHeadData();
		// generate keys of script section
		$headData_js_keys = array_keys($headData["scripts"]);
		// generate keys of css section
		$headData_css_keys = array_keys($headData["style"]);
		// set variables for false
		$engine_founded = false;
		$css_founded = false;

		//searching css in CSSs paths
		if(array_search(BDT_MS_STYLE_URL.$this->config['styles'].'/skins/default/style.css', $headData_css_keys) > 0) {
			$css_founded = true;
		}

		// if CSS not found
		if(!$css_founded && $this->config['use_style_css'] == 1) {
			// add stylesheets to document header
			$document->addStyleSheet(BDT_MS_CORE_URL.'skins/'. $this->config['config']->bdt_tab_slider->bdt_tab_slider_skins_style.'/style.css' );
		}

		$document->addStyleSheet(BDT_MS_STYLE_URL.$this->config['styles'].'/style/ms-tabs-style.css' );

		// generate necessary variables
		$width = $this->config['config']->bdt_tab_slider->bdt_tab_slider_image_width;
		$height = $this->config['config']->bdt_tab_slider->bdt_tab_slider_image_height;
		// load view
		require(dirname(__FILE__).DS.'view.php');
	}
}

// EOF