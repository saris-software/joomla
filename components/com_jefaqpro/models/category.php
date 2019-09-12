<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Newsfeeds Component Category Model
 */
class jefaqproModelCategory extends JModelList
{
	/**
	 * Category items data
	 */
	protected $_item					= null;
	protected $_children				= null;
	protected $_parent					= null;

	/**
	 * The category that applies.
	 */
	protected $_category				= null;

	/**
	 * The list of other faq categories.
	 */
	protected $_categories				= null;

	/**
	 * Constructor.
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields']	= array(
				'id', 'faq.id',
				'questions', 'faq.questions',
				'ordering', 'faq.ordering',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to get a list of items.
	 */
	public function getItems()
	{
		// Invoke the parent getItems method to get the main list
		$items						= parent::getItems();
		return $items;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 */
	protected function getListQuery()
	{
		$user							= JFactory::getUser();
		$groups							= implode(',', $user->getAuthorisedViewLevels());

		// Create a new query object.
			$db							= $this->getDbo();
			$query						= $db->getQuery(true);

		// Select required fields from the categories.
			$query->select($this->getState('list.select', 'faq.*'));
			$query->from('`#__jefaqpro_faq` AS faq');
			$query->where('faq.access IN ('.$groups.')');

		// Filter by category.
			if ($categoryId = $this->getState('category.id')) {
				$query->where('faq.catid = '.(int) $categoryId);
				$query->join('LEFT', '#__categories AS c ON c.id = faq.catid');
				$query->where('c.access IN ('.$groups.')');
			}

		// Filter by state
			$state = $this->getState('filter.published');
			if (is_numeric($state)) {
				$query->where('faq.published = '.(int) $state);
			}

		// Add the list ordering clause.
			$query->order($db->escape($this->getState('list.ordering', 'faq.ordering')).' '.$db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
			$app						= JFactory::getApplication();
			$params						= JComponentHelper::getParams('com_jefaqpro');
			$settings					= $this->getSettings();

		// List state information
			$limit						= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
			$this->setState('list.limit', $limit);

			$limitstart					= JRequest::getVar('limitstart', 0, '', 'int');
			$this->setState('list.start', $limitstart);

		// order by
			$orderCol					= $settings->orderby;
			if($orderCol==='random')
				$this->setState('list.ordering', 'rand()');
			else
				$this->setState('list.ordering', 'faq.'.$orderCol);

			$listOrder 					= $settings->sortby;
			$this->setState('list.direction', $listOrder);

		$id								= JRequest::getVar('id', 0, '', 'int');
		$this->setState('category.id', $id);

		$user							= JFactory::getUser();
		if ((!$user->authorise('core.edit.state', 'com_jefaqpro')) &&  (!$user->authorise('core.edit', 'com_jefaqpro'))) {
			// limit to published for people who can't edit or edit.state.
				$this->setState('filter.published',	1);
		}

		$this->setState('filter.language',$app->getLanguageFilter());

		// Load the parameters.
			$this->setState('params', $params);
	}

	/**
	 * Method to get category data for the current category
	 */
	public function getCategory()
	{
		if(!is_object($this->_item)) {
			$app						= JFactory::getApplication();
			$menu						= $app->getMenu();
			$active						= $menu->getActive();
			$params						= new JRegistry();

			if(!empty($active))
				$params->loadString($active->params);

			$options					= array();

			$options['countItems']		= $params->get('show_cat_items', 1) || $params->get('show_empty_categories', 0);
			$categories					= JCategories::getInstance('jefaqpro', $options);
			$this->_item				= $categories->get($this->getState('category.id', 'root'));

			if(is_object($this->_item)) {

				$user					= JFactory::getUser();
				$userId					= $user->get('id');
				$asset					= 'com_jefaqpro.category.'.$this->_item->id;

				// Check general create permission.
				if ($user->authorise('core.edit', $asset)) {
					$this->_item->getParams()->set('access-edit', true);
				}

				$this->_children		= $this->_item->getChildren();
				$this->_parent			= false;

				if($this->_item->getParent()) {
					$this->_parent		= $this->_item->getParent();
				}

				$this->_rightsibling	= $this->_item->getSibling();
				$this->_leftsibling		= $this->_item->getSibling(false);
			} else {
				$this->_children		= false;
				$this->_parent			= false;
			}
		}

		return $this->_item;
	}

	/**
	 * Get the parent category.
	 */
	public function getParent()
	{
		if (!is_object($this->_item)) {
			$this->getCategory();
		}

		return $this->_parent;
	}

	/**
	 * Get the sibling (adjacent) categories.
	 */
	function &getLeftSibling()
	{
		if (!is_object($this->_item)) {
			$this->getCategory();
		}

		return $this->_leftsibling;
	}

	function &getRightSibling()
	{
		if(!is_object($this->_item)) {
			$this->getCategory();
		}

		return $this->_rightsibling;
	}

	/**
	 * Get the child categories.
	 */
	function &getChildren()
	{
		if(!is_object($this->_item)) {
			$this->getCategory();
		}

		return $this->_children;
	}

	function getfaqcategories()
	{
		$id								= JRequest::getVar('id');
		$db								= JFactory::getDbo();
		$app							= JFactory::getApplication();
		$user							= JFactory::getUser();
		$extension						= 'com_jefaqpro';
		// Record that this $id has been checked
		$this->_checkedCategories[$id]	= true;

		$query = $db->getQuery(true);

		// right join with c for category
			$query->select('c.*');
			$query->from('#__categories as c');
			$query->where('(c.extension='.$db->Quote($extension).' OR c.extension='.$db->Quote('system').')');
			$query->where('c.published = 1');
			$query->where('c.id = '.$id);

		// Get the results
			$db->setQuery($query);
			$results = $db->loadObject();

		return $results;
	}

	public function getSettings()
	{
		$id					= 1;
		$settings  			= JTable::getInstance('Settings', 'jefaqproTable');
		$settings->load($id);

		return $settings;
	}
}