<?php

/**
 * @package		Joomla.Site
 * @subpackage	mod_je_socialprofiles
 * @copyright	Copyright (C) 2004 - 2015 jExtensions.com - All rights reserved.
 * @license		GNU General Public License version 2 or later
 */

 // no direct access

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

class JFormFieldIcons extends JFormField 

{
	protected $type = 'Icons';
	public function getLabel() {

			// Initialize variables.

			$label = '';
			// Get the label text from the XML element, defaulting to the element name.
			$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
			// Build the class for the label.
			$class = !empty($this->description) ? 'hasTip hasTooltip' : '';
			$class = $this->required == true ? $class.' required' : $class;
			$icon_class = str_replace("jform_params_","",$this->id);
			// Add replace checkbox
			$replace = '<span id="IconOut" class="'.$icon_class.'Out" ><span id="Icon" class="'.$icon_class.'"></span></span>';	
			// Add the opening label tag and main attributes attributes.
			$label .= '<label id="'.$this->id.'" for="'.$this->id.'" class="'.$class.'"';
			// If a description is specified, use it to build a tooltip.
			if (!empty($this->description)) {
					$label .= ' title="'.htmlspecialchars(trim(JText::_($text), ':').'::' .
									JText::_($this->description), ENT_COMPAT, 'UTF-8').'"';
			}
			// Add the label text and closing tag.
			$label .= '>'.$replace.JText::_($text).'</label>';
			return $label; 
	}
	protected function getInput()
	{
		
	
		return '<input type="text" name="'.$this->name.'" id="'.$this->id.'"'.' value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'"class=""/>';	
	
	}

		
}

?>

