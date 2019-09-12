<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Favicon
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.modeladmin');

class PhocaFaviconCpModelPhocaFavicon extends JModelAdmin
{
	function __construct() {
		parent::__construct();
	}
	
	protected	$option 		= 'com_phocafavicon';
	protected 	$text_prefix	= 'com_phocafavicon';
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocafavicon.phocafavicon', 'phocafavicon', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
/*
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		// Load the Phoca favicon data
		if ($this->_loadData())
		{
			// Initialize some variables
			$user = &JFactory::getUser();
		}
		else  $this->_initData();
		return $this->_data;
	}
	*/
	function create($data, &$message) {
		
		//If this file doesn't exists don't save it
		if (!PhocaFaviconHelper::existsFileOriginal($data['filename'])) {
			$message = JText::_('COM_PHOCAFAVICON_ERROR_FILENAME_NOT_EXISTS');
			return false;
		}
		
		$path 			= PhocaFaviconHelper::getPathSet();
		$orig_path 		= $path['orig_abs_ds'];
		$refresh_url 	= 'index.php?option=com_phocafavicon&task=phocafavicon.favicon';
		$thumbnail_name = PhocaFaviconHelper::getOrCreateThumbnailIco($message, $orig_path, $data['filename'], $data['template'], $refresh_url, 1);//small is favicon
		
		if (!$thumbnail_name) {
			return false;
		}

		//Clean Thumbs Folder if there are thumbnail files but not original file
		PhocaFaviconHelper::cleanThumbsFolder();
		return true;
	}
/*
	function _loadData()
	{
		if (empty($this->_data))
		{
			$this->_data = PhocaFaviconHelper::getTemplateFolders();
			return (boolean) $this->_data;
		}
		return true;
	}
	
	function _initData()
	{
		if (empty($this->_data))
		{
			$phocafavicon = new stdClass();
			$phocafavicon->name				= null;
			$phocafavicon->path_with_name	= null;
			$this->_data					= $phocafavicon;
			return (boolean) $this->_data;
		}
		return true;
	}*/
}
?>