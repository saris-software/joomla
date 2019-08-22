<?php

/**
* Helper class for Master Slider Module
*
* @package Joomla!
* @Copyright (C) 2011-2014 bdthemes.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @ version $Revision: Master Slider 1.0 $
**/

// access restriction
defined('_JEXEC') or die('Restricted access');
// Main GK Tab class
class BDTMasterSliderHelper {
	// configuration array
	private $config;
	// module info
	private $module;
	// constructor
	public function __construct($module, $params) {
		// initialize config array
		$this->config = array();
		// init the module info
		$this->module = $module;
		// basic settings
		$this->config['automatic_module_id'] = $params->get('automatic_module_id', 1); //
		$this->config['module_id'] = ($this->config['automatic_module_id'] == 1) ? 'bdt-ms-' . $module->id : $params->get('module_id', 'bdt-ms-1'); //
		$this->config['styles'] = $params->get('module_style', 'simple_slider');
		// get the JSON slides and config data
		$this->config['image_show_data'] = $params->get('image_show_data', '[]');
		$this->config['config'] = $params->get('config', '{}');
		$this->config['last_modification'] = $params->get('last_modification', 0);
		// parse JSON data
		$this->config['image_show_data'] = json_decode($this->config['image_show_data']);
		$this->config['config'] = json_decode($this->config['config']);
		// advanced
		$this->config['generate_thumbnails'] = $params->get('generate_thumbnails', '1');
		$this->config['use_style_css'] = $params->get('use_style_css', 1);
	}
	// function to render module code
	public function render() {
		// include core Controller
		require_once('core'.DS.'controller.php');
		$mscore = new bdthemes_ms_core_Controller($this->module, $this->config);

		require_once('styles'.DS.$this->config['styles'].DS.'controller.php');	
		// initialize Controller
		$controller_class = 'BDT_' . $this->config['styles'] . '_Controller';
		$controller = new $controller_class($this->module, $this->config);
	}
}

/* eof */