<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

class jefaqproHelper
{
	public static $extension = 'com_jefaqpro';

	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_JEFAQPRO_FAQ'),
			'index.php?option=com_jefaqpro&view=faqs',
			$vName == 'faqs'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_JEFAQPRO_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_jefaqpro',
			$vName == 'categories'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_JEFAQPRO_GLOBALSETTINGS'),
			'index.php?option=com_jefaqpro&task=settings.edit&id=1',
			$vName 				== 'settings'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_JEFAQPRO_IMPORT'),
			'index.php?option=com_jefaqpro&view=import',
			$vName 				== 'import'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_JEFAQPRO_IMPORT_CSV'),
			'index.php?option=com_jefaqpro&view=importcsv',
			$vName 				== 'importcsv'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_JEFAQPRO_EXPORT_CSV'),
			'index.php?option=com_jefaqpro&view=exportcsv',
			$vName 				== 'exportcsv'
		);

		if ($vName=='categories') {
			JToolBarHelper::title( JText::sprintf('COM_CATEGORIES_CATEGORIES_TITLE', JText::_('com_jefaqpro')), 'faq-categories');
		}
	}

	/**
	 * Gets a list of the actions that can be performed.
	 */
	public static function getActions($categoryId = 0)
	{
		$user			= JFactory::getUser();
		$result			= new JObject;

		if (empty($categoryId)) {
			$assetName	= 'com_jefaqpro';
		} else {
			$assetName	= 'com_jefaqpro.category.'.(int) $categoryId;
		}

		$actions 		= array ( 'core.admin', 'core.manage', 'core.create',
								  'core.edit', 'core.edit.state', 'core.delete'
								);

		foreach ($actions as $action) {
			$result->set( $action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
