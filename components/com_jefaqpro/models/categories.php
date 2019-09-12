<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Retricted Access');

jimport('joomla.application.component.model');

/**
 * This models supports retrieving lists of JE FAQPro categories.
 */
class jefaqproModelCategories extends JModelLegacy
{
	/**
	 * Model context string.
	 */
	public $_context				= 'com_jefaqpro.categories';

	/**
	 * The category context (allows other extensions to derived from this model).
	 */
	protected $_extension			= 'com_jefaqpro';

	private $_parent				= null;

	private $_items					= null;

	/**
	 * Method to auto-populate the model state.
	 */
	protected function populateState()
	{
		$app						= JFactory::getApplication();
		$this->setState('filter.extension', $this->_extension);

		// Get the parent id if defined.
			$parentId				= JRequest::getInt('id');
			$this->setState('filter.parentId', $parentId);

		$params						= $app->getParams();
		$this->setState('params', $params);

		$this->setState('filter.published',	1);
		$this->setState('filter.access',	true);
	}

	/**
	 * redefine the function an add some properties to make the styling more easy
	 */
	public function getItems()
	{
		if(!count($this->_items))
		{
			$app					= JFactory::getApplication();
			$menu					= $app->getMenu();
			$active					= $menu->getActive();
			$params					= new JRegistry();

			if($active)	{
				$params->loadString($active->params);
			}

			$options				= array();
			$options['countItems']	= $params->get('show_cat_items_cat', 1) || !$params->get('show_empty_categories_cat', 0);

			$categories				= JCategories::getInstance('jefaqpro', $options);
			$this->_parent			= $categories->get($this->getState('filter.parentId', 'root'));

			if(is_object($this->_parent)) {
				$this->_items		= $this->_parent->getChildren();
			} else {
				$this->_items		= false;
			}
		}

		return $this->_items;
	}

	public function getParent()
	{
		if(!is_object($this->_parent)) {
			$this->getItems();
		}
		return $this->_parent;
	}
	
	public function getSettings()
	{
		$id					= 1;
		$settings  			= JTable::getInstance('Settings', 'jefaqproTable');
		$settings->load($id);

		return $settings;
	}
}