<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

class RseventsproModelRseventspro extends JModelLegacy
{	
	protected $_query			= null;
	protected $_locationquery	= null;
	protected $_categoriesquery	= null;
	protected $_subscrquery		= null;
	protected $_searchquery		= null;
	protected $_formsquery		= null;
	
	protected $_db				= null;
	protected $_app				= null;
	protected $_user			= null;
	protected $permissions		= null;
	
	/**
	 *	Main constructor
	 */
	public function __construct() {
		jimport('joomla.html.pagination');
		
		parent::__construct();
		
		$this->_db			= JFactory::getDbo();
		$this->_app			= JFactory::getApplication();
		$this->_user		= JFactory::getUser();
		$this->permissions	= rseventsproHelper::permissions();
		$layout				= $this->_app->input->get('layout','');
		$config				= JFactory::getConfig();
		$this->_operator	= $this->getOperator();
		
		if (in_array($layout, array('','items','default','locations','categories','map'))) {
			if ($category = $this->_app->input->getInt('category',0)) {
				$this->setFilter('categories',$this->getNameType('category',$category));
			}
			
			if ($tag = $this->_app->input->getInt('tag',0)) {
				$this->setFilter('tags',$this->getNameType('tag', $tag));
			}
				
			if ($location = $this->_app->input->getInt('location',0)) {
				$this->setFilter('locations',$this->getNameType('location', $location));
			}
			
			$this->_filters		= $this->getFilters();
			$this->_query		= $this->_buildQuery();
		}
		
		if ($layout == 'locations' || $layout == 'items') {
			$this->_locationquery = $this->_buildLocationQuery();
		}
		
		if ($layout == 'categories' || $layout == 'items') {
			$this->_categoriesquery = $this->_buildCategoriesQuery();
		}
		
		if ($layout == 'subscribers' || $layout == 'items' || $this->_app->input->get('task') == 'exportguests') {
			$this->_subscrquery = $this->_buildSubscribersQuery();
		}
		
		if ($layout == 'rsvp' || $layout == 'items' || $this->_app->input->get('task') == 'exportrsvpguests') {
			$this->_rsvpquery = $this->_buildRSVPQuery();
		}
		
		if ($layout == 'search' || $layout == 'items') {
			$this->_searchquery = $this->_buildSearchQuery();
		}
		
		if ($layout == 'forms') {
			$this->_formsquery = $this->getFormsQuery();
		}
		
		// Get pagination request variables
		$thelimit	= $this->_app->input->get('format','') == 'feed' ? $config->get('feed_limit') : ($this->_app->input->get('type','') == 'ical' ? $config->get('feed_limit') : $config->get('list_limit'));
		$limit		= $this->_app->getUserStateFromRequest('com_rseventspro.limit', 'limit', $thelimit, 'int');
		$limitstart	= $this->_app->input->getInt('limitstart', 0);
		
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('com_rseventspro.limit', $limit);
		$this->setState('com_rseventspro.limitstart', $limitstart);
	}
	
	/**
	 *	Method to build the events query
	 *
	 *	@return SQL query
	 */
	protected function _buildQuery() {
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/query.php';
		
		$params = rseventsproHelper::getParams();
		$query	= RSEventsProQuery::getInstance($params);
		$query->group('e.id');
		
		return $query->toString();
	}
	
	/**
	 *	Method to build the locations query
	 *
	 *	@return SQL query
	 */
	protected function _buildLocationQuery() {
		$query	= $this->_db->getQuery(true);
		$params	= rseventsproHelper::getParams();
		$order	= $params->get('order','ASC');
		
		$query->clear()
			->select($this->_db->qn('l.id'))->select($this->_db->qn('l.name'))->select($this->_db->qn('l.description'))
			->from($this->_db->qn('#__rseventspro_locations','l'))
			->where($this->_db->qn('l.published').' = 1')
			->order($this->_db->qn('l.name').' '.$this->_db->escape($order));
		
		if ($params->get('empty',0)) {
			$query->join('right',$this->_db->qn('#__rseventspro_events','e').' ON '.$this->_db->qn('e.location').' = '.$this->_db->qn('l.id'));
			$query->where($this->_db->qn('e.published').' = 1');
			$query->where($this->_db->qn('e.completed').' = 1');
			$query->group($this->_db->qn('l.id'));
			$query->group($this->_db->qn('l.name'));
			$query->group($this->_db->qn('l.description'));
		}		
		
		return (string) $query;
	}
	
	/**
	 *	Method to build the locations query
	 *
	 *	@return SQL query
	 */
	protected function _buildCategoriesQuery() {
		$query	= $this->_db->getQuery(true);
		$params	= rseventsproHelper::getParams();
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		
		$parent		= (int) $params->get('parent', '0');
		$ordering	= $params->get('ordering','c.title');
		$direction	= $params->get('order','ASC');
		
		$query->clear()
			->select($this->_db->qn('c.id'))->select($this->_db->qn('c.title'))
			->select($this->_db->qn('c.description'))->select($this->_db->qn('c.level'))->select($this->_db->qn('c.params'))
			->from($this->_db->qn('#__categories','c'))
			->where($this->_db->qn('c.extension').' = '.$this->_db->q('com_rseventspro'))
			->where($this->_db->qn('c.published').' = 1')
			->order($this->_db->qn($ordering).' '.$this->_db->escape($direction));
		
		
		if ($parent) {
			$query->from($this->_db->qn('#__categories', 'p'))
				->where($this->_db->qn('p.published').' = 1')
				->where($this->_db->qn('p.parent_id') . '=' . (int) $parent)
				->where($this->_db->qn('c.lft') . '>=' . $this->_db->qn('p.lft'))
				->where($this->_db->qn('c.lft') . '<=' . $this->_db->qn('p.rgt'));
		}
		
		if (JLanguageMultilang::isEnabled()) {
			$query->where($this->_db->qn('c.language').' IN ('.$this->_db->q(JFactory::getLanguage()->getTag()).','.$this->_db->q('*').')');
		}
		
		$query->where('c.access IN ('.$groups.')');
		
		return (string) $query;
	}
	
