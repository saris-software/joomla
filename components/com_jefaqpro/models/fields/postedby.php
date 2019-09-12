<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 */
class JFormFieldPostedby extends JFormField
{
	/**
	 * The form field type.
	 */
	protected $type = 'Postedby';

	/**
	 * Method to get the field input markup.
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$size						= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$maxLength					= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';

		$user						= JFactory::getUser();
		if ($user->get('id') > 0) {
			$this->element['class'] = 'inputbox';
		} else {
			$this->element['class']	= 'inputbox required';
		}

		$class						= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';

		$readonly					= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled					= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Initialize JavaScript field attributes.
			$onchange				= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		return '<input type="text" name="'.$this->name.'" id="'.$this->id.'"' .
				' value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'"' .
				$class.$size.$disabled.$readonly.$onchange.$maxLength.'/><div id="je-error-'.$this->id.'"></div>';
	}
}
