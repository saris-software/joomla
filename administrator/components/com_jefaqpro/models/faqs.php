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

jimport('joomla.application.component.modellist');

class jefaqproModelFaqs extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'faq.id',
				'questions', 'faq.questions',
				'answers', 'faq.answers',
				'published', 'faq.published',
				'catid', 'faq.catid', 'category_title',
				'access', 'faq.access', 'access_level',
				'ordering', 'faq.ordering',
				'language', 'faq.language',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to get the maximum ordering value for each category.
	 */
	function &getCategoryOrders()
	{
		if (!isset($this->cache['categoryorders'])) {
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);

			$query->select('MAX(ordering) as `max`, catid');
			$query->select('catid');
			$query->from('#__jefaqpro_faq');
			$query->group('catid');
			$db->setQuery($query);

			$this->cache['categoryorders'] = $db->loadAssocList('catid', 0);
		}

		return $this->cache['categoryorders'];
	}

	/**
	 * Method to auto-populate the model state.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
			$app		= JFactory::getApplication();

		// Adjust the context to support modal layouts.
			if ($layout = JRequest::getVar('layout')) {
				$this->context .= '.'.$layout;
			}

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published		= $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$categoryId		= $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		$access			= $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);

		$language		= $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		// List state information.
			parent::populateState('faq.id', 'desc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 */
	protected function getStoreId($id = '')
{
		// Compile the store id.
			$id	.= ':'.$this->getState('filter.search');
			$id	.= ':'.$this->getState('filter.published');
			$id	.= ':'.$this->getState('filter.category_id');
			$id	.= ':'.$this->getState('filter.access');
			$id	.= ':'.$this->getState('filter.language');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 */
	protected function getListQuery()
	{
		// Create a new query object.
			$db				= $this->getDbo();
			$query			= $db->getQuery(true);

		// Select the required fields from the table.
			$query->select( $this->getState(
				'list.select',
				'faq.id AS id, faq.questions AS questions, faq.answers AS answers,'.
				'faq.catid AS catid,' .
				'faq.published AS published, faq.ordering AS ordering,'.
				'faq.language'
			) );
			$query->from( '#__jefaqpro_faq AS faq' );

		// Join over the categories.
			$query->select( 'cat.title AS category_title' );
			$query->join( 'LEFT', '#__categories AS cat ON cat.id = faq.catid' );

		// Join over the asset groups.
			$query->select( 'ag.title AS access_level' );
			$query->join( 'LEFT', '#__viewlevels AS ag ON ag.id = faq.access' );

		// Join over the language
			$query->select( 'l.title AS language_title' );
			$query->join( 'LEFT', '`#__languages` AS l ON l.lang_code = faq.language' );

		// Filter by published state
			$published		= $this->getState('filter.published');
			if (is_numeric($published)) {
				$query->where('faq.published = ' . (int) $published);
			} else if ($published === '') {
				$query->where('(faq.published = 0 OR faq.published = 1)');
			}

		// Filter by search in title & microblog.
			$search			= $this->getState('filter.search');
			if (!empty($search)) {
				if (stripos($search, 'id:') === 0) {
					$query->where('faq.id = '.(int) substr($search, 3));
				} else {
					//$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
					$search = $db->Quote('%'.$db->escape($search, true).'%');
					$query->where('(faq.questions LIKE '.$search.' OR faq.answers LIKE '.$search.')');
				}
			}

		// Filter by a single or group of categories.
			$categoryId		= $this->getState('filter.category_id');
			if (is_numeric($categoryId)) {
				$query->where('faq.catid = '.(int) $categoryId);
			} else if (is_array($categoryId)) {
				JArrayHelper::toInteger($categoryId);
				$categoryId	= implode(',', $categoryId);
				$query->where('faq.catid IN ('.$categoryId.')');
			}

		// Filter by access level.
			if ($access = $this->getState('filter.access')) {
				$query->where('faq.access = ' . (int) $access);
			}

		// Filter on the language.
			if ($language = $this->getState('filter.language')) {
				$query->where('faq.language = '.$db->quote($language));
			}

		// Add the list ordering clause.
			$orderCol		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');

			if ($orderCol == 'ordering' || $orderCol == 'category_title') {
				$orderCol	= 'category_title '. $orderDirn .', ordering';
			}

			//$query->order($db->getEscaped($orderCol.' '.$orderDirn));
			$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}


	protected function _getList($query, $limitstart=0, $limit=0)
	{
		$ordering = $this->getState('list.ordering', 'ordering');
		if (in_array($ordering, array('pages', 'name'))) {
			$this->_db->setQuery($query);
			$result = $this->_db->loadObjectList();
			$this->translate($result);
			$lang = JFactory::getLanguage();
			JArrayHelper::sortObjects($result, $ordering, $this->getState('list.direction') == 'desc' ? -1 : 1, true, $lang->getLocale());
			$total = count($result);
			$this->cache[$this->getStoreId('getTotal')] = $total;
			if ($total < $limitstart) {
				$limitstart = 0;
				$this->setState('list.start', 0);
			}
			return array_slice($result, $limitstart, $limit ? $limit : null);
		}
		else {
			if ($ordering == 'ordering') {
				$query->order('faq.catid ASC');
				$ordering = 'faq.ordering';
			}

			$query->order($this->_db->quoteName($ordering) . ' ' . $this->getState('list.direction'));
			if ($ordering == 'cat') {
				$query->order('faq.ordering ASC');
			}
			$result = parent::_getList($query, $limitstart, $limit);
			return $result;
		}
	}
}
?>
