<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

class jefaqproModelSettings extends JModelAdmin
{
	/**
	 * Returns a reference to the a Table object, always creating it.
	 */
	public function getTable($type = 'Settings', $prefix = 'jefaqproTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the row form.
	 */
	public function getForm($data = array(), $loadData = true)
	{
		jimport('joomla.form.form');

		// Get the form.
			$form					= $this->loadForm('com_jefaqpro.settings', 'settings', array('control' => 'jform', 'load_data' => $loadData));
			if (empty($form)) {
				return false;
			}

		return $form;
	}

	/**
	 * Method to get a single record.
	 */
	public function getItem($pk = null)
	{
		return parent::getItem('1');
	}

	/**
	 * Method to get the data that should be injected in the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
			$data					= JFactory::getApplication()->getUserState('com_jefaqpro.edit.settings.data', array());

		if (empty($data)) {
			$data					= $this->getItem();
		}

		return $data;
	}
}
?>
