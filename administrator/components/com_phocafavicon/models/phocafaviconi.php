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

jimport('joomla.application.component.model');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class PhocaFaviconCpModelPhocaFaviconi extends JModelLegacy
{
	function getFolderState($property = null)
	{
		static $set;

		if (!$set) {
			$folder = JRequest::getVar( 'folder', '', '', 'path' );
			$this->setState('folder', $folder);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			$set = true;
		}
		return parent::getState($property);
	}

	function getImages()
	{
		$list = $this->getList();
		return $list['images'];
	}

	function getFolders()
	{
		$list = $this->getList();
		return $list['folders'];
	}

	function getList()
	{
		static $list;
		
		$errorMsg = '';

		// Only process the list once per request
		if (is_array($list)) {
			return $list;
		}

		// Get current path from request
		$current 	= $this->getState('folder');
		$field		= JRequest::getVar('field');

		// If undefined, set to empty
		if ($current == 'undefined') {
			$current = '';
		}

		//Get folder variables from Helper
		$path = PhocaFaviconHelper::getPathSet();
		
		// Initialize variables
		if (strlen($current) > 0) {
			$orig_path = $path['orig_abs_ds'].$current;
		} else {
			$orig_path = $path['orig_abs_ds'];
		}
		$orig_path_server 	= str_replace(DS, '/', $path['orig_abs'] .'/');
		

		$images 	= array ();
		$folders 	= array ();

		// Get the list of files and folders from the given folder
		$file_list 		= JFolder::files($orig_path);
		$folder_list 	= JFolder::folders($orig_path, '', false, false, array(0 => 'thumbs'));
		
		// Iterate over the files if they exist
		//file - abc.img, file_no - folder/abc.img
		if ($file_list !== false)
		{
			foreach ($file_list as $file)
			{
				if (is_file($orig_path.DS.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html')
				{
					//Create thumbnails small, medium, large
					$refresh_url 	= 'index.php?option=com_phocafavicon&view=phocafaviconi&tmpl=component&folder='.$current.'&field='.$field;
					$file_no		= $current . "/" . $file;
					$file_thumb 	= PhocaFaviconHelper::getOrCreateThumbnail($errorMsg, $path['orig_abs_ds'], $file_no, $refresh_url, 0, 1, 0);
					
					$tmp 								= new JObject();			
					$tmp->name 							= $file_thumb['name'];
					$tmp->path_with_name_relative_no	= $file_thumb['path_with_name_relative_no']	;			
					$tmp->linkthumbnailpath				= str_replace("../", "", $file_thumb['thumb_name_m_no_rel']);					
					$images[] = $tmp;
				}
			}
		}

		//Clean Thumbs Folder if there are thumbnail files but not original file
		PhocaFaviconHelper::cleanThumbsFolder();
		//---------------------------------------------------------------------------------------------------------------
		
		// Iterate over the folders if they exist
		if ($folder_list !== false)
		{
			foreach ($folder_list as $folder)
			{
				$tmp 							= new JObject();
				$tmp->name 						= basename($folder);
				$tmp->path_with_name 			= str_replace(DS, '/', JPath::clean($orig_path . DS . $folder));
				$tmp->path_without_name_relative= $path['orig_rel_ds'] . str_replace($orig_path_server, '', $tmp->path_with_name);
				$tmp->path_with_name_relative_no= str_replace($orig_path_server, '', $tmp->path_with_name);	

				$folders[] = $tmp;
			}
		}

		$list = array('folders' => $folders, 'images' => $images);
		return $list;
	}
}
?>