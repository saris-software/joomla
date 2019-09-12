<?php
/**
 * @package		Youjoomla Extend Elements
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2010 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
//<field name="handler" type="yjhandler"/>   add once in xml to load custom codes
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
/**
 * Renders a spacer element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JFormFieldYjHandler extends JFormField
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	
	var	$type = 'YjHandler';
public function getInput()
	{
		$e_folder = basename(dirname(dirname(__FILE__)));
		$document = JFactory::getDocument();
		
		if(intval(JVERSION) >= 3 ){	
			$document->addStyleSheet(JURI::root() . 'modules/'.$e_folder.'/elements/css/stylesheet30.css');
			$document->addScript(JURI::root() . 'modules/'.$e_folder.'/elements/src/yjsourceswitch30.js');
		}else{
			$document->addStyleSheet(JURI::root() . 'modules/'.$e_folder.'/elements/css/stylesheet.css');
			$document->addScript(JURI::root() . 'modules/'.$e_folder.'/elements/src/yjsourceswitch.js');
		}
			
	
		echo '<div id="selectedresult"></div>';
		
		return ;
	}
		public function getLabel() {
		return false;
	}
}
