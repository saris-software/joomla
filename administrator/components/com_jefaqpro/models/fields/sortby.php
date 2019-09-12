<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Supports an HTML select list of Sortby
 */
class JFormFieldSortby extends JFormFieldList
{
	/**
	 * @var		string	The form field type.
	 */
	public $type = 'Sortby';

	/**
	 * Method to get the field options.
	 */
	protected function getOptions()
	{
		$options = array(
			JHTML::_('select.option',  'desc', JText::_( 'JE_SORT_DESCENDING' )),
			JHTML::_('select.option',  'asc', JText::_( 'JE_ASCENDING' )),
		);

		return $options;
	}
}