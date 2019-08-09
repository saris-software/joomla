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
 * Supports an HTML select list of themes
 */
class JFormFieldThemes extends JFormFieldList
{
	/**
	 * @var		string	The form field type.
	 */
	public $type = 'Themes';

	/**
	 * Method to get the field options.
	 */
	protected function getOptions()
	{
		// Initialise variables.
		$options		= array();
		$db				= JFactory::getDbo();
		$query			= $db->getQuery(true);

		$query->select('id, themes AS text');
		$query->from('#__jefaqpro_themes');
		$query->order('id');
		$db->setQuery($query);

		$themes			= $db->loadObjectList();

		foreach ( $themes as $key=>$value ) {
			$options[]	= JHTML::_('select.option',  $value->id, $value->text);
		}

		return $options;
	}
}