	/**
	 *	Method to build the subscribers query
	 *
	 *	@return SQL query
	 */
	protected function _buildSubscribersQuery() {
		$query	= $this->_db->getQuery(true);
		$id		= $this->_app->input->getInt('id');
		$ticket = $this->_app->input->get('ticket',$this->_app->getUserState('com_rseventspro.subscriptions.ticket.frontend'));
		$search = $this->_app->input->getString('search',$this->_app->getUserState('com_rseventspro.subscriptions.search_frontend'));
		$state	= $this->_app->input->getString('state',$this->_app->getUserState('com_rseventspro.subscriptions.state.frontend'));
		
		$this->_app->setUserState('com_rseventspro.subscriptions.search_frontend',$search);
		$this->_app->setUserState('com_rseventspro.subscriptions.state.frontend',$state);
		$this->_app->setUserState('com_rseventspro.subscriptions.ticket.frontend',$ticket);
		
		$query->clear()
			->select($this->_db->qn('e.name','event'))->select($this->_db->qn('u.id'))->select($this->_db->qn('u.ide'))
			->select($this->_db->qn('u.idu'))->select($this->_db->qn('u.name'))->select($this->_db->qn('u.email'))
			->select($this->_db->qn('u.date'))->select($this->_db->qn('u.state'))->select($this->_db->qn('u.confirmed'))->select($this->_db->qn('u.ip'))
			->select($this->_db->qn('u.gateway'))->select($this->_db->qn('u.SubmissionId'))->select($this->_db->qn('u.discount'))
			->select($this->_db->qn('u.early_fee'))->select($this->_db->qn('u.late_fee'))->select($this->_db->qn('u.tax'))
			->from($this->_db->qn('#__rseventspro_users','u'))
			->join('left',$this->_db->qn('#__rseventspro_events','e').' ON '.$this->_db->qn('e.id').' = '.$this->_db->qn('u.ide'))
			->where($this->_db->qn('u.ide').' = '.$id);
		
		if ($ticket != '-' && !empty($ticket))
			$query->join('left',$this->_db->qn('#__rseventspro_user_tickets','ut').' ON '.$this->_db->qn('ut.ids').' = '.$this->_db->qn('u.id'));

		if (!empty($search)) {
			$search = $this->_db->Quote('%'.$this->_db->escape($search, true).'%');
			$query->where('('.$this->_db->qn('e.name').' LIKE '.$search.' OR '.$this->_db->qn('u.name').' LIKE '.$search.' OR '.$this->_db->qn('u.email').' LIKE '.$search.')');
		}
		
		if ($state != '-' && !is_null($state))
			$query->where($this->_db->qn('u.state').' = '.(int) $state);
		
		if ($ticket != '-' && !empty($ticket))
			$query->where($this->_db->qn('ut.idt').' = '.(int) $ticket);
		
		$query->order($this->_db->qn('u.date').' DESC');
		
		JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query)));
		
		return (string) $query;
	}
	
	/**
	 *	Method to build the RSVP query
	 *
	 *	@return SQL query
	 */
	protected function _buildRSVPQuery() {
		$query	= $this->_db->getQuery(true);
		$id		= $this->_app->input->getInt('id');	
		$search = $this->_app->input->getString('search',$this->_app->getUserState('com_rseventspro.rsvp.search'));
		$state	= $this->_app->input->getString('state',$this->_app->getUserState('com_rseventspro.rsvp.state'));
		
		$this->_app->setUserState('com_rseventspro.rsvp.search',$search);
		$this->_app->setUserState('com_rseventspro.rsvp.state',$state);
		
		$query->clear()
			->select('r.*')
			->select($this->_db->qn('u.name'))->select($this->_db->qn('u.email'))
			->from($this->_db->qn('#__rseventspro_rsvp_users','r'))
			->join('LEFT', $this->_db->qn('#__users','u').' ON '.$this->_db->qn('r.uid').' = '.$this->_db->qn('u.id'))
			->where($this->_db->qn('r.ide').' = '.$id);
		
		if (!empty($search)) {
			$search = $this->_db->Quote('%'.$this->_db->escape($search, true).'%');
			$query->where('('.$this->_db->qn('u.name').' LIKE '.$search.' OR '.$this->_db->qn('u.username').' LIKE '.$search.')');
		}
		
		if ($state != '-' && !is_null($state))
			$query->where($this->_db->qn('r.rsvp').' = '.$this->_db->q($state));
		
		return (string) $query;
	}
	
	
	/**
	 *	Method to build the search query
	 *
	 *	@return SQL query
	 */
	protected function _buildSearchQuery() {
		$query			= $this->_db->getQuery(true);
		$params			= rseventsproHelper::getParams();
		$enablestart	= $this->_app->input->getInt('enablestart');
		$enableend		= $this->_app->input->getInt('enableend');
		$enableprice	= $this->_app->input->getInt('enableprice');
		$order			= $params->get('ordering','start');
		$direction		= $params->get('order','ASC');
		
		if ($this->_app->input->get('format') != 'raw') {
			$this->_app->setUserState('rsepro.search.estart',$enablestart);
			$this->_app->setUserState('rsepro.search.eend',$enableend);
			$this->_app->setUserState('rsepro.search.eprice',$enableprice);
		}
		
		$categories	= $this->_app->getUserStateFromRequest('rsepro.search.categories', 'rscategories');
		$locations	= $this->_app->getUserStateFromRequest('rsepro.search.locations', 'rslocations');
		$estart		= $this->_app->getUserStateFromRequest('rsepro.search.estart', 'enablestart');
		$eend		= $this->_app->getUserStateFromRequest('rsepro.search.eend', 'enableend');
		$start		= $this->_app->getUserStateFromRequest('rsepro.search.start', 'rsstart');
		$end		= $this->_app->getUserStateFromRequest('rsepro.search.end', 'rsend');
		$archive	= $this->_app->getUserStateFromRequest('rsepro.search.archive', 'rsarchive');
		$price		= $this->_app->getUserStateFromRequest('rsepro.search.price', 'rsprice');
		$eprice		= $this->_app->getUserStateFromRequest('rsepro.search.eprice', 'enableprice');
		$search		= $this->_app->getUserStateFromRequest('rsepro.search.search', 'rskeyword');
		$repeat		= $this->_app->input->getInt('repeat',1);
		$exclude	= rseventsproHelper::excludeEvents();
		$where		= array();
		
		$query->clear()
			->select($this->_db->qn('e.id'))
			->from($this->_db->qn('#__rseventspro_events','e'))
			->join('left',$this->_db->qn('#__rseventspro_locations','l').' ON '.$this->_db->qn('l.id').' = '.$this->_db->qn('e.location'))
			->join('left',$this->_db->qn('#__rseventspro_taxonomy','tx').' ON '.$this->_db->qn('tx.ide').' = '.$this->_db->qn('e.id'))
			->join('left',$this->_db->qn('#__categories','c').' ON '.$this->_db->qn('c.id').' = '.$this->_db->qn('tx.id'))
			->join('left',$this->_db->qn('#__rseventspro_tickets','t').' ON '.$this->_db->qn('t.ide').' = '.$this->_db->qn('e.id'))
			->where($this->_db->qn('e.completed').' = 1')
			->where($this->_db->qn('c.extension').' = '.$this->_db->q('com_rseventspro'))
			->group($this->_db->qn('e.id'));
		
		if (!$repeat) {
			$query->where($this->_db->qn('e.parent').' = 0');
		}
		
		if ($archive) {
			$query->where($this->_db->qn('e.published').' IN (1,2)');
		} else {
			$query->where($this->_db->qn('e.published').' = 1');
		}
		
		if (!empty($categories)) {
			$categories = array_map('intval',$categories);
			$addcategorywhere = true;
			
			if (count($categories) == 1 && $categories[0] == 0) {
				$addcategorywhere = false;
			}
			
			if ($addcategorywhere) {
				$subquery = $this->_db->getQuery(true);
				$subquery->clear()
					->select($this->_db->qn('tx.ide'))
					->from($this->_db->qn('#__rseventspro_taxonomy','tx'))
					->join('left',$this->_db->qn('#__categories','c').' ON '.$this->_db->qn('c.id').' = '.$this->_db->qn('tx.id'))
					->where($this->_db->qn('c.id').' IN ('.implode(',',$categories).')')
					->where($this->_db->qn('tx.type').' = '.$this->_db->q('category'))
					->where($this->_db->qn('c.extension').' = '.$this->_db->q('com_rseventspro'));
				
				if (JLanguageMultilang::isEnabled()) {
					$subquery->where('c.language IN ('.$this->_db->q(JFactory::getLanguage()->getTag()).','.$this->_db->q('*').')');
				}
				
				$user	= JFactory::getUser();
				$groups	= implode(',', $user->getAuthorisedViewLevels());
				$subquery->where('c.access IN ('.$groups.')');
				
				$query->where($this->_db->qn('e.id').' IN ('.$subquery.')');
			}
		}
		
		if (!empty($locations)) {
			$locations = array_map('intval',$locations);
			$addlocationwhere = true;
			
			if (count($locations) == 1 && $locations[0] == 0)
				$addlocationwhere = false;
			
			if ($addlocationwhere)
				$query->where($this->_db->qn('e.location').' IN ('.implode(',',$locations).')');
		}
		
		$isstart	= false;
		$isend		= false;
		
		if ($estart && !empty($start)) {
			if (strlen(trim($start)) <= 10)
				$start .= ' 00:00:00';
			
			$start = JFactory::getDate($start);
			$start = $start->toSql();
			
			$isstart = true;
		}
		
		if ($eend && !empty($end)) {
			if (strlen(trim($end)) <= 10)
				$end .= ' 23:59:59';
			
			$end = JFactory::getDate($end);
			$end = $end->toSql();
			
			$isend = true;
		}
		
		if ($isstart && !$isend) {
			$query->where($this->_db->qn('e.start').' >= '.$this->_db->q($start));
		} else if (!$isstart && $isend) {
			$query->where('(('.$this->_db->qn('e.end').' <> '.$this->_db->q($this->_db->getNullDate()).' AND '.$this->_db->qn('e.end').' <= '.$this->_db->q($end).') OR ('.$this->_db->qn('e.end').' = '.$this->_db->q($this->_db->getNullDate()).' AND '.$this->_db->qn('e.start').' <= '.$this->_db->q($end).'))');
		} else if ($isstart && $isend) {
			// Get regular events between 'start' and 'end' dates
			$q1 = '('.$this->_db->qn('e.end').' <> '.$this->_db->q($this->_db->getNullDate()).' AND '.$this->_db->qn('e.start').' >= '.$this->_db->q($start).' AND '.$this->_db->qn('e.end').' <= '.$this->_db->q($end).')';
			
			// Get regular events that starts between the 'start' and 'end' dates and ends after the 'end' date
			$q2 = '('.$this->_db->qn('e.end').' <> '.$this->_db->q($this->_db->getNullDate()).' AND '.$this->_db->qn('e.start').' >= '.$this->_db->q($start).' AND '.$this->_db->qn('e.start').' <= '.$this->_db->q($end).' AND '.$this->_db->qn('e.end').' >= '.$this->_db->q($end).')';
			
			// Get regular events that start before the 'start' date and ends between the 'start' and 'end' dates
			$q3 = '('.$this->_db->qn('e.end').' <> '.$this->_db->q($this->_db->getNullDate()).' AND '.$this->_db->qn('e.start').' <= '.$this->_db->q($start).' AND '.$this->_db->qn('e.end').' >= '.$this->_db->q($start).' AND '.$this->_db->qn('e.end').' <= '.$this->_db->q($end).')';
			
			// Get regular event that start before the 'start' date and ends after the 'end' date
			$q4 = '('.$this->_db->qn('e.end').' <> '.$this->_db->q($this->_db->getNullDate()).' AND '.$this->_db->qn('e.start').' <= '.$this->_db->q($start).' AND '.$this->_db->qn('e.end').' >= '.$this->_db->q($end).')';
			
			// Get all day events between 'start' and 'end' dates
			$q5 = '('.$this->_db->qn('e.end').' = '.$this->_db->q($this->_db->getNullDate()).' AND '.$this->_db->qn('e.start').' >= '.$this->_db->q($start).' AND '.$this->_db->qn('e.start').' <= '.$this->_db->q($end).')';
			
			$query->where('( '.$q1.' OR  '.$q2.' OR  '.$q3.' OR  '.$q4.' OR '.$q5.' )');
		}
		
		if (!empty($search)) {
			$where	= '';
			$words	= explode(' ', $search);
			$search = $this->_db->quote('%' . $this->_db->escape($search, true) . '%', false);
			$wheres = array();
			
			$wheres1 = array();
			$wheres1[] = $this->_db->qn('e.name').' LIKE ' . $search;
			$wheres1[] = $this->_db->qn('e.description').' LIKE ' . $search;
			$wheres1[] = $this->_db->qn('l.name').' LIKE ' . $search;
			$wheres1[] = $this->_db->qn('l.description').' LIKE ' . $search;
			$wheres1[] = $this->_db->qn('l.address').' LIKE ' . $search;
			$wheres1[] = $this->_db->qn('c.title').' LIKE ' . $search;
			$wheres1[] = $this->_db->qn('c.description').' LIKE ' . $search;
			$wheres[] = implode(' OR ', $wheres1);
			
			if (count($words) > 1) {
				foreach ($words as $word) {
					$word = $this->_db->quote('%' . $this->_db->escape($word, true) . '%', false);
					$wheres2 = array();
					$wheres2[] = $this->_db->qn('e.name').' LIKE ' . $word;
					$wheres2[] = $this->_db->qn('e.description').' LIKE ' . $word;
					$wheres2[] = $this->_db->qn('l.name').' LIKE ' . $word;
					$wheres2[] = $this->_db->qn('l.description').' LIKE ' . $word;
					$wheres2[] = $this->_db->qn('l.address').' LIKE ' . $word;
					$wheres2[] = $this->_db->qn('c.title').' LIKE ' . $word;
					$wheres2[] = $this->_db->qn('c.description').' LIKE ' . $word;
					$wheres[] = implode(' OR ', $wheres2);
				}
			}
			
			$where = '(' . implode(') OR (', $wheres) . ')';
			$query->where('('.$where.')');
		}
		
		if ($eprice) {
			list($min, $max) = explode(',',$price,2);
			
			$query->where($this->_db->qn('t.price').' >= '.$this->_db->q((int) $min));
			$query->where($this->_db->qn('t.price').' <= '.$this->_db->q((int) $max));
		}

		if (!empty($exclude))
			$query->where($this->_db->qn('e.id').' NOT IN ('.implode(',',$exclude).')');
		
		if ($order == 'title' || $order == 'c.title')	$order = 'name';
		if ($order == 'lft' || $order == 'c.lft')		$order = 'start';
		
		if (rseventsproHelper::getConfig('featured','int'))
			$query->order($this->_db->qn('e.featured').' DESC, '.$this->_db->qn('e.'.$order).' '.$this->_db->escape($direction));
		else
			$query->order($this->_db->qn('e.'.$order).' '.$this->_db->escape($direction));
		
		return (string) $query;
	}
	
	/**
	 *	Method to build the RSForm! Pro forms query
	 *
	 *	@return SQL query
	 */
	protected function getFormsQuery() {
		$query	= $this->_db->getQuery(true);
		
		$query->clear()
			->select('DISTINCT '.$this->_db->qn('f.FormId'))->select($this->_db->qn('f.FormName'))
			->from($this->_db->qn('#__rsform_forms','f'))
			->join('left',$this->_db->qn('#__rsform_components','c').' ON '.$this->_db->qn('c.FormId').' = '.$this->_db->qn('f.FormId'))
			->where($this->_db->qn('f.Published').' = 1')
			->where($this->_db->qn('c.Published').' = 1')
			->where($this->_db->qn('c.ComponentTypeId').' IN (30,31)')
			->order($this->_db->qn('f.FormId').' ASC');
		
		return (string) $query;
	}
	
	/**
	 *	Method to get events
	 */
	public function getEvents() {
		$this->_db->setQuery($this->_query, $this->getState('com_rseventspro.limitstart'), $this->getState('com_rseventspro.limit'));
		return $this->_db->loadObjectList();
	}
	
	/**
	 *	Method to get locations
	 */
	public function getLocations() {
		$this->_db->setQuery($this->_locationquery, $this->getState('com_rseventspro.limitstart'), $this->getState('com_rseventspro.limit'));
		return $this->_db->loadObjectList();
	}
	
	/**
	 *	Method to get categories
	 */
	public function getCategories() {		
		$this->_db->setQuery($this->_categoriesquery,$this->getState('com_rseventspro.limitstart'), $this->getState('com_rseventspro.limit'));
		if ($categories = $this->_db->loadObjectList()) {
			foreach ($categories as $i => $category) {
				$categories[$i]->image = '';
				$categories[$i]->color = '';
				
				try {
					$registry = new JRegistry;
					$registry->loadString($category->params);
					$categories[$i]->image = $registry->get('image');
					$categories[$i]->color = $registry->get('color');
					
				} catch (Exception $e) {}
			}
			
			return $categories;
		}
		
		return array();
	}
	
	/**
	 *	Method to get subscribers
	 */
	public function getSubscribers() {
		$this->_db->setQuery($this->_subscrquery,$this->getState('com_rseventspro.limitstart'), $this->getState('com_rseventspro.limit'));
		return $this->_db->loadObjectList();
	}
	
	/**
	 *	Method to get search results
	 */
	public function getResults() {
		$this->_db->setQuery($this->_searchquery,$this->getState('com_rseventspro.limitstart'), $this->getState('com_rseventspro.limit'));
		return $this->_db->loadObjectList();
	}
	
	/**
	 *	Method to get RSForm! Pro forms
	 */
	public function getForms() {
		if (!file_exists(JPATH_SITE.'/components/com_rsform/rsform.php'))
			return array();
		
		$this->_db->setQuery($this->_formsquery,$this->getState('com_rseventspro.limitstart'),$this->getState('com_rseventspro.limit'));
		return $this->_db->loadObjectList();
	}
	
	protected function getCount($query) {
		if ($query instanceof JDatabaseQuery
			&& $query->type == 'select'
			&& $query->group === null
			&& $query->having === null)
		{
			$query = clone $query;
			$query->clear('select')->clear('order')->clear('limit')->select('COUNT(*)');

			$this->_db->setQuery($query);
			return (int) $this->_db->loadResult();
		}

		// Otherwise fall back to inefficient way of counting all results.
		$this->_db->setQuery($query);
		$this->_db->execute();

		return (int) $this->_db->getNumRows();
	}
	
	/**
	 *	Method to get the total number of events
	 */
	public function getTotal() {
		return $this->getCount($this->_query);
	}
	
	/**
	 *	Method to get the total number of locations
	 */
	public function getTotalLocations() {
		return $this->getCount($this->_locationquery);
	}
	
	/**
	 *	Method to get the total number of categories
	 */
	public function getTotalCategories() {
		return $this->getCount($this->_categoriesquery);
	}
	
	/**
	 *	Method to get the total number of categories
	 */
	public function getTotalSubscribers() {
		return $this->getCount($this->_subscrquery);
	}
	
	/**
	 *	Method to get the total number of search results
	 */
	public function getTotalResults() {
		return $this->getCount($this->_searchquery);
	}
	
	/**
	 *	Method to get the total number of forms
	 */
	public function getFormsTotal() {
		if (!file_exists(JPATH_SITE.'/components/com_rsform/rsform.php')) 
			return 1;
		
		return $this->getCount($this->_formsquery);
	}
	
	/**
	 *	Method to get forms pagination
	 */
	public function getFormsPagination() {
		return new JPagination($this->getFormsTotal(), $this->getState('com_rseventspro.limitstart'), $this->getState('com_rseventspro.limit'));
	}
	
	public function getUser() {
		if ($this->_user->get('id') > 0) {
			return $this->_user->get('id');
		} else {
			return JFactory::getSession()->getId();
		}
	}
	
	// Get current subscriber details
	public function getSubscriber() {
		$id		= $this->_app->input->getInt('id',0);
		$ide	= $this->_app->input->getInt('ide',0);
		$query	= $this->_db->getQuery(true);
		
		// Get subscriber details
		$query->clear()
			->select('*')
			->from($this->_db->qn('#__rseventspro_users'))
			->where($this->_db->qn('id').' = '.$id);
		
		$this->_db->setQuery($query);
		$subscription = $this->_db->loadObject();
		
		// Get user tickets
		$query->clear()
			->select($this->_db->qn('ut.quantity'))->select($this->_db->qn('t').'.*')
			->from($this->_db->qn('#__rseventspro_user_tickets','ut'))
			->join('left',$this->_db->qn('#__rseventspro_tickets','t').' ON '.$this->_db->qn('t.id').' = '.$this->_db->qn('ut.idt'))
			->where($this->_db->qn('ut.ids').' = '.$id);
		
		$this->_db->setQuery($query);
		$tickets = $this->_db->loadObjectList();
		
		// Get event details
		$idevent = !empty($subscription->ide) ? $subscription->ide : $ide;
		$query->clear()
			->select($this->_db->qn('id'))->select($this->_db->qn('name'))->select($this->_db->qn('owner'))
			->select($this->_db->qn('ticketsconfig'))
			->from($this->_db->qn('#__rseventspro_events'))
			->where($this->_db->qn('id').' = '.(int) $idevent);
		
		$this->_db->setQuery($query);
		$event = $this->_db->loadObject();
		
		return array('data' => $subscription, 'tickets' => $tickets, 'event' => $event);
	}
	
	// Get payment info
	public function getPayment() {
		$id		= $this->_app->input->getInt('pid',0);
		$query	= $this->_db->getQuery(true);
		
		$query->clear()
			->select($this->_db->qn('id'))->select($this->_db->qn('name'))
			->select($this->_db->qn('details'))->select($this->_db->qn('redirect'))
			->from($this->_db->qn('#__rseventspro_payments'))
			->where($this->_db->qn('id').' = '.$id);
		
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}
	
	// Check if the user is subscribed to this event
	public function getIsSubscribed() {
		$id = $this->_app->input->getInt('id');
		$query	= $this->_db->getQuery(true);
		
		$query->clear()
			->select('COUNT('.$this->_db->qn('u.id').')')
			->from($this->_db->qn('#__rseventspro_users','u'))
			->where($this->_db->qn('u.ide').' = '.$id)
			->where($this->_db->qn('u.idu').' = '.$this->_user->get('id'));
		
		JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query)));
		
		$this->_db->setQuery($query);
		$issubscribed = $this->_db->loadResult();
		
		if ($this->_user->get('id') > 0 && !empty($this->permissions['can_unsubscribe'])) {
			return $issubscribed;
		} else return 0;
	}
	
	// Get user subscriptions
	public function getUserSubscriptions() {
		$id = $this->_app->input->getInt('id');
		$query	= $this->_db->getQuery(true);
		
		$query->clear()
			->select($this->_db->qn('u.id'))->select($this->_db->qn('u.name'))
			->select($this->_db->qn('u.date'))->select($this->_db->qn('u.state'))
			->from($this->_db->qn('#__rseventspro_users','u'))
			->where($this->_db->qn('u.ide').' = '.$id)
			->where($this->_db->qn('u.idu').' = '.$this->_user->get('id'));
		
		JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query)));
		
		$this->_db->setQuery($query);
		$subscriptions = $this->_db->loadObjectList();
		
		if ($this->_user->get('id') > 0 && !empty($this->permissions['can_unsubscribe']))
			return $subscriptions;
		
		return false;
	}
	
	// Get user subscriptions
	public function getSubscriptions() {
		$query		= $this->_db->getQuery(true);
		$params		= rseventsproHelper::getParams();
		$past		= (int) $params->get('past',1);
		$archived	= (int) $params->get('archived',1);
		$code		= JFactory::getApplication()->input->getString('code');
		$showform	= $this->getShowForm();
		
		$subscriptions = array();
		
		$query->clear()
			->select($this->_db->qn('u.state'))->select($this->_db->qn('u.URL'))->select($this->_db->qn('u.date','subscribe_date'))->select($this->_db->qn('u.id','ids'))
			->select($this->_db->qn('u.name','iname'))->select($this->_db->qn('u.SubmissionId'))->select($this->_db->qn('e.id'))->select($this->_db->qn('e.name'))
			->select($this->_db->qn('e.start'))->select($this->_db->qn('e.end'))
			->from($this->_db->qn('#__rseventspro_users','u'))
			->join('left',$this->_db->qn('#__rseventspro_events','e').' ON '.$this->_db->qn('e.id').' = '.$this->_db->qn('u.ide'))
			->where($this->_db->qn('e.completed').' = 1');
		
		if (!$showform && $code) {
			$email = $this->getEmailFromCode();
			$query->where($this->_db->qn('u.email').' = '.$this->_db->q($email));
		} else {
			$query->where($this->_db->qn('u.email').' = '.$this->_db->q($this->_user->get('email')));
		}
		
		if (!$archived) {
			$query->where($this->_db->qn('e.published').' = 1');
		}
		
		if (!$past) {
			$query->where($this->_db->qn('e.end').' > '.$this->_db->q(JFactory::getDate()->toSql()));
		}
		
		$this->_db->setQuery($query);
		if ($subscriptions = $this->_db->loadObjectList()) {
			foreach ($subscriptions as $i => &$subscription) {
				$subscription->URL = base64_decode($subscription->URL);
				$subscription->tickets = array();
				
				$query->clear()
					->select($this->_db->qn('t.id'))->select($this->_db->qn('t.name'))
					->select($this->_db->qn('t.layout'))->select($this->_db->qn('ut.quantity'))
					->from($this->_db->qn('#__rseventspro_tickets','t'))
					->join('LEFT', $this->_db->qn('#__rseventspro_user_tickets','ut').' ON '.$this->_db->qn('t.id').' = '.$this->_db->qn('ut.idt'))
					->where($this->_db->qn('ut.ids').' = '.$this->_db->q($subscription->ids));
				$this->_db->setQuery($query);
				if ($tickets = $this->_db->loadObjectList()) {
					foreach ($tickets as $ticket) {
						$subscription->tickets[$ticket->id] = (object) array('id' => $ticket->id, 'ide' => $subscription->id, 'quantity' => $ticket->quantity, 'name' => $ticket->name, 'layout' => !empty($ticket->layout));
					}
				}
			}
		}
		
		return $subscriptions;
	}
	
	// Get a list of tickets that belong to a specific event
	public function getTicketsFromEvent() {
		$id		= $this->_app->input->getInt('id');
		$query	= $this->_db->getQuery(true);
		$return = array();
		$return[] = JHTML::_('select.option', '-', '-= '.JText::_('COM_RSEVENTSPRO_GLOBAL_SELECT_TICKET').' =-');
		
		if (!empty($id)) {
			$query->clear()
				->select($this->_db->qn('id'))->select($this->_db->qn('name'))->select($this->_db->qn('price'))
				->from($this->_db->qn('#__rseventspro_tickets'))
				->where($this->_db->qn('ide').' = '.$id)
				->order($this->_db->qn('order').' ASC');
			
			$this->_db->setQuery($query);
			$tickets = $this->_db->loadObjectList();
			
			if (!empty($tickets)) {
				foreach ($tickets as $ticket) {
					if ($ticket->price > 0) {
						$return[] = JHTML::_('select.option', $ticket->id, $ticket->name . ' (' . rseventsproHelper::currency($ticket->price).')');
					} else {
						$return[] = JHTML::_('select.option', $ticket->id, $ticket->name . ' (' .JText::_('COM_RSEVENTSPRO_GLOBAL_FREE').')');
					}
				}
			}
		}
		
		return $return;
	}
	
	// Check if the current user can subscribe
	public function getCanSubscribe() {
		$id = $this->_app->input->getInt('id',0);
		return rseventsproHelper::getCanSubscribe($id);
	}
	
	// Get event tickets
	public function getTickets() {
		$id			= $this->_app->input->getInt('id');
		return rseventsproHelper::getTickets($id, true);
	}
	
	// Get event tickets
	public function getEventTickets() {
		$return   = array();
		$tickets  = $this->getTickets();
		
		if (!empty($tickets)) {
			foreach ($tickets as $ticket) {				
				$checkticket = rseventsproHelper::checkticket($ticket->id);
				if ($checkticket == -1) 
					continue;
				
				$price = $ticket->price > 0 ? ' - '.rseventsproHelper::currency($ticket->price) : ' - '.JText::_('COM_RSEVENTSPRO_GLOBAL_FREE');
				$return[] = JHTML::_('select.option', $ticket->id, $ticket->name.$price);
			}
		}
		
		return $return;
	}
	
	function getTicketPayment() {
		$tickets	= $this->getTickets();
		$return		= false;
		
		if (!empty($tickets)) {
			foreach ($tickets as $ticket) {
				if ($ticket->price > 0) 
					$return = true;
			}
		}
		
		return $return;
	}
	
	// Get registered users
	public function getPeople() {
		$id		= $this->_app->input->getInt('id');
		$query	= $this->_db->getQuery(true);
		$return = array();
		
		$query->clear()
			->select($this->_db->qn('u.id'))->select($this->_db->qn('u.name'))->select($this->_db->qn('u.email'))
			->from($this->_db->qn('#__rseventspro_users','u'))
			->where($this->_db->qn('u.ide').' = '.$id)
			->where($this->_db->qn('u.state').' = 0');
		
		$this->_app->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query)));
		
		$this->_db->setQuery($query);
		$pending = $this->_db->loadObjectList();
		
		$query->clear()
			->select($this->_db->qn('u.id'))->select($this->_db->qn('u.name'))->select($this->_db->qn('u.email'))
			->from($this->_db->qn('#__rseventspro_users','u'))
			->where($this->_db->qn('u.ide').' = '.$id)
			->where($this->_db->qn('u.state').' = 1');
		
		$this->_app->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query)));
		
		$this->_db->setQuery($query);
		$accepted = $this->_db->loadObjectList();
		
		$query->clear()
			->select($this->_db->qn('u.id'))->select($this->_db->qn('u.name'))->select($this->_db->qn('u.email'))
			->from($this->_db->qn('#__rseventspro_users','u'))
			->where($this->_db->qn('u.ide').' = '.$id)
			->where($this->_db->qn('u.state').' = 2');
			
		$this->_db->setQuery($query);
		$denied = $this->_db->loadObjectList();
		
		if (!empty($pending)) {
			$pendingobjstart = new stdClass();
			$pendingobjstart->value = '<OPTGROUP>';
			$pendingobjstart->text = JText::_('COM_RSEVENTSPRO_SEND_MESSAGE_PENDING');
			$return[] = $pendingobjstart;
			
			foreach ($pending as $subscriber)
				$return[] = JHTML::_('select.option' , $subscriber->id, $subscriber->name . ' (' .$subscriber->email.')');
			
			$pendingobjend = new stdClass();
			$pendingobjend->value = '</OPTGROUP>';
			$pendingobjend->text = JText::_('COM_RSEVENTSPRO_SEND_MESSAGE_PENDING');
			$return[] = $pendingobjend;
		}
		
		if (!empty($accepted)) {
			$acceptedobjstart = new stdClass();
			$acceptedobjstart->value = '<OPTGROUP>';
			$acceptedobjstart->text = JText::_('COM_RSEVENTSPRO_SEND_MESSAGE_ACCEPTED');
			$return[] = $acceptedobjstart;
			
			foreach ($accepted as $subscriber)
				$return[] = JHTML::_('select.option' , $subscriber->id, $subscriber->name . ' (' .$subscriber->email.')');
			
			$acceptedobjend = new stdClass();
			$acceptedobjend->value = '</OPTGROUP>';
			$acceptedobjend->text = JText::_('COM_RSEVENTSPRO_SEND_MESSAGE_ACCEPTED');
			$return[] = $acceptedobjend;
		}
		
		if (!empty($denied)) {
			$deniedobjstart = new stdClass();
			$deniedobjstart->value = '<OPTGROUP>';
			$deniedobjstart->text = JText::_('COM_RSEVENTSPRO_SEND_MESSAGE_DENIED');
			$return[] = $deniedobjstart;
			
			foreach ($denied as $subscriber)
				$return[] = JHTML::_('select.option' , $subscriber->id, $subscriber->name . ' (' .$subscriber->email.')');
			
			$deniedobjend = new stdClass();
			$deniedobjend->value = '</OPTGROUP>';
			$deniedobjend->text = JText::_('COM_RSEVENTSPRO_SEND_MESSAGE_DENIED');
			$return[] = $deniedobjend;
		}
		
		$query->clear()
			->select($this->_db->qn('r.id'))->select($this->_db->qn('r.rsvp'))
			->select($this->_db->qn('u.name'))->select($this->_db->qn('u.email'))
			->from($this->_db->qn('#__rseventspro_rsvp_users','r'))
			->join('LEFT',$this->_db->qn('#__users','u').' ON '.$this->_db->qn('r.uid').' = '.$this->_db->qn('u.id'))
			->where($this->_db->qn('r.ide').' = '.$id);
			
		$this->_db->setQuery($query);
		if ($rsvps = $this->_db->loadObjectList()) {
			$going = $interested = $notgoing = array();
			
			foreach ($rsvps as $rsvp) {
				if ($rsvp->rsvp == 'going') $going[] = $rsvp;
				if ($rsvp->rsvp == 'interested') $interested[] = $rsvp;
				if ($rsvp->rsvp == 'notgoing') $notgoing[] = $rsvp;
			}
			
			if ($going) {
				$goingobj = new stdClass();
				$goingobj->value = '<OPTGROUP>';
				$goingobj->text = JText::_('COM_RSEVENTSPRO_RSVP_GOING');
				$return[] = $goingobj;
				
				foreach ($going as $subscriber)
					$return[] = JHTML::_('select.option' , 'rsvp'.$subscriber->id, $subscriber->name . ' (' .$subscriber->email.')');
				
				$goingobj = new stdClass();
				$goingobj->value = '</OPTGROUP>';
				$goingobj->text = JText::_('COM_RSEVENTSPRO_RSVP_GOING');
				$return[] = $goingobj;
			}
			
			if ($interested) {
				$interestedobj = new stdClass();
				$interestedobj->value = '<OPTGROUP>';
				$interestedobj->text = JText::_('COM_RSEVENTSPRO_RSVP_INTERESTED');
				$return[] = $interestedobj;
				
				foreach ($interested as $subscriber)
					$return[] = JHTML::_('select.option' , 'rsvp'.$subscriber->id, $subscriber->name . ' (' .$subscriber->email.')');
				
				$interestedobj = new stdClass();
				$interestedobj->value = '</OPTGROUP>';
				$interestedobj->text = JText::_('COM_RSEVENTSPRO_RSVP_INTERESTED');
				$return[] = $interestedobj;
			}
			
			if ($notgoing) {
				$notgoingobj = new stdClass();
				$notgoingobj->value = '<OPTGROUP>';
				$notgoingobj->text = JText::_('COM_RSEVENTSPRO_RSVP_NOT_GOING');
				$return[] = $notgoingobj;
				
				foreach ($notgoing as $subscriber)
					$return[] = JHTML::_('select.option' , 'rsvp'.$subscriber->id, $subscriber->name . ' (' .$subscriber->email.')');
				
				$notgoingobj = new stdClass();
				$notgoingobj->value = '</OPTGROUP>';
				$notgoingobj->text = JText::_('COM_RSEVENTSPRO_RSVP_NOT_GOING');
				$return[] = $notgoingobj;
			}
		}
		
		return $return;
	}
	
	// Get events map
	public function getEventsMap() {
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/query.php';
		
		$return	= array();
		$params = rseventsproHelper::getParams();
		$query	= RSEventsProQuery::getInstance($params);
		$select = array('e.id', 'e.name', 'e.start', 'e.owner', 'e.end', 'e.allday', 'l.id' => 'lid', 'l.name' => 'lname', 'l.address', 'l.coordinates', 'l.marker');
		$group	= array('e.id', 'e.name', 'e.start', 'e.owner', 'e.end', 'e.allday', 'l.id', 'l.name', 'l.address', 'l.coordinates', 'l.marker');
		$query->select($select);
		$query->group($group);
		$query->featured(false);
		$query->userevents(false);
		
		$query = $query->toString();
		$this->_db->setQuery($query);
		$events = $this->_db->loadObjectList();
		
		if (!empty($events)) {
			foreach ($events as $event) {
				if (!rseventsproHelper::canview($event->id) && $event->owner != $this->_user->get('id')) 
					continue;
				
				$return[$event->lid][] = $event;
			}
		}
		
		return $return;
	}
	
	// Get location details
	public function getLocation() {
		$id = $this->_app->input->getInt('id');
		$row = JTable::getInstance('Location','rseventsproTable');
		$row->load($id);
		
		try {
			$registry = new JRegistry();
			$registry->loadString($row->gallery_tags);
			$row->gallery_tags = $registry->toArray();
		} catch (Exception $e) {
			$row->gallery_tags = array();
		}
		
		return $row;
	}
	
	// Get event details
	public function getEvent() {
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/events.php';
		
		$id		= $this->_app->input->getInt('id');
		$jform	= $this->_app->input->get('jform',array(),'array');
		$task	= $this->_app->input->get('task');
		$query	= $this->_db->getQuery(true);
		$tasks	= array('approve','pending','denied','removesubscriber','savesubscriber', 'removersvp');
		
		if (in_array($task,$tasks)) {
			$id = $this->_app->input->getInt('ide',0);
		} elseif ($task == 'message') {
			$id = (int) $jform['id'];
		} elseif ($task == 'saveticket' || $task == 'savecoupon') {
			$id = (int) $jform['ide'];
		} elseif ($task == 'savesubscriber') {
			$id = $this->_app->input->getInt('ide',0);
		}
		
		$event = RSEvent::getInstance($id);
		return $event->getEvent();
	}
	
	// Get owner
	public function getOwner() {
		$jinput = $this->_app->input;
		$query	= $this->_db->getQuery(true);
		$id		= $jinput->getInt('id');
		
		if (empty($id)) {
			$event = $jinput->get('jform',array(),'array');
			$id = isset($event['id']) ? (int) $event['id'] : 0;
		}
		
		// Get id from file
		if ($jinput->get('from') == 'file') {
			$file = $jinput->getInt('id');
			
			$query->clear()
				->select($this->_db->qn('ide'))
				->from($this->_db->qn('#__rseventspro_files'))
				->where($this->_db->qn('id').' = '.$file);
			
			$this->_db->setQuery($query);
			$id = (int) $this->_db->loadResult();
		}
		
		// Get id from ticket
		if ($jinput->get('from') == 'ticket') {
			$ticket = $jinput->getInt('id');
			
			$query->clear()
				->select($this->_db->qn('ide'))
				->from($this->_db->qn('#__rseventspro_tickets'))
				->where($this->_db->qn('id').' = '.$ticket);
			
			$this->_db->setQuery($query);
			$id = (int) $this->_db->loadResult();
		}
		
		// Get id from coupon
		if ($jinput->get('from') == 'coupon') {
			$coupon = $jinput->getInt('id');
			
			$query->clear()
				->select($this->_db->qn('ide'))
				->from($this->_db->qn('#__rseventspro_coupons'))
				->where($this->_db->qn('id').' = '.$coupon);
			
			$this->_db->setQuery($query);
			$id = (int) $this->_db->loadResult();
		}
		
		$query->clear();
		if ($this->_user->get('guest')) {
			$query->select($this->_db->qn('sid'));
		} else {
			$query->select($this->_db->qn('owner'));
		}
		$query->from($this->_db->qn('#__rseventspro_events'))
			->where($this->_db->qn('id').' = '.(int) $id);
		
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}
	
	// Get RSForm!Pro data
	public function getFields() {
		$id = $this->_app->input->getInt('id');
		return rseventsproHelper::getRSFormData($id);
	}
	
	// Remove event
	public function remove() {
		$id = $this->_app->input->getInt('id');
		rseventsproHelper::remove($id);
		return true;
	}
	
	// Get filters
	public function getFilters($fromrequest = false) {
		$itemid 	= $this->_app->input->getInt('Itemid');
		$parent		= $this->_app->input->getInt('parent');
		
		if ($fromrequest) {
			$columns 	= $this->_app->input->get('filter_from', 		array(), 'array');
			$operators 	= $this->_app->input->get('filter_condition',	array(), 'array');
			$values 	= $this->_app->input->get('search',				array(), 'array');
		} else {
			$columns 	= $this->_app->getUserStateFromRequest('com_rseventspro.events.filter_columns'.$itemid.$parent, 	'filter_from',		array(), 'array');
			$operators 	= $this->_app->getUserStateFromRequest('com_rseventspro.events.filter_operators'.$itemid.$parent,	'filter_condition',	array(), 'array');
			$values 	= $this->_app->getUserStateFromRequest('com_rseventspro.events.filter_values'.$itemid.$parent,		'search',			array(), 'array');
		}
		
		if ($columns && $columns[0] == '')
			$columns = $operators = $values = array();
		
		if (!empty($values)) {
			$filter = JFilterInput::getInstance();
			foreach ($values as $i => $value) {
				if (empty($value)) {
					if (isset($columns[$i])) unset($columns[$i]);
					if (isset($operators[$i])) unset($operators[$i]);
					if (isset($values[$i])) unset($values[$i]);
				}
				
				$values[$i] = $filter->clean($value,'string');
			}
		}
		
		return array(array_merge($columns), array_merge($operators), array_merge($values));
	}
	
	// Get extra filters
	public function getExtraFilters() {
		$itemid 	= $this->_app->input->getInt('Itemid');
		$parent		= $this->_app->input->getInt('parent');
		
		$status		= $this->_app->getUserStateFromRequest('com_rseventspro.events.filter_status'.$itemid.$parent,		'filter_status',	array(), 'array');
		$featured	= $this->_app->getUserStateFromRequest('com_rseventspro.events.filter_featured'.$itemid.$parent,	'filter_featured',	array(), 'array');
		$childs		= $this->_app->getUserStateFromRequest('com_rseventspro.events.filter_child'.$itemid.$parent, 		'filter_child',		array(), 'array');
		$start		= $this->_app->getUserStateFromRequest('com_rseventspro.events.filter_start'.$itemid.$parent, 		'filter_start',		array(), 'array');
		$end		= $this->_app->getUserStateFromRequest('com_rseventspro.events.filter_end'.$itemid.$parent, 		'filter_end',		array(), 'array');
		$price		= $this->_app->getUserStateFromRequest('com_rseventspro.events.filter_price'.$itemid.$parent, 		'filter_price',		array(), 'array');
		
		$status		= isset($status[0])		? ($status[0] 	== '' ? null : $status) : null;
		$featured	= isset($featured[0])	? ($featured[0] == '' ? null : $featured[0]) : null;
		$childs		= isset($childs[0])		? ($childs[0] 	== '' ? null : $childs[0]) : null;
		$start		= isset($start[0])		? ($start[0] 	== '' ? null : $start[0]) : null;
		$end		= isset($end[0])		? ($end[0] 		== '' ? null : $end[0]) : null;
		$price		= isset($price[0])		? ($price[0] 	== '' ? null : $price[0]) : null;
		
		if (is_array($status)) {
			$status = array_unique($status);
			
			foreach ($status as $key => $option) {
				if ($option == '') unset($status[$key]);
			}	
		}
		
		return array('status' => $status, 'featured' => $featured, 'childs' => $childs, 'start' => $start, 'end' => $end, 'price' => $price);
	}
	
	public function getConditions() {
		$filters	= $this->getFilters();
		$other		= $this->getExtraFilters();
		$columns	= isset($filters[0]) ? $filters[0] : array();
		$count		= 0;
		
		foreach($columns as $column) {
			if ($column == '') continue;
			$count++;
		}
		
		if (!is_null($other['status'])) {
			foreach ($other['status'] as $status) {
				if ($status == '') continue;
				$count++;
			}
		}
			
		if (!is_null($other['featured'])) {
			$count++;
		}
		
		if (!is_null($other['childs'])) {
			$count++;
		}
		
		if (!is_null($other['start'])) {
			$count++;
		}
		
		if (!is_null($other['end'])) {
			$count++;
		}
		
		if (!is_null($other['price'])) {
			$count++;
		}
		
		return $count;
	}
	
	public function getOperator() {
		$itemid 	= $this->_app->input->getInt('Itemid');
		$parent		= $this->_app->input->getInt('parent');
		$valid		= array('AND', 'OR');
		$operator	= $this->_app->getUserStateFromRequest('com_rseventspro.events.filter_operator'.$itemid.$parent, 'filter_operator', 'AND');
		
		return !in_array($operator, $valid) ? 'AND' : $operator;		
	}
	
	// Set filter
	public function setFilter($type,$value) {
		$itemid 	= $this->_app->input->getInt('Itemid');
		$parent		= $this->_app->input->getInt('parent');
		
		$this->_app->setUserState('com_rseventspro.events.filter_columns'.$itemid.$parent,array($type));
		$this->_app->setUserState('com_rseventspro.events.filter_operators'.$itemid.$parent,array('is'));
		$this->_app->setUserState('com_rseventspro.events.filter_values'.$itemid.$parent,array($value));
		
		return true;
	}
	
	// Get name of category, tag or location
	protected function getNameType($type, $value) {
		$query	= $this->_db->getQuery(true);
		
		if ($type == 'category') {
			$query->clear()
				->select($this->_db->qn('title'))
				->from($this->_db->qn('#__categories'))
				->where($this->_db->qn('extension').' = '.$this->_db->q('com_rseventspro'))
				->where($this->_db->qn('id').' = '.(int) $value);
			
			$this->_db->setQuery($query);
			return $this->_db->loadResult();
		} else if ($type == 'location') {
			$query->clear()
				->select($this->_db->qn('name'))
				->from($this->_db->qn('#__rseventspro_locations'))
				->where($this->_db->qn('id').' = '.(int) $value);
			
			$this->_db->setQuery($query);
			return $this->_db->loadResult();
		} else if ($type == 'tag') {
			$query->clear()
				->select($this->_db->qn('name'))
				->from($this->_db->qn('#__rseventspro_tags'))
				->where($this->_db->qn('id').' = '.(int) $value);
			
			$this->_db->setQuery($query);
			return $this->_db->loadResult();
		} else return '';
	}
	
	// Get category details
	public function getEventCategory() {
		$doc		= JFactory::getDocument();
		$query		= $this->_db->getQuery(true);
		$config		= JFactory::getConfig();
		$category	= 0;
		$count		= 0;
		
		list($columns, $operators, $values) = $this->_filters;
		
		for ($i=0; $i<count($columns); $i++) {
			$column 	= $columns[$i];
			$operator	= $operators[$i];
			$value 		= $values[$i];
			
			if ($column == 'categories') {
				if ($operator == 'is') {
					$query->clear()
						->select($this->_db->qn('id'))
						->from($this->_db->qn('#__categories'))
						->where($this->_db->qn('extension').' = '.$this->_db->q('com_rseventspro'))
						->where($this->_db->qn('title').' = '.$this->_db->q($value));
					
					$this->_db->setQuery($query);
					$category = (int) $this->_db->loadResult();
				}
				$count++;
			}
		}
		
		// Search the category within the params
		if (empty($count) && empty($category)) {
			$params 	= rseventsproHelper::getParams();
			if ($pcategories = $params->get('categories','')) {
				foreach ($pcategories as $cat) {
					$category = (int) $cat;
					$count++;
				}
			}
		}
		
		// Get Category details
		if ($count == 1 && $category > 0) {
			jimport('joomla.application.categories');
			$categories = JCategories::getInstance('Rseventspro');
			$item = $categories->get($category);
			
			// Check whether category access level allows access.
			$user	= JFactory::getUser();
			$groups	= $user->getAuthorisedViewLevels();
			if (!is_null($item) && !in_array($item->access, $groups)) {
				throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			}
			
			if ($item) {
				// Set Meta Description
				if ($item->metadesc) {
					$doc->setDescription($item->metadesc);
				}
				
				// Set Meta Keywords
				if ($item->metakey) {
					$doc->setMetadata('keywords', $item->metakey);
				}
				
				// Set Author
				if ($config->get('MetaAuthor') == '1') {
					$doc->setMetaData('author', $item->getMetadata()->get('author'));
				}
				
				// Set Robots
				$robots = $item->getMetadata()->get('robots');
				if ($robots) {
					$doc->setMetadata('robots', $robots);
				}
			}
			
			return $item;
		}
		
		return false;
	}
	
	// Export subscribers
	public function exportguests() {
		$query = $this->_subscrquery;
		rseventsproHelper::exportSubscribersCSV($query);
	}
	
	// Change subscriber status
	public function status($pk, $value) {
		$query = $this->_db->getQuery(true);
		
		$query->clear()
			->select($this->_db->qn('state'))
			->from($this->_db->qn('#__rseventspro_users'))
			->where($this->_db->qn('id').' = '.$pk);
		
		$this->_db->setQuery($query);
		$oldstate = $this->_db->loadResult();
		
		$query->clear()
			->update($this->_db->qn('#__rseventspro_users'))
			->set($this->_db->qn('state').' = '.(int) $value)
			->where($this->_db->qn('id').' = '.$pk);
		
		$this->_db->setQuery($query);
		$this->_db->execute();
		
		// Send activation email
		if ($oldstate != 1 && $value == 1) {
			rseventsproHelper::confirm($pk);
		}
		
		// Send denied email
		if ($oldstate != 2 && $value == 2) {
			rseventsproHelper::denied($pk);
		}
		
		return true;
	}
	
	// Save subscriber
	public function savesubscriber() {
		$table	= JTable::getInstance('Subscription','RseventsproTable');
		$data	= $this->_app->input->get('jform',array(),'array');
		$query	= $this->_db->getQuery(true);
		
		// Verify user
		if ($this->_app->input->getInt('isuser',0)) {
			$email = $this->getEmailFromCode();
			$clone = clone($table);
			$clone->load($data['id']);
			
			// We have a code in the URL
			if ($email) {
				if ($clone->email != $email) {
					$this->setError(JText::_('COM_RSEVENTSPRO_ERROR_SUBSCRIBER_SAVE'));
					return false;
				}
			} else {
				if ($clone->idu != JFactory::getUser()->get('id')) {
					$this->setError(JText::_('COM_RSEVENTSPRO_ERROR_SUBSCRIBER_SAVE'));
					return false;
				}
			}
			
			if (isset($data['state'])) {
				unset($data['state']);
			}
		}
		
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}
		
		// Get old state
		$query->clear()
			->select($this->_db->qn('state'))
			->from($this->_db->qn('#__rseventspro_users'))
			->where($this->_db->qn('id').' = '.$table->id);
		
		$this->_db->setQuery($query);
		$state = $this->_db->loadResult();
		
		JFactory::getApplication()->triggerEvent('rsepro_adminBeforeStoreSubscription', array(array('table' => $table)));
		
		if ($table->store()) {
			// Send activation email
			if (isset($data['state'])) {
				if ($state != 1 && $data['state'] == 1) {
					rseventsproHelper::confirm($table->id);
				}
				
				// Send denied email
				if ($state != 2 && $data['state'] == 2) {
					rseventsproHelper::denied($table->id);
				}
			}
			
			return true;
		} else {
			$this->setError($table->getError());
			return false;
		}
	}
	
	// Remove subscriber
	public function removesubscriber() {
		$table	= JTable::getInstance('Subscription','RseventsproTable');
		$ids	= $this->_app->input->getInt('id');
		$ide	= $this->_app->input->getInt('ide');
		
		$unsubscribe = false;
		$this->_app->triggerEvent('rsepro_unsubscribeUser', array(array('ids' => $ids, 'ide' => $ide, 'unsubscribe' => &$unsubscribe)));
		
		if (!$unsubscribe) {
			if (!$table->delete($ids)) {
				$this->setError($table->getError());
				return false;
			}
		}
		
		return true;
	}
	
	// Send message to guests
	public function message() {
		$jform		= $this->_app->input->get('jform',array(),'array');
		$send		= array();
		$sendRSVP	= array();
		$people		= array();
		$peopleRSVP	= array();
		$data		= array();
		$query		= $this->_db->getQuery(true);
		
		if (isset($jform['pending']) && $jform['pending'] == 1) $send[] = 0;
		if (isset($jform['accepted']) && $jform['accepted'] == 1) $send[] = 1;
		if (isset($jform['denied']) && $jform['denied'] == 1) $send[] = 2;		
		
		if (isset($jform['interested']) && $jform['interested'] == 1) $sendRSVP[] = 'interested';
		if (isset($jform['going']) && $jform['going'] == 1) $sendRSVP[] = 'going';
		if (isset($jform['notgoing']) && $jform['notgoing'] == 1) $sendRSVP[] = 'notgoing';		
		
		if (!empty($jform['subscribers'])) {
			foreach ($jform['subscribers'] as $subscriberID) {
				if (substr($subscriberID,0,4) == 'rsvp') $peopleRSVP[] = (int) substr_replace($subscriberID, '', 0, 4); else $people[] = (int) $subscriberID;
				
			}
		}
		
		if (!empty($send) || !empty($people)) {
			$query->clear()
				->select($this->_db->qn('u.email'))->select($this->_db->qn('u.name'))->select($this->_db->qn('u.ide'))
				->from($this->_db->qn('#__rseventspro_users','u'))
				->where($this->_db->qn('u.ide').' = '.(int) $jform['id']);
			
			if (empty($people) && !empty($send)) {
				$query->where($this->_db->qn('u.state').' IN ('.implode(',',$send).')');
			} elseif (empty($send) && !empty($people)) {
				$query->where($this->_db->qn('u.idu').' IN ('.implode(',',$people).')');
			} elseif (!empty($send) && !empty($people)) {
				$query->where('('.$this->_db->qn('u.state').' IN ('.implode(',',$send).') OR '.$this->_db->qn('u.idu').' IN ('.implode(',',$people).'))');
			}
			
			$this->_app->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query)));
			
			$this->_db->setQuery($query);
			if ($subscribers = $this->_db->loadObjectList()) {
				$data = array_merge($subscribers, array());
			}
		}
		
		if (!empty($sendRSVP) || !empty($peopleRSVP)) {
			$query->clear()
				->select($this->_db->qn('u.email'))->select($this->_db->qn('u.name'))->select($this->_db->qn('r.ide'))
				->from($this->_db->qn('#__rseventspro_rsvp_users','r'))
				->join('LEFT',$this->_db->qn('#__users','u').' ON '.$this->_db->qn('r.uid').' = '.$this->_db->qn('u.id'))
				->where($this->_db->qn('r.ide').' = '.(int) $jform['id']);
				
			if (empty($peopleRSVP) && !empty($sendRSVP)) {
				$query->where($this->_db->qn('r.rsvp').' IN ('.rseventsproHelper::quoteImplode($sendRSVP).')');
			} elseif (empty($sendRSVP) && !empty($peopleRSVP)) {
				$query->where($this->_db->qn('r.uid').' IN ('.implode(',',$peopleRSVP).')');
			} elseif (!empty($sendRSVP) && !empty($peopleRSVP)) {
				$query->where('('.$this->_db->qn('r.rsvp').' IN ('.rseventsproHelper::quoteImplode($sendRSVP).') OR '.$this->_db->qn('r.uid').' IN ('.implode(',',$peopleRSVP).'))');
			}
			
			$this->_db->setQuery($query);
			if ($RSVPsubscribers = $this->_db->loadObjectList()) {
				$data = array_merge($data, $RSVPsubscribers);
			}
		}
		
		if (!empty($data)) {
			$subject = $jform['subject'];
			$message = $jform['message'];
			$sent	 = array();
			
			foreach ($data as $subscriber) {
				$hash = md5($subscriber->email.$subscriber->ide);
				if (!isset($sent[$hash])) {
					rseventsproEmails::guests($subscriber->email, $subscriber->ide, $subscriber->name, $subject, $message);
					$sent[$hash] = true;
				}
			}
		}
		
		return true;
	}
	
	// Invite people to event
	public function invite() {
		jimport('joomla.mail.helper');
		
		$lang		= JFactory::getLanguage();
		$jform		= $this->_app->input->get('jform',array(),'array');
		$from		= $jform['from'];
		$fromname	= $jform['from_name'];
		$emails		= $jform['emails'];
		$ide		= $this->_app->input->getInt('id');
		
		$from		= !empty($from) ? $from : rseventsproHelper::getConfig('email_from');
		$fromname	= !empty($fromname) ? $fromname : rseventsproHelper::getConfig('email_fromname');
		
		if (!empty($emails)) {
			$emails = str_replace("\r",'',$emails);
			$emails = explode("\n",$emails);
			
			if (!empty($emails)) {
				foreach ($emails as $email) {
					if (JMailHelper::isEmailAddress($email))
						rseventsproEmails::invite($from,$fromname,$email,$ide, $lang->getTag());
				}
			}
		}
		
		return true;
	}
	
	// Export event 
	public function export() {
		$id		= $this->_app->input->getInt('id');
		
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/ical.php';
		$ical = RSEventsProiCal::getInstance(array($id));
		
		$ical->toIcal();
	}
	
	// Rate event
	public function rate() {
		$id		= $this->_app->input->getInt('id',0);
		$vote	= $this->_app->input->getInt('feedback',0);
		$ip		= md5($_SERVER['REMOTE_ADDR']);
		$query	= $this->_db->getQuery(true);
		
		//check for the id of the event and for the number of votes
		if ($id == 0 || $vote == 0) {
			return json_encode(array('error' => JText::_('COM_RSEVENTSPRO_INVALID_EVENT_OR_BLANK_VOTE')));
		}
		
		//check for vote number
		if ($vote > 5){
			return json_encode(array('error' => JText::_('COM_RSEVENTSPRO_INVALID_VOTE')));
		}
		
		//check if the user or the ip has already voted
		$query->clear()
			->select($this->_db->qn('value'))
			->from($this->_db->qn('#__rseventspro_rating'))
			->where($this->_db->qn('ip').' = '.$this->_db->q($ip))
			->where($this->_db->qn('ide').' = '.$id);
			
		$this->_db->setQuery($query,0,1);
		$voted = $this->_db->loadResult();
		
		//if the user voted do nothing
		if ($voted) {
			return json_encode(array('error' => JText::_('COM_RSEVENTSPRO_ALREADY_VOTED')));
		}
		
		//insert the vote
		$query->clear()
			->insert($this->_db->qn('#__rseventspro_rating'))
			->set($this->_db->qn('ip').' = '.$this->_db->q($ip))
			->set($this->_db->qn('ide').' = '.$id)
			->set($this->_db->qn('value').' = '.$this->_db->q($vote));
		
		$this->_db->setQuery($query);
		$this->_db->execute();
		
		//get the total votes
		$query->clear()
			->select('CEIL(IFNULL(SUM(value)/COUNT(id),0))')
			->from($this->_db->qn('#__rseventspro_rating'))
			->where($this->_db->qn('ide').' = '.$id);
		
		
		$this->_db->setQuery($query);
		$rating = (int) $this->_db->loadResult();
		
		return json_encode(array('rating' => $rating, 'message' => JText::_('COM_RSEVENTSPRO_VOTE_ADDED')));
	}
	
	// Save location
	public function savelocation() {
		$table	= JTable::getInstance('Location','RseventsproTable');
		$data	= $this->_app->input->get('jform',array(),'array');
		
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}
		
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}
		
		if (!empty($this->permissions['event_moderation']) && !rseventsproHelper::admin()) 
			$table->published = 0;
		
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}
		
		$this->setState($this->getName().'.lid',$table->id);
		
		return true;
	}
	
	// Save category
	public function savecategory() {
		$data	= $this->_app->input->get('jform',array(),'array');
		
		$data['extension'] = 'com_rseventspro';
		$data['language'] = '*';
		$data['params'] = '';
		$data['description'] = '';
		$table = JTable::getInstance('Category', 'RseventsproTable');
		$table->setLocation($data['parent_id'], 'last-child');
		$table->save($data);
		$table->rebuildPath($table->id);
		$table->rebuild($table->id, $table->lft, $table->level, $table->path);
		
		$this->setState($this->getName().'.cid',$table->id);
		return true;
	}
	
	// Subscribe user
	public function subscribe($idsubmission = null) {
		jimport('joomla.mail.helper');
		
		$now			= JFactory::getDate();
		$query			= $this->_db->getQuery(true);
		$lang			= JFactory::getLanguage();
		$nowunix		= $now->toUnix();
		$jinput			= $this->_app->input;
		$id				= $jinput->getInt('id');
		$name			= $jinput->getString('name');
		$email			= $jinput->getString('email');
		$payment		= $jinput->getString('payment');
		$form			= $jinput->get('form',array(),'array');
		$from			= $jinput->getInt('from');
		$total			= 0;
		$discount		= 0;
		$info			= '';
		$cansubscribe	= rseventsproHelper::getCanSubscribe($id, true);
		$couponid		= 0;
		$tickets		= array();
		$eventtickets	= array();
		$seats			= array();
		$discounts		= array();
		$state			= 0;
		$tax			= 0;
		
		// RSForm!Pro mapping
		if (!empty($form['RSEProName']) && $jinput->get('option') == 'com_rseventspro')	{
			$id			= $jinput->getInt('id');
			$name		= @$form['RSEProName'];
			$email		= @$form['RSEProEmail'];
			$payment	= is_array($form['RSEProPayment']) ? $form['RSEProPayment'][0] : @$form['RSEProPayment'];
		}
		
		$email = trim($email);
		
		// Get event name
		$query->clear()
			->select($this->_db->qn('name'))->select($this->_db->qn('discounts'))->select($this->_db->qn('early_fee'))->select($this->_db->qn('early_fee_type'))
			->select($this->_db->qn('early_fee_end'))->select($this->_db->qn('late_fee'))->select($this->_db->qn('late_fee_type'))->select($this->_db->qn('late_fee_start'))
			->select($this->_db->qn('automatically_approve'))->select($this->_db->qn('notify_me'))->select($this->_db->qn('owner'))->select($this->_db->qn('ticketsconfig'))
			->from($this->_db->qn('#__rseventspro_events'))
			->where($this->_db->qn('id').' = '.$id);
		
		$this->_db->setQuery($query);
		$event = $this->_db->loadObject();
		
		// Check for consent 
		if (rseventsproHelper::getConfig('consent','int','1')) {
			if (!isset($form['RSEProName'])) {
				$consent = $jinput->getInt('consent',0);
				if (!$consent) {
					return array('status' => false, 'url' => rseventsproHelper::route('index.php?option=com_rseventspro&layout=subscribe&id='.rseventsproHelper::sef($id,$event->name),false) , 'message' => JText::_('COM_RSEVENTSPRO_CONSENT_INFO'));
				}
			}
		}
		
		// Check if this event has tickets assigned to it
		$query->clear()
			->select('COUNT('.$this->_db->qn('id').')')
			->from($this->_db->qn('#__rseventspro_tickets'))
			->where($this->_db->qn('ide').' = '.(int) $id);
		
		$this->_db->setQuery($query);
		$hasTickets = $this->_db->loadResult();
		
		if (!JMailHelper::isEmailAddress($email) || empty($name))
			return array('status' => false, 'url' => rseventsproHelper::route('index.php?option=com_rseventspro&layout=subscribe&id='.rseventsproHelper::sef($id,$event->name),false) , 'message' => JText::_('COM_RSEVENTSPRO_INVALID_SUBSCRIBE_FORM'));
		
		if (!$cansubscribe['status']) {
			return array('status' => false, 'id' => $id, 'name' => $event->name, 'url' => rseventsproHelper::route('index.php?option=com_rseventspro&layout=subscribe&id='.rseventsproHelper::sef($id,$event->name),false),  'message' => $cansubscribe['err']);
		}
		
		// Set tickets
		if ($event->ticketsconfig) {
			$seatTaken	= false;
			$tickets	= array();
			$thetickets	= $jinput->get('tickets',array(),'array');
			$unlimited	= $jinput->get('unlimited',array(),'array');
			
			foreach ($thetickets as $tid => $theticket) {
				$tickets[$tid] = count($theticket);
			}
			
			if (!empty($unlimited)) {
				$unlimited = array_map('intval',$unlimited);
				foreach ($unlimited as $unlimitedid => $quantity)
					$tickets[$unlimitedid] = $quantity;
			}
			
			$seats = $thetickets;
			
			foreach ($seats as $ticketID => $ticketSeats) {
				foreach($ticketSeats as $ticketSeat) {
					$query->clear()
						->select($this->_db->qn('id'))
						->from($this->_db->qn('#__rseventspro_user_seats'))
						->where($this->_db->qn('idt').' = '.$this->_db->q($ticketID))
						->where($this->_db->qn('seat').' = '.$this->_db->q($ticketSeat));
					$this->_db->setQuery($query);
					if ($this->_db->loadResult()) {
						JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_RSEVENTSPRO_SEAT_ALREADY_TAKEN', $ticketSeat), 'error');
						$seatTaken = true;
					}
				}
			}
			
			if (empty($tickets) && $hasTickets) {
				JFactory::getApplication()->enqueueMessage(JText::_('COM_RSEVENTSPRO_SELECT_TICKETS_ERROR'), 'error');
				return array('status' => false, 'url' => rseventsproHelper::route('index.php?option=com_rseventspro&layout=subscribe&id='.rseventsproHelper::sef($id,$event->name),false) , 'message' => '');
			}
			
			if ($seatTaken) {
				return array('status' => false, 'url' => rseventsproHelper::route('index.php?option=com_rseventspro&layout=subscribe&id='.rseventsproHelper::sef($id,$event->name),false) , 'message' => '');
			}
		} else {
			if (rseventsproHelper::getConfig('multi_tickets','int')) {
				$tickets = $jinput->get('tickets',array(),'array');
				
				if (empty($tickets) && !empty($form['RSEProTickets']) && $jinput->get('option') == 'com_rseventspro') {
					if ($from == 1) {
						$tickets = array($form['RSEProTickets'] => $jinput->getInt('number'));
					} else  {
						$tickets = array($form['RSEProTickets'] => $jinput->getInt('numberinp'));
					}
				}
				
				if (empty($tickets) && $hasTickets) {
					JFactory::getApplication()->enqueueMessage(JText::_('COM_RSEVENTSPRO_SELECT_TICKETS_ERROR'), 'error');
					return array('status' => false, 'url' => rseventsproHelper::route('index.php?option=com_rseventspro&layout=subscribe&id='.rseventsproHelper::sef($id,$event->name),false) , 'message' => '');
				}
			} else {
				$ticket = (!empty($form['RSEProTickets']) && $jinput->get('option') == 'com_rseventspro') ? $form['RSEProTickets'] : $jinput->get('ticket');
				
				if (!empty($ticket)) {
					if ($from == 1) {
						$tickets = array($ticket => $jinput->getInt('number'));
					} else {
						$tickets = array($ticket => $jinput->getInt('numberinp'));
					}
				}
			}
		}
		
		// Check for quantity
		$negative = false;
		if (!empty($tickets)) {
			foreach($tickets as $ticket => $quantity) {
				if ((int) $quantity <= 0)
					$negative = true;
			}
		}
		
		if ($negative) {
			return array('status' => false, 'url' => rseventsproHelper::route('index.php?option=com_rseventspro&layout=subscribe&id='.rseventsproHelper::sef($id,$event->name),false) , 'message' => JText::_('COM_RSEVENTSPRO_INVALID_QUANTITY'));
		}
		
		// Set the verification string
		$verification = md5(time().$id.$name);
		
		// Get the user id
		$uid = 0;
		$create_user = rseventsproHelper::getConfig('create_user','int');
		
		if ($this->_user->get('guest')) {
			if ($create_user == 1) {
				$uid = rseventsproHelper::returnUser($email,$name);
			}
		} else {
			$uid = $this->_user->get('id');
		}
		
		$idsubmission = !is_null($idsubmission) ? $idsubmission : 0;
		
		// Trigger before the user subscribes.
		$this->_app->triggerEvent('rsepro_beforeSubscribe',array(array('name'=>&$name, 'email'=>&$email)));
		
		$sid	= JFactory::getSession()->getId();
		$hash	= md5($email.$sid);
		
		$query->clear()
			->insert($this->_db->qn('#__rseventspro_users'))
			->set($this->_db->qn('ide').' = '.(int) $id)
			->set($this->_db->qn('idu').' = '.(int) $uid)
			->set($this->_db->qn('name').' = '.$this->_db->q($name))
			->set($this->_db->qn('email').' = '.$this->_db->q($email))
			->set($this->_db->qn('date').' = '.$this->_db->q($now->toSql()))
			->set($this->_db->qn('state').' = 0')
			->set($this->_db->qn('SubmissionId').' = '.(int) $idsubmission)
			->set($this->_db->qn('verification').' = '.$this->_db->q($verification))
			->set($this->_db->qn('gateway').' = '.$this->_db->q($payment))
			->set($this->_db->qn('ip').' = '.$this->_db->q(rseventsproHelper::getIP()))
			->set($this->_db->qn('params').' = '.$this->_db->q(''))
			->set($this->_db->qn('log').' = '.$this->_db->q(''))
			->set($this->_db->qn('hash').' = '.$this->_db->q($hash))
			->set($this->_db->qn('lang').' = '.$this->_db->q($lang->getTag()));
		
		if ($create_user == 2) {
			$query->set($this->_db->qn('create_user').' = 1');
		}
		
		// Add the method that iDeal is using
		if (rseventsproHelper::ideal() && $payment == 'ideal') {
			$iDealMethod = rseventsproHelper::getConfig('ideal_account');
			$query->set($this->_db->qn('ideal').' = '.$this->_db->q($iDealMethod));
		}
		
		$this->_db->setQuery($query);
		$this->_db->execute();
		$ids = (int) $this->_db->insertid();
		
		if (!empty($tickets)) {
			foreach ($tickets as $tid => $quantity) {
				$checkticket = rseventsproHelper::checkticket($tid);
				if ($checkticket == RSEPRO_TICKETS_NOT_AVAILABLE) continue;
				
				$query->clear()
					->select($this->_db->qn('name'))->select($this->_db->qn('price'))->select($this->_db->qn('seats'))
					->from($this->_db->qn('#__rseventspro_tickets'))
					->where($this->_db->qn('id').' = '.(int) $tid);
				
				$this->_db->setQuery($query);
				$ticket = $this->_db->loadObject();
				
				if ($checkticket > RSEPRO_TICKETS_UNLIMITED && $quantity > $checkticket) 
					$quantity = $checkticket;
				
				$eventtickets[$tid] = $quantity;
				
				// Calculate the total
				if ($ticket->price > 0) {
					$price = $ticket->price * $quantity;
					if ($event->discounts) {
						$eventdiscount = rseventsproHelper::discount($id,$ticket->price);
						if (is_array($eventdiscount)) {
							
							$query->clear()
								->select($this->_db->qn('c.action'))->select($this->_db->qn('c.type'))
								->from($this->_db->qn('#__rseventspro_coupons','c'))
								->join('left',$this->_db->qn('#__rseventspro_coupon_codes','cc').' ON '.$this->_db->qn('cc.idc').' = '.$this->_db->qn('c.id'))
								->where($this->_db->qn('cc.id').' = '.(int) $eventdiscount['id']);
							
							$this->_db->setQuery($query);
							$thecoupon = $this->_db->loadObject();
							
							if ($thecoupon->action == 0) {
								if ($thecoupon->type == 0)
									$discount += $eventdiscount['discount'] * $quantity;
								else
									$discount += $eventdiscount['discount'];
							}
							$couponid = $eventdiscount['id'];
						}
					}
					$total += $price;
				}
				
				// Insert tickets into database
				$query->clear()
					->insert($this->_db->qn('#__rseventspro_user_tickets'))
					->set($this->_db->qn('ids').' = '.(int) $ids)
					->set($this->_db->qn('idt').' = '.(int) $tid)
					->set($this->_db->qn('quantity').' = '.(int) $quantity);
				
				$this->_db->setQuery($query);
				$this->_db->execute();
				
				// Add seats
				if (isset($seats[$tid]) && !empty($seats[$tid])) {
					$theseats = $quantity < count($seats[$tid]) ? array_slice($seats[$tid],0,$quantity) : $seats[$tid];
					
					if (!empty($theseats)) {
						foreach ($theseats as $seat) {
							$query->clear()
								->insert($this->_db->qn('#__rseventspro_user_seats'))
								->set($this->_db->qn('ids').' = '.(int) $ids)
								->set($this->_db->qn('idt').' = '.(int) $tid)
								->set($this->_db->qn('seat').' = '.(int) $seat);
							
							$this->_db->setQuery($query);
							$this->_db->execute();
						}
					}
				}
				
				// Get purchased tickets
				if ($ticket->price > 0) {
					$info .= $quantity . ' x ' .$ticket->name.' ('.rseventsproHelper::currency($ticket->price).') '.rseventsproHelper::getSeats($ids,$tid).' <br />';
				} else {
					$info .= $quantity . ' x ' .$ticket->name.' ('.JText::_('COM_RSEVENTSPRO_GLOBAL_FREE').')<br />';
				}
			}
		} else {
			// Insert tickets into database
			$query->clear()
				->insert($this->_db->qn('#__rseventspro_user_tickets'))
				->set($this->_db->qn('ids').' = '.(int) $ids)
				->set($this->_db->qn('idt').' = 0')
				->set($this->_db->qn('quantity').' = 1');
			
			$this->_db->setQuery($query);
			$this->_db->execute();
		}
		
		if ($event->discounts) {
			$eventdiscount = rseventsproHelper::discount($id,$total);
			if (is_array($eventdiscount)) {
				$query->clear()
					->select($this->_db->qn('c.action'))
					->from($this->_db->qn('#__rseventspro_coupons','c'))
					->join('left',$this->_db->qn('#__rseventspro_coupon_codes','cc').' ON '.$this->_db->qn('cc.idc').' = '.$this->_db->qn('c.id'))
					->where($this->_db->qn('cc.id').' = '.(int) $eventdiscount['id']);
				
				$this->_db->setQuery($query);
				$couponaction = $this->_db->loadResult();
				
				if ($couponaction == 1)
					$discount += $eventdiscount['discount'];
				$couponid = $eventdiscount['id'];
			}
		}
		
		if ($event->discounts && $discount) {
			$discounts[] = (object) array('discount' => $discount, 'id' => $couponid, 'code' => '', 'global' => false);
		}
		
		// Check for a global discount
		if ($event->discounts) {
			if ($globalDiscount = rseventsproHelper::globalDiscount($id, $total, $eventtickets, $payment)) {
				$discounts[] = (object) array('discount' => $globalDiscount['discount'], 'id' => $globalDiscount['id'], 'code' => $globalDiscount['code'], 'global' => true);
			}
		}
		
		// Sort discounts
		usort($discounts, array('rseventsproHelper', 'sort_discounts'));
		
		// Select discount
		if (isset($discounts[0])) {
			$discount = $discounts[0]->discount;
			
			// Update the use of the coupon and add the coupon code to the users table
			if ($discounts[0]->global) {
				$query->clear()
					->update($this->_db->qn('#__rseventspro_discounts'))
					->set($this->_db->qn('used').' = '.$this->_db->qn('used').' + 1')
					->where($this->_db->qn('id').' = '.(int) $discounts[0]->id);
				
				$this->_db->setQuery($query);
				$this->_db->execute();
				
				$query->clear()
					->update($this->_db->qn('#__rseventspro_users'))
					->set($this->_db->qn('coupon').' = '.$this->_db->q($discounts[0]->code))
					->where($this->_db->qn('id').' = '.(int) $ids);
				
				$this->_db->setQuery($query);
				$this->_db->execute();
			} else {
				if ($discounts[0]->id) {
					$query->clear()
						->update($this->_db->qn('#__rseventspro_coupon_codes'))
						->set($this->_db->qn('used').' = '.$this->_db->qn('used').' + 1')
						->where($this->_db->qn('id').' = '.(int) $discounts[0]->id);
					
					$this->_db->setQuery($query);
					$this->_db->execute();
					
					$query->clear()
						->select($this->_db->qn('code'))
						->from($this->_db->qn('#__rseventspro_coupon_codes'))
						->where($this->_db->qn('id').' = '.(int) $discounts[0]->id);
					
					$this->_db->setQuery($query);
					if ($couponcode = $this->_db->loadResult()) {
						$query->clear()
							->update($this->_db->qn('#__rseventspro_users'))
							->set($this->_db->qn('coupon').' = '.$this->_db->q($couponcode))
							->where($this->_db->qn('id').' = '.(int) $ids);
						
						$this->_db->setQuery($query);
						$this->_db->execute();
					}
				}
			}
		}
		
		// Update the total after the discount
		if ($discount) {
			$total = $total - $discount;
		}
		
		// If this is a free ticket subscription automatically approve the subscription
		if ($total == 0 && $event->automatically_approve) {
			$query->clear()
				->update($this->_db->qn('#__rseventspro_users'))
				->set($this->_db->qn('state').' = 1')
				->where($this->_db->qn('id').' = '.(int) $ids);
			
			if ($create_user == 2) {
				$uid = rseventsproHelper::returnUser($email,$name);
				$query->set($this->_db->qn('idu').' = '.(int) $uid);
			}
			
			$this->_db->setQuery($query);
			$this->_db->execute();
			$state = 1;
		}
		
		// Check for late and early fees
		$early = 0;
		if ($total > 0 && $event->discounts) {
			if (!empty($event->early_fee_end) && $event->early_fee_end != $this->_db->getNullDate()) {
				$early_fee_unix = JFactory::getDate($event->early_fee_end)->toUnix();
				if ($early_fee_unix > $nowunix) {
					$early = rseventsproHelper::setTax($total,$event->early_fee_type,$event->early_fee);
					$total = $total - $early;
				}
			}
		}

		$late = 0;
		if ($total > 0 && $event->discounts) {
			if (!empty($event->late_fee_start) && $event->late_fee_start != $this->_db->getNullDate()) {
				$late_fee_unix = JFactory::getDate($event->late_fee_start)->toUnix();
				if ($late_fee_unix < $nowunix) {
					$late = rseventsproHelper::setTax($total,$event->late_fee_type,$event->late_fee);
					$total = $total + $late;
				}
			}
		}
		
		// Check to see if the selected payment type is a wire payment
		$query->clear()
			->select($this->_db->qn('id'))->select($this->_db->qn('name'))
			->select($this->_db->qn('tax_type'))->select($this->_db->qn('tax_value'))
			->from($this->_db->qn('#__rseventspro_payments'))
			->where($this->_db->qn('id').' = '.(int) $payment);
		
		$this->_db->setQuery($query);
		$wire = $this->_db->loadObject();
		
		// Add payment tax
		if ($total > 0) {
			if (!empty($wire)) {
				$tax = rseventsproHelper::setTax($total,$wire->tax_type,$wire->tax_value);
				$total = $total + $tax;
			} else {
				$plugintaxes = $this->_app->triggerEvent('rsepro_tax',array(array('method'=>&$payment, 'total'=>$total)));
				
				if (!empty($plugintaxes)) {
					foreach ($plugintaxes as $plugintax) {
						if (!empty($plugintax)) $tax = $plugintax;
					}
				}
				
				$total = $total + $tax;
			}
		}
		
		$query->clear()
			->select($this->_db->qn('coupon'))
			->from($this->_db->qn('#__rseventspro_users'))
			->where($this->_db->qn('id').' = '.(int) $ids);
		$this->_db->setQuery($query);
		$thecouponcode = $this->_db->loadResult();
		
		$ticketstotal		= rseventsproHelper::currency($total);
		$ticketsdiscount	= !empty($discount) ? rseventsproHelper::currency($discount) : '';
		$subscriptionTax	= !empty($tax) ? rseventsproHelper::currency($tax) : '';
		$lateFee			= !empty($late) ? rseventsproHelper::currency($late) : '';
		$earlyDiscount		= !empty($early) ? rseventsproHelper::currency($early) : '';
		$gateway			= rseventsproHelper::getPayment($payment);
		$IP					= rseventsproHelper::getIP();
		$coupon				= !empty($thecouponcode) ? $thecouponcode : '';
		$optionals			= array($info, $ticketstotal, $ticketsdiscount, $subscriptionTax, $lateFee, $earlyDiscount, $gateway, $IP, $coupon);
		
		// Trigger after the user subscribes.
		$this->_app->triggerEvent('rsepro_afterSubscribe',array(array('ids'=>$ids, 'name'=>&$name, 'email'=>&$email, 'discount'=>&$discount, 'early'=>&$early, 'late'=>&$late, 'tax'=>&$tax, 'total'=>$total, 'optionals'=>&$optionals)));
		
		// Update the subscription with the late , early and discount fees
		$query->clear()
			->update($this->_db->qn('#__rseventspro_users'))
			->set($this->_db->qn('discount').' = '.$this->_db->q($discount))
			->set($this->_db->qn('early_fee').' = '.$this->_db->q($early))
			->set($this->_db->qn('late_fee').' = '.$this->_db->q($late))
			->set($this->_db->qn('tax').' = '.$this->_db->q($tax))
			->where($this->_db->qn('id').' = '.(int) $ids);
		
		$this->_db->setQuery($query);
		$this->_db->execute();
		
		// Notify the owner of a new subscription
		if ($ids && $event->notify_me) {
			$theuser = JFactory::getUser($event->owner); 			
			$additional_data = array(
				'{SubscriberUsername}' => $uid ? JFactory::getUser($uid)->get('username') : '',
				'{SubscriberName}' => $name,
				'{SubscriberEmail}' => $email,
				'{SubscribeDate}' => rseventsproHelper::showdate($now->toSql(),null,true),
				'{PaymentGateway}' => rseventsproHelper::getPayment($payment),
				'{SubscriberIP}' => rseventsproHelper::getIP(),
				'{TicketInfo}' => $info,
				'{TicketsTotal}' => $ticketstotal,
				'{TicketsDiscount}' => $ticketsdiscount
			);
			
			rseventsproEmails::notify_me($theuser->get('email'), $id, $additional_data, $lang->getTag(), $optionals, $ids);
		}
		
		$url = rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($id,$event->name),false,rseventsproHelper::itemid($id));
		if ($total > 0 && !empty($payment)) {
			if (!empty($wire)) {
				$url = rseventsproHelper::route('index.php?option=com_rseventspro&layout=wire&id='.$ids.'&pid='.rseventsproHelper::sef($wire->id,$wire->name),false);
			} else {
				$url = rseventsproHelper::route('index.php?option=com_rseventspro&task=payment&method='.$payment.'&hash='.md5($ids.$name.$email),false);
			}
			
			$query->clear()
				->update($this->_db->qn('#__rseventspro_users'))
				->set($this->_db->qn('URL').' = '.$this->_db->q(base64_encode($url)))
				->where($this->_db->qn('id').' = '.(int) $ids);
			
			$this->_db->setQuery($query);
			$this->_db->execute();
		}
		
		// Send registration email
		rseventsproEmails::registration($email, $id, $name, $optionals, $ids);
		
		// Send activation email
		if ($state)
			rseventsproEmails::activation($email, $id, $name, $optionals, $ids);
		
		if ($total > 0 && !empty($payment)) {
			if (!empty($wire)) {
				return array('status' => true, 'url' => $url, 'message' => JText::_('COM_RSEVENTSPRO_REGISTRATION_COMPLETE'));
			} else {
				// Payment plugins
				return array('status' => true, 'url' => $url, 'message' => JText::_('COM_RSEVENTSPRO_REGISTRATION_COMPLETE'));
			}
		}
		
		return array('status' => true, 'url' => $url, 'message' => JText::_('COM_RSEVENTSPRO_REGISTRATION_COMPLETE'));
	}
	
	// Unsubscribe user from the unsubscribe layout
	public function unsubscribeuser() {
		$id		= $this->_app->input->getInt('id');
		$ide	= $this->_app->input->getInt('ide');
		$now	= JFactory::getDate()->toUnix();
		$query	= $this->_db->getQuery(true);
		$config	= rseventsproHelper::getConfig();
		
		$query->clear()
			->select($this->_db->qn('id'))->select($this->_db->qn('name'))
			->select($this->_db->qn('unsubscribe_date'))->select($this->_db->qn('sync'))
			->select($this->_db->qn('notify_me_unsubscribe'))->select($this->_db->qn('owner'))
			->from($this->_db->qn('#__rseventspro_events'))
			->where($this->_db->qn('id').' = '.(int) $ide);
		
		$this->_db->setQuery($query);
		$event = $this->_db->loadObject();
		
		$userID = (int) $this->_user->get('id');
		
		if (empty($userID)) {
			$URL = rseventsproHelper::route('index.php?option=com_rseventspro&layout=unsubscribe&id='.rseventsproHelper::sef($event->id,$event->name).'&tmpl=component',false);
			return array('status' => false, 'url' => $URL, 'message' => JText::_('COM_RSEVENTSPRO_GUEST_UNSUBSCRIBED_ERROR'));
		}
		
		$URL = rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),false,rseventsproHelper::itemid($event->id));
		
		if (!empty($event->unsubscribe_date) && $event->unsubscribe_date != $this->_db->getNullDate()) {
			$unsubscribe_unix = JFactory::getDate($event->unsubscribe_date)->toUnix();
			if ($now > $unsubscribe_unix) {
				$URL = rseventsproHelper::route('index.php?option=com_rseventspro&layout=unsubscribe&id='.rseventsproHelper::sef($event->id,$event->name).'&tmpl=component',false);
				return array('status' => false, 'url' => $URL, 'message' => JText::_('COM_RSEVENTSPRO_USER_UNSUBSCRIBED_ERROR'));
			}
		}
		
		if (!empty($this->permissions['can_unsubscribe'])) {
			$query->clear()
				->select($this->_db->qn('id'))->select($this->_db->qn('name'))->select($this->_db->qn('email'))
				->select($this->_db->qn('ip'))->select($this->_db->qn('date'))
				->select($this->_db->qn('SubmissionId'))->select($this->_db->qn('lang'))
				->from($this->_db->qn('#__rseventspro_users'))
				->where($this->_db->qn('id').' = '.(int) $id)
				->where($this->_db->qn('idu').' = '.(int) $this->_user->get('id'));
			
			$this->_db->setQuery($query);
			$subscription = $this->_db->loadObject();
			
			if (!empty($subscription)) {
				JFactory::getApplication()->triggerEvent('rsepro_beforeUnsubscribe',array(array('subscription'=>$subscription)));
				
				// Send unsubscribe email
				rseventsproEmails::unsubscribe($subscription->email,$event->id,$subscription->name,$subscription->lang, $id);
				
				$pluginUnsubscribe = false;
				$this->_app->triggerEvent('rsepro_unsubscribeUser', array(array('unsubscribe' => &$pluginUnsubscribe, 'ide' => $ide, 'ids' => $subscription->id)));
				
				if (!$pluginUnsubscribe) {
					$query->clear()
						->delete($this->_db->qn('#__rseventspro_users'))
						->where($this->_db->qn('id').' = '.$id);
						
					$this->_db->setQuery($query);
					$this->_db->execute();
					
					$query->clear()
						->delete($this->_db->qn('#__rseventspro_user_tickets'))
						->where($this->_db->qn('ids').' = '.$id);
						
					$this->_db->setQuery($query);
					$this->_db->execute();
					
					$query->clear()
						->delete($this->_db->qn('#__rseventspro_user_seats'))
						->where($this->_db->qn('ids').' = '.$id);
						
					$this->_db->setQuery($query);
					$this->_db->execute();
					
					// Delete RSForm!Pro submission
					if (file_exists(JPATH_SITE.'/components/com_rsform/rsform.php') && $event->sync) {
						$query->clear()
							->delete()
							->from($this->_db->qn('#__rsform_submission_values'))
							->where($this->_db->qn('SubmissionId').' = '.(int) $subscription->SubmissionId);
						
						$this->_db->setQuery($query);
						$this->_db->execute();
						
						$query->clear()
							->delete()
							->from($this->_db->qn('#__rsform_submissions'))
							->where($this->_db->qn('SubmissionId').' = '.(int) $subscription->SubmissionId);
						
						$this->_db->setQuery($query);
						$this->_db->execute();
					}
				}
				
				// Notify the owner
				if ($event->notify_me_unsubscribe) {
					if ($event->owner) {
						$additional_data = array(
							'{SubscriberUsername}' => JFactory::getUser($this->_user->get('id'))->get('username'),
							'{SubscriberName}' => rseventsproHelper::getUser($this->_user->get('id')),
							'{SubscriberEmail}' => $subscription->email,
							'{SubscriberIP}' => $subscription->ip,
							'{SubscribeDate}' => rseventsproHelper::showdate($subscription->date,null,true)
						);
						
						rseventsproEmails::notify_me_unsubscribe(JFactory::getUser($event->owner)->get('email'), $event->id, $additional_data, $subscription->lang, $subscription->id);
					}
				}
				
			}
			return array('status' => true, 'url' => $URL, 'message' => JText::_('COM_RSEVENTSPRO_USER_UNSUBSCRIBED'));
		}
		
		$URL = rseventsproHelper::route('index.php?option=com_rseventspro&layout=unsubscribe&id='.rseventsproHelper::sef($event->id,$event->name).'&tmpl=component',false);
		return array('status' => false, 'url' => $URL, 'message' => JText::_('COM_RSEVENTSPRO_USER_UNSUBSCRIBED_ERROR'));
	}
	
	// Unsubscribe user
	public function unsubscribe() {
		$id		= $this->_app->input->getInt('id');
		$hash	= $this->_app->input->getString('hash');
		$now	= JFactory::getDate()->toUnix();
		$query	= $this->_db->getQuery(true);
		$config	= rseventsproHelper::getConfig();
		$valid	= true;
		
		if ($hash) {
			list($hash, $id) = explode('-', $hash, 2);
			
			// Check if the hash is in our database
			$query->clear()
				->select($this->_db->qn('id'))
				->from($this->_db->qn('#__rseventspro_users'))
				->where($this->_db->qn('hash').' = '.$this->_db->q($hash));
			$this->_db->setQuery($query);
			if ($this->_db->loadResult()) {
				$this->_app->input->set('id',$id);
			} else {
				$valid = false;
			}
		}
		
		$query->clear()
			->select($this->_db->qn('id'))->select($this->_db->qn('name'))->select($this->_db->qn('owner'))
			->select($this->_db->qn('sync'))->select($this->_db->qn('notify_me_unsubscribe'))
			->from($this->_db->qn('#__rseventspro_events'))
			->where($this->_db->qn('id').' = '.$id);
		
		$this->_db->setQuery($query);
		$event = $this->_db->loadObject();
		
		if (!$valid) {
			if (empty($event)) {
				$this->_app->enqueueMessage(JText::_('COM_RSEVENTSPRO_INVALID_SUBSCRIPTION'));
				$this->_app->redirect(rseventsproHelper::route('index.php?option=com_rseventspro'));
			} else {
				return array('id' => $event->id, 'name' => $event->name, 'message' => JText::_('COM_RSEVENTSPRO_INVALID_SUBSCRIPTION'));
			}
		}
		
		if (empty($hash)) {
			$userID = $this->_user->get('id');
			if (empty($userID)) {
				return array('id' => $event->id, 'name' => $event->name, 'message' => JText::_('COM_RSEVENTSPRO_GUEST_UNSUBSCRIBED_ERROR'));
			}
		}
		
		$query->clear()
			->select($this->_db->qn('u.id'))->select($this->_db->qn('u.name'))
			->select($this->_db->qn('u.email'))->select($this->_db->qn('u.SubmissionId'))
			->select($this->_db->qn('u.lang'))
			->from($this->_db->qn('#__rseventspro_users','u'))
			->where($this->_db->qn('u.ide').' = '.(int) $id);
			
		if ($hash) {
			$query->where($this->_db->qn('u.hash').' = '.$this->_db->q($hash));
		} else {
			$query->where($this->_db->qn('u.idu').' = '.(int) $this->_user->get('id'));
		}
		
		$this->_app->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query)));
		
		$this->_db->setQuery($query);
		$subscription = $this->_db->loadObject();
		
		$can_unsubscribe = $this->getCanUnsubscribe();
		if (!$can_unsubscribe) 
			return array('id' => $event->id, 'name' => $event->name, 'message' => JText::_('COM_RSEVENTSPRO_USER_UNSUBSCRIBED_ERROR'));
		
		if (!empty($this->permissions['can_unsubscribe'])) {
			$this->_app->triggerEvent('rsepro_beforeUnsubscribe',array(array('subscription'=>$subscription)));
			
			// Send unsubscribe email
			rseventsproEmails::unsubscribe($subscription->email,$id,$subscription->name,$subscription->lang,$subscription->id);
			
			$pluginUnsubscribe = false;
			$this->_app->triggerEvent('rsepro_unsubscribeUser', array(array('unsubscribe' => &$pluginUnsubscribe, 'ide' => $id, 'ids' => $subscription->id)));
			
			if (!$pluginUnsubscribe) {
				$query->clear()
					->delete($this->_db->qn('#__rseventspro_users'))
					->where($this->_db->qn('id').' = '.$subscription->id);
					
				$this->_db->setQuery($query);
				$this->_db->execute();
				
				$query->clear()
					->delete($this->_db->qn('#__rseventspro_confirmed'))
					->where($this->_db->qn('id').' = '.$subscription->id);
					
				$this->_db->setQuery($query);
				$this->_db->execute();
				
				$query->clear()
					->delete($this->_db->qn('#__rseventspro_user_tickets'))
					->where($this->_db->qn('ids').' = '.$subscription->id);
					
				$this->_db->setQuery($query);
				$this->_db->execute();
				
				$query->clear()
					->delete($this->_db->qn('#__rseventspro_user_seats'))
					->where($this->_db->qn('ids').' = '.$subscription->id);
					
				$this->_db->setQuery($query);
				$this->_db->execute();
				
				// Delete RSForm!Pro submission
				if (file_exists(JPATH_SITE.'/components/com_rsform/rsform.php') && $event->sync) {
					$query->clear()
						->delete($this->_db->qn('#__rsform_submission_values'))
						->where($this->_db->qn('SubmissionId').' = '.(int) $subscription->SubmissionId);
					
					$this->_db->setQuery($query);
					$this->_db->execute();
					
					$query->clear()
						->delete($this->_db->qn('#__rsform_submissions'))
						->where($this->_db->qn('SubmissionId').' = '.(int) $subscription->SubmissionId);
					
					$this->_db->setQuery($query);
					$this->_db->execute();
				}
			}
			
			// Notify the owner
			if ($event->notify_me_unsubscribe) {
				if ($event->owner) {
					$additional_data = array(
						'{SubscriberUsername}' => JFactory::getUser($this->_user->get('id'))->get('username'),
						'{SubscriberName}' => rseventsproHelper::getUser($this->_user->get('id')),
						'{SubscriberEmail}' => $subscription->email,
						'{SubscriberIP}' => $subscription->ip,
						'{SubscribeDate}' => rseventsproHelper::showdate($subscription->date,null,true)
					);
					
					rseventsproEmails::notify_me_unsubscribe(JFactory::getUser($event->owner)->get('email'), $event->id, $additional_data, $subscription->lang, $subscription->id);
				}
			}
			
			return array('id' => $event->id, 'name' => $event->name, 'message' => JText::_('COM_RSEVENTSPRO_USER_UNSUBSCRIBED'));
		}		
		
		return array('id' => $event->id, 'name' => $event->name, 'message' => JText::_('COM_RSEVENTSPRO_USER_UNSUBSCRIBED_ERROR'));
	}
	
	// Check if the user can unsubscribe
	public function getCanUnsubscribe() {
		$id		= $this->_app->input->getInt('id');
		$now	= JFactory::getDate()->toUnix();
		$query	= $this->_db->getQuery(true);
		
		$query->clear()
			->select($this->_db->qn('unsubscribe_date'))
			->from($this->_db->qn('#__rseventspro_events'))
			->where($this->_db->qn('id').' = '.$id);
		
		$this->_db->setQuery($query);
		$unsubscribe_date = $this->_db->loadResult();
		
		if (!empty($unsubscribe_date) && $unsubscribe_date != $this->_db->getNullDate()) {
			$unsubscribeunix = JFactory::getDate($unsubscribe_date)->toUnix();
			if ($now > $unsubscribeunix) return false;
		}
		
		return true;
	}
	
	// Save event ticket
	public function saveticket() {
		$query = $this->_db->getQuery(true);
		$data  = $this->_app->input->get('jform',array(),'array');
		$data  = (object) $data;
		
		$query->select('MAX('.$this->_db->qn('order').')')
			->from($this->_db->qn('#__rseventspro_tickets'))
			->where($this->_db->qn('ide').' = '.$this->_db->q($data->ide));
		$this->_db->setQuery($query);
		$ordering = (int) $this->_db->loadResult();
		$data->order = $ordering + 1;
		
		$groups = $this->_app->input->get('groups',array(),'array');
		if (!empty($groups)) {
			try {
				$registry = new JRegistry;
				$registry->loadArray($groups);
				$data->groups = $registry->toString();
			} catch (Exception $e) {
				$data->groups = array();
			}
		} else {
			$data->groups = '';
		}
		
		$data->position = '';
		$data->layout = '';
		$data->price = (float) $data->price;
		$data->seats = (int) $data->seats;
		$data->user_seats = (int) $data->user_seats;
		
		if (!empty($data->from) && $data->from != $this->_db->getNullDate()) {
			$start = JFactory::getDate($data->from, rseventsproHelper::getTimezone());
			$data->from = $start->format('Y-m-d H:i:s');
		} else {
			$data->from = $this->_db->getNullDate();
		}
		
		if (!empty($data->to) && $data->to != $this->_db->getNullDate()) {
			$end = JFactory::getDate($data->to, rseventsproHelper::getTimezone());
			$data->to = $end->format('Y-m-d H:i:s');
		} else {
			$data->to = $this->_db->getNullDate();
		}
		
		$this->_db->insertObject('#__rseventspro_tickets', $data, 'id');
		return $data->id;
	}
	
	// Remove ticket
	public function removeticket() {
		$query	= $this->_db->getQuery(true);
		$id		= $this->_app->input->getInt('id');
		$response = false;
		
		if ($id) {
			$query->clear()
				->delete()
				->from($this->_db->qn('#__rseventspro_tickets'))
				->where($this->_db->qn('id').' = '.$id);
			
			$this->_db->setQuery($query);
			$response = $this->_db->execute();
			
			if ($response)
				JFactory::getApplication()->triggerEvent('rsepro_afterDeleteTicket', array(array('id' => $id)));
		}
		
		return $response;
	}
	
	// Save event coupon
	public function savecoupon() {
		$query		= $this->_db->getQuery(true);
		$data		= $this->_app->input->get('jform',array(),'array');
		$tzoffset	= rseventsproHelper::getTimezone();
		$data		= (object) $data;
		$groups		= $this->_app->input->get('groups',array(),'array');
		
		if (!empty($groups)) {
			try {
				$registry = new JRegistry;
				$registry->loadArray($groups);
				$data->groups = $registry->toString();
			} catch (Exception $e) {
				$data->groups = array();
			}
		}
		
		if (!empty($data->from) && $data->from != $this->_db->getNullDate()) {
			$start = JFactory::getDate($data->from);
			$start->setTimezone(new DateTimezone($tzoffset));
			$data->from = $start->toSql();
		} else {
			$data->from = $this->_db->getNullDate();
		}
		
		if (!empty($data->to) && $data->to != $this->_db->getNullDate()) {
			$end = JFactory::getDate($data->to);
			$end->setTimezone(new DateTimezone($tzoffset));
			$data->to = $end->toSql();
		} else {
			$data->to = $this->_db->getNullDate();
		}
		
		$data->usage = (int) $data->usage;
		
		$this->_db->insertObject('#__rseventspro_coupons', $data, 'id');
		
		if ($codes = JFactory::getApplication()->input->getString('codes')) {
			$codes = explode("\n",$codes);
			if (!empty($codes)) {
				foreach ($codes as $code) {
					$code = trim($code);
					$query->clear()
						->insert($this->_db->qn('#__rseventspro_coupon_codes'))
						->set($this->_db->qn('idc').' = '.(int) $data->id)
						->set($this->_db->qn('code').' = '.$this->_db->q($code));
					
					$this->_db->setQuery($query);
					$this->_db->execute();
				}
			}
		}
		
		return $data->id;
	}
	
	// Remove coupon
	public function removecoupon() {
		$query	= $this->_db->getQuery(true);
		$id		= $this->_app->input->getInt('id');
		
		if ($id) {
			$query->clear()
				->delete()
				->from($this->_db->qn('#__rseventspro_coupons'))
				->where($this->_db->qn('id').' = '.$id);
			
			$this->_db->setQuery($query);
			if ($this->_db->execute()) {
				$query->clear()
					->delete()
					->from($this->_db->qn('#__rseventspro_coupon_codes'))
					->where($this->_db->qn('idc').' = '.$id);
				
				$this->_db->setQuery($query);
				$this->_db->execute();
				return true;
			}
		}
		return false;
	}
	
	// Get file details
	public function getFile() {
		$query	= $this->_db->getQuery(true);
		$id		= $this->_app->input->getInt('id');
		
		$query->clear()
			->select($this->_db->qn('id'))->select($this->_db->qn('name'))->select($this->_db->qn('permissions'))
			->from($this->_db->qn('#__rseventspro_files'))
			->where($this->_db->qn('id').' = '.$id);
		
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}
	
	// Save event file details
	public function savefile() {
		$query	= $this->_db->getQuery(true);
		$jinput	= $this->_app->input->post;
		$id		= $jinput->getInt('id');
		$permissions = '';
		
		$fp0 = $jinput->get('fp0');
		$fp1 = $jinput->get('fp1');
		$fp2 = $jinput->get('fp2');
		$fp3 = $jinput->get('fp3');
		$fp4 = $jinput->get('fp4');
		$fp5 = $jinput->get('fp5');
		
		if (isset($fp0) && $fp0 == 1) $permissions .= '1'; else $permissions .= '0';
		if (isset($fp1) && $fp1 == 1) $permissions .= '1'; else $permissions .= '0';
		if (isset($fp2) && $fp2 == 1) $permissions .= '1'; else $permissions .= '0';
		if (isset($fp3) && $fp3 == 1) $permissions .= '1'; else $permissions .= '0';
		if (isset($fp4) && $fp4 == 1) $permissions .= '1'; else $permissions .= '0';
		if (isset($fp5) && $fp5 == 1) $permissions .= '1'; else $permissions .= '0';
		
		$query->clear()
			->update($this->_db->qn('#__rseventspro_files'))
			->set($this->_db->qn('name').' = '.$this->_db->q($jinput->getString('name')))
			->set($this->_db->qn('permissions').' = '.$this->_db->q($permissions))
			->where($this->_db->qn('id').' = '.$this->_db->q($id));
		
		$this->_db->setQuery($query);
		$this->_db->execute();
		
		$this->setState('com_rseventspro.file.id',$id);
		$this->setState('com_rseventspro.file.name',$jinput->getString('name'));
		
		return true;
	}
	
	// Remove file
	public function removefile() {
		jimport('joomla.filesystem.file');
		
		$id = $this->_app->input->getInt('id');
		$query = $this->_db->getQuery(true);
		
		$query->clear()
			->select($this->_db->qn('location'))
			->from($this->_db->qn('#__rseventspro_files'))
			->where($this->_db->qn('id').' = '.$id);
		
		$this->_db->setQuery($query);
		if ($file = $this->_db->loadResult()) {
			$thefile = JPATH_SITE.'/components/com_rseventspro/assets/images/files/'.$file;
			if (JFile::exists($thefile)) {
				if (JFile::delete($thefile)) {
					$query->clear()
						->delete()
						->from($this->_db->qn('#__rseventspro_files'))
						->where($this->_db->qn('id').' = '.$id);
					
					$this->_db->setQuery($query);
					$this->_db->execute();
					
					return true;
				}
			}
		}
		
		return false;
	}
	
	// Get icon details
	public function getIcon() {
		if ($icon = JFactory::getApplication()->input->getString('icon','')) {
			return base64_decode($icon);
		}
		
		return false;
	}
	
	// Delete event icon
	public function deleteicon() {
		jimport('joomla.filesystem.file');
		
		$id = $this->_app->input->getInt('id');
		$query = $this->_db->getQuery(true);
		
		$query->clear()
			->select($this->_db->qn('icon'))
			->from($this->_db->qn('#__rseventspro_events'))
			->where($this->_db->qn('id').' = '.$id);
		
		$this->_db->setQuery($query);
		if ($icon = $this->_db->loadResult()) {
			if (JFile::exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$icon))
				JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$icon);
			
			$query->clear()
				->update($this->_db->qn('#__rseventspro_events'))
				->set($this->_db->qn('icon').' = '.$this->_db->q(''))
				->set($this->_db->qn('properties').' = '.$this->_db->q(''))
				->where($this->_db->qn('id').' = '.$id);
			
			$this->_db->setQuery($query);
			$this->_db->execute();
		}
		return true;
	}
	
	// Upload event icon
	public function upload() {
		jimport('joomla.filesystem.file');
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/phpthumb/phpthumb.class.php';
		
		$icon	= $this->_app->input->files->get('icon',array(),'array');
		$path	= JPATH_SITE.'/components/com_rseventspro/assets/images/events/';
		$id		= $this->_app->input->getInt('id');
		$query	= $this->_db->getQuery(true);
		$config = rseventsproHelper::getConfig();
		
		if (!empty($icon)) {
			$ext = JFile::getExt($icon['name']);
			if (in_array(strtolower($ext),array('jpg','png','jpeg'))) {
				if ($icon['error'] == 0) {
					$query->clear()
						->select($this->_db->qn('icon'))
						->from($this->_db->qn('#__rseventspro_events'))
						->where($this->_db->qn('id').' = '.$id);
					
					$this->_db->setQuery($query);
					if ($eventicon = $this->_db->loadResult()) {
						if (JFile::exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$eventicon))
							JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$eventicon);
						
						$extension	= JFile::getExt($eventicon);
						$name		= JFile::stripExt($eventicon);
						
						// Delete small icon
						if (file_exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/'.$config->icon_small_width.'/'.md5($config->icon_small_width.$name).'.'.$extension)) {
							JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/'.$config->icon_small_width.'/'.md5($config->icon_small_width.$name).'.'.$extension);
						}
						
						// Delete big icon
						if (file_exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/'.$config->icon_big_width.'/'.md5($config->icon_big_width.$name).'.'.$extension)) {
							JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/'.$config->icon_big_width.'/'.md5($config->icon_big_width.$name).'.'.$extension);
						}
						
						// Delete event listing icon from backend
						if (file_exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/70/'.md5('70'.$name).'.'.$extension)) {
							JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/70/'.md5('70'.$name).'.'.$extension);
						}
						
						// Delete event edit icon
						if (file_exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/188/'.md5('188'.$name).'.'.$extension)) {
							JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/188/'.md5('188'.$name).'.'.$extension);
						}
					}
					
					$file		= JFile::makeSafe($icon['name']);
					$filename	= basename(JFile::stripExt($file));
					
					while(JFile::exists($path.$filename.'.'.$ext))
						$filename .= rand(1,999);
					
					if (JFile::upload($icon['tmp_name'],$path.$filename.'.'.$ext)) {
						$query->clear()
							->update($this->_db->qn('#__rseventspro_events'))
							->set($this->_db->qn('icon').' = '.$this->_db->q($filename.'.'.$ext))
							->set($this->_db->qn('properties').' = '.$this->_db->q(''))
							->where($this->_db->qn('id').' = '.$id);
						
						$this->_db->setQuery($query);
						$this->_db->execute();
						
						$this->setState('com_rseventspro.edit.icon', $filename.'.'.$ext);
						$this->setState('rseventspro.icon',$filename.'.'.$ext);
						$this->setState('rseventspro.eid',$id);
						
					} else {
						$this->setError(JText::_('COM_RSEVENTSPRO_UPLOAD_ERROR'));
						return false;
					}
				} else {
					$this->setError(JText::_('COM_RSEVENTSPRO_FILE_ERROR'));
					return false;
				}
			} else {
				$this->setError(JText::_('COM_RSEVENTSPRO_WRONG_FILE_TYPE'));
				return false;
			}
		} else {
			$this->setError(JText::_('COM_RSEVENTSPRO_NO_FILE_SELECTED'));
			return false;
		}
		
		return true;
	}
	
	// Get image properties
	public function getProperties($public = true) {
		$id = $this->_app->input->getInt('id');
		$query = $this->_db->getQuery(true);
		
		$query->clear()
			->select($this->_db->qn('properties'))
			->from($this->_db->qn('#__rseventspro_events'))
			->where($this->_db->qn('id').' = '.$id);
		
		$this->_db->setQuery($query);
		if ($properties = $this->_db->loadResult()) {
			try {
				$registry = new JRegistry;
				$registry->loadString($properties);
				return $registry->toArray();
			} catch (Exception $e) {
				return array();
			}
		}
		
		return false;
	}
	
	// Crop event image
	public function crop() {
		$id		= $this->_app->input->getInt('id');
		$query	= $this->_db->getQuery(true);
		$path	= JPATH_SITE.'/components/com_rseventspro/assets/images/events/';
		$thumbs = JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/';
		
		$query->clear()
			->select($this->_db->qn('icon'))
			->from($this->_db->qn('#__rseventspro_events'))
			->where($this->_db->qn('id').' = '.$id);
		
		$this->_db->setQuery($query);
		$icon = $this->_db->loadResult();
		
		$this->setState('rseventspro.crop.icon', $icon);
		
		$left	= $this->_app->input->getInt('x1');
		$top	= $this->_app->input->getInt('y1');
		$width	= $this->_app->input->getInt('width');
		$height	= $this->_app->input->getInt('height');
		
		$properties = array('left' => $left, 'top' => $top, 'width' => $width, 'height' => $height);
		$registry = new JRegistry;
		$registry->loadArray($properties);
		$properties = $registry->toString();
		
		$query->clear()
			->update($this->_db->qn('#__rseventspro_events'))
			->set($this->_db->qn('properties').' = '.$this->_db->q($properties))
			->set($this->_db->qn('aspectratio').' = '.$this->_db->q($this->_app->input->getInt('aspectratio',0)))
			->where($this->_db->qn('id').' = '.$id);
		
		$this->_db->setQuery($query);
		$this->_db->execute();
		
		// Remove old thumbs
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		
		// Get file extension
		$extension	= JFile::getExt($icon);
		// Strip extension
		$name		= JFile::stripExt($icon);
		
		if ($folders = JFolder::folders($thumbs)) {
			$folders = array_map('intval',$folders);
			
			foreach ($folders as $folder) {
				if (file_exists($thumbs.$folder.'/'.md5($folder.$name).'.'.$extension)) {
					JFile::delete($thumbs.$folder.'/'.md5($folder.$name).'.'.$extension);
				}
			}
		}
		
		return true;
	}
	
	// Get event guests
	public function getGuests() {
		$id		= $this->_app->input->getInt('id');
		$query	= $this->_db->getQuery(true);
		$return	= array();
		
		$query->clear()
			->select('DISTINCT(u.email)')
			->select($this->_db->qn('ju.id'))
			->select($this->_db->qn('u.name'))
			->from($this->_db->qn('#__rseventspro_users','u'))
			->join('LEFT', $this->_db->qn('#__users','ju').' ON '.$this->_db->qn('u.idu').' = '.$this->_db->qn('ju.id'))
			->where($this->_db->qn('u.ide').' = '.$id)
			->where($this->_db->qn('u.state').' IN (0,1)');
		
		JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query)));
		
		$this->_db->setQuery($query);
		if ($guests = $this->_db->loadObjectList()) {
			foreach ($guests as $guest) {
				$object = new stdClass();
				
				// Already logged in?
				if ($guest->id) {
					$object->name = rseventsproHelper::getUser($guest->id, 'guest', $guest->name);
				} else {
					$object->name = $guest->name;
				}
				
				$object->url	= !empty($guest->id) ? rseventsproHelper::getProfile('guests', $guest->id) : '';
				$object->avatar = rseventsproHelper::getAvatar($guest->id,$guest->email);
				$return[] = $object;
			}
		}
		
		return $return;
	}
	
	// Get card details
	public function getCard() {
		$id		= $this->_app->input->getInt('id');
		
		return  rseventsproHelper::getCardDetails($id);
	}
	
	// Save event
	public function save() {
		$lang	= JFactory::getLanguage();
		$data	= $this->_app->input->get('jform', array(), 'array');
		$new	= $this->_app->input->getInt('new',0);
		$admin	= rseventsproHelper::admin();
		$query	= $this->_db->getQuery(true);
		
		$moderated = 0;
		
		if (!empty($this->permissions['event_moderation']) && $new && !$admin) 
			$data['published'] = 0;
		
		jimport('joomla.application.component.modeladmin');
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rseventspro/models');
		$model = JModelLegacy::getInstance('Event','RseventsproModel',  array('ignore_request' => true));
		
		if ($model->save($data)) {
			$this->setState('eventid', $model->getState('event.id'));
			$this->setState('eventname', $model->getState('event.name'));
			
			$query->clear()
				->select($this->_db->qn('owner'))
				->from($this->_db->qn('#__rseventspro_events'))
				->where($this->_db->qn('id').' = '.(int) $model->getState('event.id'));
			
			$this->_db->setQuery($query);
			$owner = (int) $this->_db->loadResult();
			
			if ((!empty($this->permissions['event_moderation']) && !$admin) && $owner == JFactory::getUser()->get('id')) {
				$query->clear()
					->select($this->_db->qn('completed'))->select($this->_db->qn('approved'))
					->from($this->_db->qn('#__rseventspro_events'))
					->where($this->_db->qn('id').' = '.(int) $model->getState('event.id'));
					
				$this->_db->setQuery($query);
				$event = $this->_db->loadObject();
				
				if ($event->completed && !$event->approved) {
					$emails = rseventsproHelper::getConfig('event_moderation_emails');
					$emails = !empty($emails) ? explode(',',$emails) : '';
					
					if (!empty($emails))
						foreach ($emails as $email)
							rseventsproEmails::moderation(trim($email), $model->getState('event.id'), $lang->getTag());
							
					$query->clear()
						->update($this->_db->qn('#__rseventspro_events'))
						->set($this->_db->qn('published').' = 0')
						->set($this->_db->qn('approved').' = 1')
						->where($this->_db->qn('id').' = '.(int) $model->getState('event.id'));
				
					$this->_db->setQuery($query);
					$this->_db->execute();
					$moderated = 1;
				}
			}
			
			$this->setState('moderated', $moderated);
			return true;
		} else {
			$this->setError($model->getError());
			return false;
		}
	}
	
	/**
	 * Method to get save tickets configuration
	 *
	 * @return	array
	 */
	public function tickets() {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$input		= JFactory::getApplication()->input;
		$params		= $input->get('params',array(),'array');
		
		if (!empty($params)) {
			foreach ($params as $i => $param) {
				$registry = new JRegistry;
				$registry->loadArray($param);
				$position = $registry->toString();
				
				$query->clear()
					->update($db->qn('#__rseventspro_tickets'))
					->set($db->qn('position').' = '.$db->q($position))
					->where($db->qn('id').' = '.(int) $i);
				
				$db->setQuery($query);
				$db->execute();
			}
		}
	}
	
	/**
	 * Method to save the report
	 *
	 * @return	void
	 */
	public function report() {
		$db					= JFactory::getDbo();
		$query				= $db->getQuery(true);
		$jform				= JFactory::getApplication()->input->get('jform',array(),'array');
		$lang				= JFactory::getLanguage();
		$user				= JFactory::getUser();
		$config				= rseventsproHelper::getConfig();
		$additional_data	= array();
		$to					= array();
		
		$query->clear()
			->insert($db->qn('#__rseventspro_reports'))
			->set($db->qn('ide').' = '.(int) $jform['id'])
			->set($db->qn('idu').' = '.(int) $user->get('id'))
			->set($db->qn('ip').' = '.$db->q(rseventsproHelper::getIP()))
			->set($db->qn('text').' = '.$db->q($jform['report']));
		$db->setQuery($query);
		$db->execute();
		
		$additional_data = array(
				'{ReportUser}' => $user->get('guest') ? JText::_('COM_RSEVENTSPRO_GLOBAL_GUEST') : $user->get('name'),
				'{ReportIP}' => rseventsproHelper::getIP(),
				'{ReportMessage}' => $jform['report']
			);
		
		if ($config->report_to_owner) {
			$query->clear()
				->select($db->qn('u.email'))
				->from($db->qn('#__users','u'))
				->join('left',$db->qn('#__rseventspro_events','e').' ON '.$db->qn('u.id').' = '.$db->qn('e.owner'))
				->where($db->qn('e.id').' = '.(int) $jform['id']);
			$db->setQuery($query);
			if ($email = $db->loadResult()) {
				$to = array_merge($to,(array) $email);
			}
		}
		
		if ($config->report_to) {
			$report_to = explode(',',$config->report_to);
			$to = array_merge($to,$report_to);
		}
		
		// Send email
		rseventsproEmails::report($to, (int) $jform['id'], $additional_data, $lang->getTag());
	}
	
	// Can we report events ?
	public function getCanreport() {
		$config = rseventsproHelper::getConfig();
		$user	= JFactory::getUser();
		
		if ($config->reports) {
			if ($user->get('guest')) {
				if ($config->reports_guests)
					return true;
				else
					return false;
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Method to delete the reports.
	 */
	public function deletereports($pks) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->delete($db->qn('#__rseventspro_reports'))
			->where($db->qn('id').' IN ('.implode(',',$pks).')');
		$db->setQuery($query);
		$db->execute();
	}
	
	/**
	 * Method to confirm subscriber.
	 */
	public function confirm($id, $code) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$admin	= rseventsproHelper::admin();
		$user 	= $this->getUser();
		
		$query->select($db->qn('e.owner'))
			->select($db->qn('e.sid'))
			->from($db->qn('#__rseventspro_events','e'))
			->join('LEFT', $db->qn('#__rseventspro_users','u').' ON '.$db->qn('e.id').' = '.$db->qn('u.ide'))
			->where($db->qn('u.id').' = '.(int) $id);
		$db->setQuery($query);
		$event = $db->loadObject();
		
		if ($admin || $event->owner == $user || $event->sid == $user) {
			$query->clear()
				->select($db->qn('id'))
				->from('#__rseventspro_confirmed')
				->where($db->qn('ids').' = '.$db->q($id))
				->where($db->qn('code').' = '.$db->q($code));
			$db->setQuery($query);
			if (!$db->loadResult()) {
				$query->clear()
					->insert('#__rseventspro_confirmed')
					->set($db->qn('ids').' = '.$db->q($id))
					->set($db->qn('code').' = '.$db->q($code));
				$db->setQuery($query);
				if ($db->execute()) {
					return json_encode(array('status' => true, 'message' => JText::_('JYES')));
				}
			}
		}
		
		return json_encode(array('status' => false));
	}
	
	public function getFilterId() {
		$filters = $this->getFilters();
		$filters = serialize($filters);
		$input	 = JFactory::getApplication()->input;
		
		return md5($input->getInt('Itemid').$input->getInt('parent').$filters);
	}
	
	public function getMapItems() {
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/query.php';
		
		$params		= rseventsproHelper::getParams();
		$results	= (int) $params->get('display_results',1);
		$jinput		= JFactory::getApplication()->input;
		$return		= array();
		$having		= false;
		$select		= array('COUNT('.$this->_db->qn('e.id').') AS '.$this->_db->qn('eventsnr'), 'e.id',	'e.name', 'e.start', 'e.end', 'e.owner', 'e.allday', 'l.id' => 'lid', 'l.name' => 'lname', 'l.address', 'l.coordinates', 'l.marker');
		
		if (!is_null($jinput->getString('startpoint'))) {
			$coords = explode(',', $jinput->getString('startpoint'));
			$radius_start = array(
				'lat' => $coords[0],
				'lng' => $coords[1]
			);
			
			$unit = $jinput->getString('unit', 'km');
			if ($unit == 'km') {
				$unit_value = '6371';
			} else {
				$unit_value = '3959';
			}
			
			$having = "( {$unit_value} * acos( cos( radians({$radius_start['lat']}) ) * cos( radians( SUBSTRING_INDEX(".$this->_db->qn('l.coordinates').", ',', 1) ) ) * cos( radians( SUBSTRING_INDEX(".$this->_db->qn('l.coordinates').", ',', -1) ) - radians({$radius_start['lng']}) ) + sin( radians({$radius_start['lat']}) ) * sin( radians( SUBSTRING_INDEX(".$this->_db->qn('l.coordinates').", ',', 1) ) ) ) )";
		}
		
		$groupBy = array(($results ? 'e.id' : 'lid'), 'e.name', 'e.start', 'e.end', 'e.owner', 'e.allday', 'l.id' , 'l.name' , 'l.address', 'l.coordinates', 'l.marker');
		
		$query	= RSEventsProQuery::getInstance($params);
		
		$query->select($select);
		$query->featured(false);
		$query->userevents(false);
		
		if (isset($radius_start)) {
			$radius = $jinput->getInt('radius', 100);
			if ($radius > 0) {
				if ($having) {
					$query->having($having.' < '.$radius);
				}
			}
		}
		
		$query->where($this->_db->qn('l.coordinates').' <> '.$this->_db->q(''));
		$query->group($groupBy);
		$query->order('e.start');
		$query->direction('DESC');
		
		$query = $query->toString();
		
		$this->_db->setQuery($query);
		$events = $this->_db->loadObjectList();
		
		if (!empty($events)) {
			foreach ($events as $event) {
				if (!rseventsproHelper::canview($event->id) && $event->owner != $this->_user->get('id')) {
					continue;
				}
				
				$single = (int) $event->eventsnr > 1 ? false : true;
				
				$url = rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),false,rseventsproHelper::itemid($event->id));
				$src = rseventsproHelper::thumb($event->id, rseventsproHelper::getConfig('icon_small_width', 'int'));
				
				$image = '<a class="thumbnail" href="'.$url.'">';
				$image .= '<img class="media-object" src="'.$src.'" alt="" />';
				$image .= '</a>';
				
				$link = '<p><a href="'.$url.'">'.addslashes($event->name).'</a></p>';
				
				$return[] = array(
					'id' => $event->id,
					'coords' => $event->coordinates,
					'content' => rseventsproHelper::locationContent($event, $single, null, false),
					'image' => $image,
					'marker' => $event->marker ? rseventsproHelper::showMarker($event->marker) : null,
					'link' => $link,
					'address' => addslashes($event->address)
				);
			}
		}
		
		return $return;
	}
	
	public function ticketsorder() {
		$db		 = JFactory::getDbo();
		$query	 = $db->getQuery(true);
		$input	 = JFactory::getApplication()->input;
		$id		 = $input->getInt('id',0);
		$tickets = $input->get('ticket', array(), 'array');
		
		foreach ($tickets as $i => $ticket) {
			$query->clear()
				->update($db->qn('#__rseventspro_tickets'))
				->set($db->qn('order').' = '.$db->q($i))
				->where($db->qn('id').' = '.$db->q($ticket))
				->where($db->qn('ide').' = '.$db->q($id));
			$db->setQuery($query);
			$db->execute();
		}
	}
	
	// Get tag details
	public function getEventTag() {
		$doc		= JFactory::getDocument();
		$query		= $this->_db->getQuery(true);
		$config		= JFactory::getConfig();
		$tag		= 0;
		$count		= 0;
		
		list($columns, $operators, $values) = $this->_filters;
		
		for ($i=0; $i<count($columns); $i++) {
			$column 	= $columns[$i];
			$operator	= $operators[$i];
			$value 		= $values[$i];
			
			if ($column == 'tags') {
				if ($operator == 'is') {
					$query->clear()
						->select($this->_db->qn('id'))
						->from($this->_db->qn('#__rseventspro_tags'))
						->where($this->_db->qn('name').' = '.$this->_db->q($value));
					
					$this->_db->setQuery($query);
					$tag = (int) $this->_db->loadResult();
				}
				$count++;
			}
		}
		
		// Search the tags within the params
		if (empty($count) && empty($tag)) {
			$params 	= rseventsproHelper::getParams();
			if ($ptags = $params->get('tags','')) {
				foreach ($ptags as $ptag) {
					$tag = (int) $ptag;
					$count++;
				}
			}
		}
		
		// Get Tag details
		if ($count == 1 && $tag > 0) {
			$query->clear()
				->select($this->_db->qn('name'))
				->from($this->_db->qn('#__rseventspro_tags'))
				->where($this->_db->qn('id').' = '.$this->_db->q($tag));
			$this->_db->setQuery($query);
			return $this->_db->loadResult();
		}
		
		return false;
	}
	
	// Get location details
	public function getEventLocation() {
		$doc		= JFactory::getDocument();
		$query		= $this->_db->getQuery(true);
		$config		= JFactory::getConfig();
		$location	= 0;
		$count		= 0;
		
		list($columns, $operators, $values) = $this->_filters;
		
		for ($i=0; $i<count($columns); $i++) {
			$column 	= $columns[$i];
			$operator	= $operators[$i];
			$value 		= $values[$i];
			
			if ($column == 'locations') {
				if ($operator == 'is') {
					$query->clear()
						->select($this->_db->qn('id'))
						->from($this->_db->qn('#__rseventspro_locations'))
						->where($this->_db->qn('name').' = '.$this->_db->q($value));
					
					$this->_db->setQuery($query);
					$location = (int) $this->_db->loadResult();
				}
				$count++;
			}
		}
		
		// Search the locations within the params
		if (empty($count) && empty($location)) {
			$params 	= rseventsproHelper::getParams();
			if ($ptags = $params->get('locations','')) {
				foreach ($ptags as $ptag) {
					$location = (int) $ptag;
					$count++;
				}
			}
		}
		
		// Get Location details
		if ($count == 1 && $location > 0) {
			$query->clear()
				->select($this->_db->qn('name'))
				->from($this->_db->qn('#__rseventspro_locations'))
				->where($this->_db->qn('id').' = '.$this->_db->q($location));
			$this->_db->setQuery($query);
			return $this->_db->loadResult();
		}
		
		return false;
	}
	
	public function getMaxPrice() {
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/query.php';
		
		$db		= JFactory::getDbo();
		$params = rseventsproHelper::getParams();
		$query	= RSEventsProQuery::getInstance($params);
		
		$query->group('e.id');
		$query->price(false);
		
		$db->setQuery('SELECT MAX('.$db->qn('price').') FROM '.$db->qn('#__rseventspro_tickets').' WHERE '.$db->qn('ide').' IN ( SELECT * FROM ('.$query->toString().') AS subquery)');
		return round($db->loadResult());
	}
	
	public function saveuser($data) {
		$table	= JTable::getInstance('User','RseventsproTable');
		
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}
		
		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}
		
		// Upload the image
		$table->uploadImage();
		
		return true;
	}
	
	public function deleteimage() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$id		= JFactory::getApplication()->input->getInt('id',0);
		$path	= JPATH_SITE.'/components/com_rseventspro/assets/images/users/';
		
		$query->select($db->qn('image'))
			->from($db->qn('#__rseventspro_user_info'))
			->where($db->qn('id').' = '.$id);
		$db->setQuery($query);
		if ($image = $db->loadResult()) {
			jimport('joomla.filesystem.file');
			
			if (file_exists($path.$image)) {
				if (JFile::delete($path.$image)) {
					$query->clear()
						->update($db->qn('#__rseventspro_user_info'))
						->set($db->qn('image').' = '.$db->q(''))
						->where($db->qn('id').' = '.$id);
					$db->setQuery($query);
					$db->execute();
					
					return true;
				}
			}
			
			return false;
		}
		
		return false;
	}
	
	// Remove subscription
	public function deletesubscriber() {
		$from	= $this->_app->input->get('from','');
		$id		= $this->_app->input->getInt('id');
		$user  	= $this->getUser();
		
		if ($from == 'rsvp') {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->clear()
				->select($db->qn('r.uid'))->select($db->qn('u.email'))
				->from($db->qn('#__rseventspro_rsvp_users','r'))
				->join('LEFT', $db->qn('#__users','u').' ON '.$db->qn('r.uid').' = '.$db->qn('u.id'))
				->where($db->qn('r.id').' = '.$db->q($id));
			$db->setQuery($query);
			if ($subscriber = $db->loadObject()) {
				if ($subscriber->uid == $user || $subscriber->email == $this->getEmailFromCode()) {
					$this->removersvp();
					return true;
				}
			}
			
			return false;
		} else {
			$table	= JTable::getInstance('Subscription','RseventsproTable');
			$table->load($id);
			
			if ($table->idu == $user || $table->email == $this->getEmailFromCode()) {
				if (!$table->delete($id)) {
					$this->setError($table->getError());
					return false;
				}
			} else {
				$this->setError(JText::_('COM_RSEVENTSPRO_ERROR_SUBSCRIBER_DELETE'));
				return false;
			}
			
			return true;
		}
	}
	
	public function getShowForm() {
		$email = $this->getEmailFromCode();
		
		if (JFactory::getUser()->get('guest')) {
			if ($email) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	public function getEmailFromCode() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$code	= JFactory::getApplication()->input->getString('code');
		$email	= false;
		
		$query->clear()
			->select($db->qn('email'))
			->from($db->qn('#__rseventspro_users'))
			->where('MD5(CONCAT('.$db->qn('date').','.$db->qn('id').','.$db->qn('email').','.$db->qn('verification').')) = '.$db->q($code));
		$db->setQuery($query);
		$email = $db->loadResult();
		
		if (!$email) {
			$query->clear()
				->select($db->qn('u.email'))
				->from($db->qn('#__rseventspro_rsvp_users','r'))
				->join('LEFT', $db->qn('#__users','u').' ON '.$db->qn('r.uid').' = '.$db->qn('u.id'))
				->where('MD5(CONCAT('.$db->qn('r.date').','.$db->qn('r.id').','.$db->qn('u.email').')) = '.$db->q($code));
			$db->setQuery($query);
			$email = $db->loadResult();
		}
		
		return $email;
	}
	
	// Get RSVP event guests
	public function getRSVPGuests() {
		$id		= $this->_app->input->getInt('id');
		$query	= $this->_db->getQuery(true);
		$return	= array();
		
		$query->clear()
			->select('r.*')
			->select($this->_db->qn('u.name'))->select($this->_db->qn('u.email'))
			->from($this->_db->qn('#__rseventspro_rsvp_users','r'))
			->join('LEFT', $this->_db->qn('#__users','u').' ON '.$this->_db->qn('r.uid').' = '.$this->_db->qn('u.id'))
			->where($this->_db->qn('r.ide').' = '.$id);
		
		$this->_db->setQuery($query);
		if ($guests = $this->_db->loadObjectList()) {
			foreach ($guests as $guest) {
				$object = new stdClass();
				
				// Already logged in?
				if ($guest->uid) {
					$object->name = rseventsproHelper::getUser($guest->uid, 'guest', $guest->name);
				} else {
					$object->name = $guest->name;
				}
				
				$object->url	= !empty($guest->uid) ? rseventsproHelper::getProfile('guests', $guest->uid) : '';
				$object->avatar = rseventsproHelper::getAvatar($guest->uid,$guest->email);
				$return[$guest->rsvp][] = $object;
			}
		}
		
		asort($return);
		return $return;
	}
	
	public function getRSVPData() {
		$this->_db->setQuery($this->_rsvpquery,$this->getState('com_rseventspro.limitstart'), $this->getState('com_rseventspro.limit'));
		return $this->_db->loadObjectList();
	}
	
	public function getRSVPTotal() {
		return $this->getCount($this->_rsvpquery);
	}
	
	public function rsvp($id, $rsvp) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->update($db->qn('#__rseventspro_rsvp_users'))
			->set($db->qn('rsvp').' = '.$db->q($rsvp))
			->where($db->qn('id').' = '.$db->q($id));
		$db->setQuery($query);
		$db->execute();
		
		return true;
	}
	
	public function removersvp() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$id		= $this->_app->input->getInt('id');
		
		$query->clear()
			->delete($db->qn('#__rseventspro_rsvp_users'))
			->where($db->qn('id').' = '.$db->q($id));
		$db->setQuery($query);
		$db->execute();
		
		return true;
	}
	
	public function exportrsvpguests() {
		rseventsproHelper::exportRSVPCSV($this->_rsvpquery);
	}
	
	public function getRSVPSubscriptions() {
		$query		= $this->_db->getQuery(true);
		$params		= rseventsproHelper::getParams();
		$past		= (int) $params->get('past',1);
		$archived	= (int) $params->get('archived',1);
		$code		= JFactory::getApplication()->input->getString('code');
		$showform	= $this->getShowForm();
		
		$query->clear()
			->select('r.*')->select($this->_db->qn('u.name','uname'))->select($this->_db->qn('e.name'))
			->select($this->_db->qn('e.start'))->select($this->_db->qn('e.end'))
			->from($this->_db->qn('#__rseventspro_rsvp_users','r'))
			->join('left',$this->_db->qn('#__users','u').' ON '.$this->_db->qn('r.uid').' = '.$this->_db->qn('u.id'))
			->join('left',$this->_db->qn('#__rseventspro_events','e').' ON '.$this->_db->qn('e.id').' = '.$this->_db->qn('r.ide'))
			->where($this->_db->qn('e.completed').' = 1');
		
		if (!$showform && $code) {
			$email = $this->getEmailFromCode();
			$query->where($this->_db->qn('u.email').' = '.$this->_db->q($email));
		} else {
			$query->where($this->_db->qn('u.email').' = '.$this->_db->q($this->_user->get('email')));
		}
		
		if (!$archived) {
			$query->where($this->_db->qn('e.published').' = 1');
		}
		
		if (!$past) {
			$query->where($this->_db->qn('e.end').' > '.$this->_db->q(JFactory::getDate()->toSql()));
		}
		
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	public function savespeaker() {
		$table	= JTable::getInstance('Speaker','RseventsproTable');
		$data	= $this->_app->input->get('jform',array(),'array');
		$data['published'] = 1;
		
		$table->save($data);
		$table->uploadImage();
		
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/events.php';
		$event = RSEvent::getInstance(0);
		
		return $event->speakers();
	}
	
	public function getRSVPstatuses() {
		return array(JHTML::_('select.option', 'going', JText::_('COM_RSEVENTSPRO_RSVP_GOING')), 
			JHTML::_('select.option', 'interested', JText::_('COM_RSEVENTSPRO_RSVP_INTERESTED')), 
			JHTML::_('select.option', 'notgoing', JText::_('COM_RSEVENTSPRO_RSVP_NOT_GOING'))
		);
	}
	
	public function getStatuses() {
		return array(JHTML::_('select.option', 0, JText::_('COM_RSEVENTSPRO_GLOBAL_STATUS_INCOMPLETE')), 
			JHTML::_('select.option', 1, JText::_('COM_RSEVENTSPRO_GLOBAL_STATUS_COMPLETED')), 
			JHTML::_('select.option', 2, JText::_('COM_RSEVENTSPRO_GLOBAL_STATUS_DENIED'))
		);
	}
	
	public function getFilterOptions() { 
		return array(JHTML::_('select.option', 'events', JText::_('COM_RSEVENTSPRO_FILTER_NAME')), JHTML::_('select.option', 'description', JText::_('COM_RSEVENTSPRO_FILTER_DESCRIPTION')), 
			JHTML::_('select.option', 'locations', JText::_('COM_RSEVENTSPRO_FILTER_LOCATION')) ,JHTML::_('select.option', 'categories', JText::_('COM_RSEVENTSPRO_FILTER_CATEGORY')),
			JHTML::_('select.option', 'tags', JText::_('COM_RSEVENTSPRO_FILTER_TAG')), JHTML::_('select.option', 'featured', JText::_('COM_RSEVENTSPRO_FILTER_FEATURED')), 
			JHTML::_('select.option', 'price', JText::_('COM_RSEVENTSPRO_FILTER_PRICE'))
		);
	}
	
	public function getFilterConditions() {
		return array(JHTML::_('select.option', 'is', JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_IS')), JHTML::_('select.option', 'isnot', JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_ISNOT')),
			JHTML::_('select.option', 'contains', JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_CONTAINS')),JHTML::_('select.option', 'notcontain', JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_NOTCONTAINS'))
		);
	}
	
	public function getYesNo() {
		return array(JHTML::_('select.option', 1, JText::_('JYES')), JHTML::_('select.option', 0, JText::_('JNO')));
	}
}