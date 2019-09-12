<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Supports an HTML select list of file
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldYJcss extends JFormFieldList
{	
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'YJcss';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		// Initialize some field attributes.
		$filter			= (string) $this->element['filter'];
		$exclude		= (string) $this->element['exclude'];
		$stripExt		= (string) $this->element['stripext'];
		$hideNone		= (string) $this->element['hide_none'];
		$hideDefault	= (string) $this->element['hide_default'];

		// Get the path in which to search for file options.
		$path = (string) $this->element['directory'];
		if (!is_dir($path)) {
			$path = JPATH_ROOT.'/'.$path;
		}

		// Prepend some default options based on field attributes.
		if (!$hideNone) {
			$options[] = JHtml::_('select.option', '-1', JText::alt('JOPTION_DO_NOT_USE', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
		}
		if (!$hideDefault) {
			$options[] = JHtml::_('select.option', '', JText::alt('JOPTION_USE_DEFAULT', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
		}

		// Get a list of files in the search path with the given filter.
		$files = JFolder::files($path, $filter);

		// Build the options list from the list of files.
		if (is_array($files)) {
			foreach($files as $file) {

				// Check to see if the file is in the exclude mask.
				if ($exclude) {
					if (preg_match(chr(1).$exclude.chr(1), $file)) {
						continue;
					}
				}

				// If the extension is to be stripped, do it.
				if ($stripExt) {
					$file = JFile::stripExt($file);
				}

				$options[] = JHtml::_('select.option', $file, $file);
			}
		}
		global $yj_mod_name;
		$yj_mod_name = basename(dirname(dirname(__FILE__)));

		$cssedit = JURI::root() . 'modules/'.$yj_mod_name.'/elements/yjeditcss/index.php';
		$cssupload = JURI::root() . 'modules/'.$yj_mod_name.'/elements/yjuploadcss/index.php';
		$csscreate = JURI::root() . 'modules/'.$yj_mod_name.'/elements/yjcreatecss/index.php';
		JHTML::_('behavior.modal', 'a.modal');
		//global $html;
		$html = '
		<div id="css_file">
		<div class="clearbuttons"></div>
			<div class="button2-left">
				<div class="blank">
					<a class="modal  btn btn-success" title="'.JText::_('Edit CSS files').'"  href="'.$cssedit.'" rel="{handler: \'iframe\', size: {x: 700, y: 500}}">'.JText::_('Edit CSS files').'</a>
				</div>
			</div>
			
			<div class="button2-left">
				<div class="blank">
					<a class="modal  btn btn-info" title="'.JText::_('Upload CSS file').'"  href="'.$cssupload.'" rel="{handler: \'iframe\', size: {x: 380, y: 210}}">'.JText::_('Upload new CSS file').'</a>
				</div>
			</div>
			
			<div class="button2-left">
				<div class="blank">
					<a class="modal btn btn-warning" title="'.JText::_('Create new CSS file').'"  href="'.$csscreate.'" rel="{handler: \'iframe\', size: {x: 380, y: 210}}">'.JText::_('Create new CSS file').'</a>
				</div>
			</div>			
		</div>
		
		';
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		echo $html;
		return $options;
		
	}   
	
}
