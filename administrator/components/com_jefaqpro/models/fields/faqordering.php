<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class JFormFieldFaqordering extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since   1.7
	 */
	protected $type = 'Faqordering';

	/**
	 * Method to get the list of siblings in a menu.
	 * The method requires that parent be set.
	 *
	 * @return  array  The field option objects or false if the parent field has not been set
	 * @since   1.7
	 */
	protected function getOptions()
	{
		$options = array();
		$db = JFactory::getDbo();

		$categoryId	= (int) $this->form->getValue('catid');

		$query = 'SELECT ordering AS value, questions AS text' .
					' FROM #__jefaqpro_faq' .
					' WHERE catid = ' . (int) $categoryId .
					' ORDER BY ordering';

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		$options = array_merge(
			array(array('value' => '-1', 'text' => JText::_('COM_JEFAQPRO_ITEM_FIELD_ORDERING_VALUE_FIRST'))),
			$options,
			array(array('value' => '-2', 'text' => JText::_('COM_JEFAQPRO_ITEM_FIELD_ORDERING_VALUE_LAST')))
		);

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	/**
	 * Method to get the field input markup
	 *
	 * @return  string  The field input markup.
	 * @since   1.7
	 */
	protected function getInput()
	{
		if ($this->form->getValue('id', 0) == 0)
		{
			return '<span class="readonly">' . JText::_('COM_JEFAQPRO_ITEM_FIELD_ORDERING_TEXT') . '</span>';
		}
		else
		{
			return parent::getInput();
		}
	}
}
