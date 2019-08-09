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
 * Supports an HTML select list of Orderby
 */
class JFormFieldOrderby extends JFormFieldList
{
	/**
	 * @var		string	The form field type.
	 */
	public $type = 'Orderby';

	/**
	 * Method to get the field options.
	 */
	protected function getOptions()
	{
		$options = array(
			JHTML::_('select.option',  'ordering', JText::_( 'JE_ORDERBY_ORDERING' )),
			JHTML::_('select.option',  'id', JText::_( 'JE_ORDERBY_ID' )),
			JHTML::_('select.option',  'questions', JText::_( 'JE_ORDERBY_QUESTIONS' ) ),
			JHTML::_('select.option',  'random', JText::_( 'JE_ORDERBY_RANDOM' ) ),
		);

		return $options;
	}
}