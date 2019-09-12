<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Supports an HTML select list of Dateformat
 */
class JFormFieldDateformat extends JFormFieldList
{
	/**
	 * @var		string	The form field type.
	 */
	public $type = 'Dateformat';

	/**
	 * Method to get the field options.
	 */
	protected function getOptions()
	{
		// Initialise variables.
		$options		= array();

		$options[]		= JHTML::_('select.option', 'l,j F Y', 'l,j F Y');
		$options[]		= JHTML::_('select.option', 'l,j F Y g:i', 'l,j F Y g:i');
		$options[]		= JHTML::_('select.option', 'l, Y/m/j', 'l, Y/m/j');
		$options[]		= JHTML::_('select.option', 'Y-m-j g:i:s', 'Y-m-j g:i:s');
		$options[]		= JHTML::_('select.option', 'j F Y', 'j F Y');
		$options[]		= JHTML::_('select.option', 'j.m.y', 'j.m.y');
		$options[]		= JHTML::_('select.option', 'y.m.j', 'y.m.j');

		return $options;
	}
}