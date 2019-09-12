<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin');

class plgContentRSEventspro extends JPlugin
{
	public function plgContentRSComments(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	/**
	 * Plugin that loads RSEvents!Pro events within content
	 *
	 * @param   string	The context of the content being passed to the plugin.
	 * @param   object	The article object.  Note $article->text is also available
	 * @param   object	The article params
	 * @param   integer  The 'page' number
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0) {
		
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}
		
		// simple performance check to determine whether bot should process further
		if (strpos($article->text, '{rseventspro') === false) {
			return true;
		}
		
		// Can we run the plugin
		if (!$this->canRun()) {
			return true;
		}
		
		$itemid = $this->params->get('itemid',0);
		
		// Expl: {rseventspro type="past" start="2015-06-21" end="2015-06-29" archived="0" childs="1" categories="Events,Art" locations="Location 1,Location 2" tags="tag1,tag2,tag3"} or {rseventspro id="4"}
		// expression to search for events
		$regex		= '#{rseventspro(.*?)}#is';
		$pattern	= '#\s+?(.*?)=["|\'](.*?)["|\']#is';
		
		// Find all instances of plugin and put in $matches for events
		// $matches[0] is full pattern match, $matches[1] is the event id
		preg_match_all($regex, $article->text, $eventmatches);
		
		if (isset($eventmatches) && isset($eventmatches[1])) {
			foreach ($eventmatches[1] as $i => $match) {
				if (!empty($match)) {
					JFactory::getLanguage()->load('com_rseventspro', JPATH_SITE);
					JFactory::getLanguage()->load('com_rseventspro.dates', JPATH_SITE);
					$string = $match;
					preg_match_all($pattern,$string,$options);
					
					if (!empty($options) && !empty($options[1])) {
						// If we find an id in the options array , then we show that event
						$keys = isset($options[1]) ? $options[1] : array();
						$values = isset($options[2]) ? $options[2] : array();
						
						$key_id			= array_search('id',$keys);
						$key_type		= array_search('type',$keys);
						$key_start		= array_search('start',$keys);
						$key_end		= array_search('end',$keys);
						$key_archived	= array_search('archived',$keys);
						$key_childs		= array_search('childs',$keys);
						$key_categories = array_search('categories',$keys);
						$key_locations 	= array_search('locations',$keys);
						$key_tags		= array_search('tags',$keys);
						$key_ordering	= array_search('ordering',$keys);
						$key_order		= array_search('order',$keys);
						$key_limit		= array_search('limit',$keys);
						
						if ($key_id !== false) {
							$eventID = isset($values[$key_id]) ? $values[$key_id] : 0;
							if ($eventID) {
								$eitemid = rseventsproHelper::itemid($eventID);
								$itemid	 = empty($eitemid) ? $itemid : $eitemid;
								$output	 = rseventsproHelper::event($eventID, $itemid);
								// Replace placeholders
								$article->text = str_replace($eventmatches[0][$i], $output, $article->text);
							}
						} else {
							$ordering = $key_ordering !== false && isset($values[$key_ordering]) ? strtolower($values[$key_ordering]) : null;
							if ($ordering) {
								if (in_array(strtolower($ordering), array('start','name'))) {
									$ordering = strtolower($ordering);
								} else {
									$ordering = $this->params->get('ordering','start');
								}
							} else {
								$ordering = $this->params->get('ordering','start');
							}
							
							$order = $key_order !== false && isset($values[$key_order]) ? strtoupper($values[$key_order]) : null;
							if ($order) {
								if (in_array($order, array('ASC','DESC'))) {
									$order = $order;
								} else {
									$order = $this->params->get('order','DESC');
								}
							} else {
								$order = $this->params->get('order','DESC');
							}
							
							$limit = $key_limit !== false && isset($values[$key_limit]) ? (int) $values[$key_limit] : (int) $this->params->get('limit','5');
							
							$eparams = new JRegistry;
							$eparams->set('ordering', $ordering);
							$eparams->set('order', $order);
							$eparams->set('limit', $limit);
							$eparams->set('type', $key_type !== false && isset($values[$key_type]) ? strtolower($values[$key_type]) : null);
							$eparams->set('archived', $key_archived !== false && isset($values[$key_archived]) ? $values[$key_archived] : null);
							$eparams->set('child', $key_childs !== false && isset($values[$key_childs]) ? $values[$key_childs] : null);
							$eparams->set('from', $key_start !== false && isset($values[$key_start]) ? $values[$key_start] : null);
							$eparams->set('to', $key_end !== false && isset($values[$key_end]) ? $values[$key_end] : null);
							$eparams->set('categories', $key_categories !== false ? $this->getIDs('categories', $values[$key_categories]) : null);
							$eparams->set('locations', $key_locations !== false ? $this->getIDs('locations',$values[$key_locations]) : null);
							$eparams->set('tags', $key_tags !== false ? $this->getIDs('tags',$values[$key_tags]) : null);
							
							$output = '';
							if ($events = $this->getEvents($eparams)) {
								foreach ($events as $eventID) {
									$eitemid = rseventsproHelper::itemid($eventID);
									$itemid	 = empty($eitemid) ? $itemid : $eitemid;
									$output .= rseventsproHelper::event($eventID, $itemid);
								}
							}
							
							// Replace placeholders
							$article->text = str_replace($eventmatches[0][$i], $output, $article->text);
						}
					}
				}
			}
		}
	}
	
	protected function getEvents($params) {
		$db			= JFactory::getDbo();
		$now		= rseventsproHelper::showdate('now','Y-m-d H:i:s');
		$query		= $db->getQuery(true);
		$subquery	= $db->getQuery(true);
		
		$order		= $params->get('ordering','start');
		$direction	= $params->get('order','DESC');
		$type		= strtolower($params->get('type',''));
		$archived	= (int) $params->get('archived',0);
		$repeating	= (int) $params->get('child',1);
		$limit		= (int) $params->get('limit',5);
		
		$from		= $params->get('from','');
		$to			= $params->get('to','');
		$categories	= $params->get('categories','');
		$locations	= $params->get('locations','');
		$tags		= $params->get('tags','');
		
		if (!in_array($type,array('past','today','archived','thisweek','nextweek','thisweekend','nextweekend','thismonth','nextmonth','upcoming','ongoing','timeframe')) || empty($type)) {
			return;
		}
		
		$where		= '';
		$offset		= $this->offset();
		$allday		= $this->allday($params);
		
		$todayDate = JFactory::getDate();
		$todayDate->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
		$todayDate->setTime(0,0,0);
		$today = $todayDate->toSql();
		$todayDate->modify('+1 days');
		$tomorrow = $todayDate->toSql();
		
		$query->clear()
			->select($db->qn('e.id'))
			->from($db->qn('#__rseventspro_events','e'))
			->where($db->qn('e.completed').' = 1');
		
		// Include archived events
		if ($type != 'archived') {
			if ($archived) {
				$query->where($db->qn('e.published').' IN (1,2)');
			} else {
				$query->where($db->qn('e.published').' = 1');
			}
		}
		
		// Exclude child events
		if (!$repeating) {
			$query->where($db->qn('e.parent').' = 0');
		}
		
		// Exclude for now All day events
		$query->where($db->qn('e.end').' <> '.$db->q($db->getNullDate()));
		
		if ($type == 'past') {
			// Past events
			$where = 'DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) < '.$db->q($now);
		} elseif ($type == 'today') {
			// Today events
			$where = '((DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($today).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) >= '.$db->q($today).') OR (DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($today).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) < '.$db->q($tomorrow).'))';
		} elseif ($type == 'archived') {
			// Archived events
			$where = $db->qn('e.published').' = 2';
		} elseif ($type == 'thisweek') {
			// This week
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->modify('this sunday');
			$date->setTime(23,59,59);
			$endofweek = $date->toSql();
			
			$where = '((DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($now).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) >= '.$db->q($now).') OR (DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($now).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) < '.$db->q($endofweek).'))';
		} elseif ($type == 'nextweek') {
			// Next week
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->modify('next monday');
			$date->setTime(0,0,0);
			$startofnextweek = $date->toSql();
			$date->modify('this sunday');
			$date->setTime(23,59,59);
			$endofnextweek = $date->toSql();
			
			$where = '((DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($startofnextweek).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) >= '.$db->q($startofnextweek).') OR (DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($startofnextweek).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) < '.$db->q($endofnextweek).'))';
		} elseif ($type == 'thisweekend') {
			// This weekend
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->modify('this saturday');
			$date->setTime(0,0,0);
			$startweekend = $date->toSql();
			$date->modify('this sunday');
			$date->setTime(23,59,59);
			$endweekend = $date->toSql();
			
			$where = '((DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($startweekend).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) >= '.$db->q($startweekend).') OR (DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($startweekend).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) < '.$db->q($endweekend).'))';
		} elseif ($type == 'nextweekend') {
			// Next weekend
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->modify('next monday');
			$date->modify('this saturday');
			$date->setTime(0,0,0);
			$startnextweekend = $date->toSql();
			$date->modify('this sunday');
			$date->setTime(23,59,59);
			$endnextweekend = $date->toSql();
			
			$where = '((DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($startnextweekend).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) >= '.$db->q($startnextweekend).') OR (DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($startnextweekend).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) < '.$db->q($endnextweekend).'))';
		} elseif ($type == 'thismonth') {
			// This month
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->setTime(23,59,59);
			$endofmonth = $date->format('Y-m-t H:i:s');
			
			$where = '((DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($now).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) >= '.$db->q($now).') OR (DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($now).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) < '.$db->q($endofmonth).'))';
		} elseif ($type == 'nextmonth') {
			// Next month
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->modify('first day of next month');
			$date->setTime(0,0,0);
			$start = $date->toSql();
			$date->setTime(23,59,59);
			$end = $date->format('Y-m-t H:i:s');
			
			$where = '((DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($start).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) >= '.$db->q($start).') OR (DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($start).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) < '.$db->q($end).'))';
		} elseif ($type == 'upcoming') {
			// Upcoming
			$where = 'DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($now);
		} elseif ($type == 'ongoing') {
			// Ongoing
			$where = 'DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($now).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) >= '.$db->q($now);
		}
		
		// From and to
		if (in_array($type,array('past','archived','thismonth','nextmonth','upcoming','timeframe'))) {
			$timeframe = null;
			
			if (!empty($from) && $from != $db->getNullDate()) {
				$from = rseventsproHelper::showdate($from,'Y-m-d H:i:s');
			}
			
			if (!empty($to) && $to != $db->getNullDate()) {
				$to = rseventsproHelper::showdate($to,'Y-m-d H:i:s');
			}
			
			if (empty($from) && !empty($to)) {
				$timeframe = 'DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) <= '.$db->q($to);
			} elseif (!empty($from) && empty($to)) {
				$timeframe = 'DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($from);
			} elseif (!empty($from) && !empty($to)) {
				$timeframe = '((DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($from).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) >= '.$db->q($to).') OR (DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($from).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) <= '.$db->q($to).'))';
			}
			
			if ($timeframe) {
				if ($where) {
					$where = '(('.$where.') AND ('.$timeframe.'))';
				} else {
					$where = $timeframe;
				}
			}
		}
		
		if ($where) {
			$query->where($where);
		}
		
		// Filter by categories
		if (!empty($categories)) {
			array_map('intval',$categories);
			
			$subquery->clear()
				->select($db->qn('tx.ide'))
				->from($db->qn('#__rseventspro_taxonomy','tx'))
				->join('left', $db->qn('#__categories','c').' ON '.$db->qn('c.id').' = '.$db->qn('tx.id'))
				->where($db->qn('c.id').' IN ('.implode(',',$categories).')')
				->where($db->qn('tx.type').' = '.$db->q('category'))
				->where($db->qn('c.extension').' = '.$db->q('com_rseventspro'));
			
			if (JLanguageMultilang::isEnabled()) {
				$subquery->where('c.language IN ('.$db->q(JFactory::getLanguage()->getTag()).','.$db->q('*').')');
			}
			
			$user	= JFactory::getUser();
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$subquery->where('c.access IN ('.$groups.')');
			
			$query->where($db->qn('e.id').' IN ('.$subquery.')');
		}
		
		// Filter by tags
		if (!empty($tags)) {
			array_map('intval',$tags);
			
			$subquery->clear()
				->select($db->qn('tx.ide'))
				->from($db->qn('#__rseventspro_taxonomy','tx'))
				->join('left', $db->qn('#__rseventspro_tags','t').' ON '.$db->qn('t.id').' = '.$db->qn('tx.id'))
				->where($db->qn('t.id').' IN ('.implode(',',$tags).')')
				->where($db->qn('tx.type').' = '.$db->q('tag'));
			
			$query->where($db->qn('e.id').' IN ('.$subquery.')');
		}
		
		// Filter by locations
		if (!empty($locations)) {
			array_map('intval',$locations);
			
			$query->where($db->qn('e.location').' IN ('.implode(',',$locations).')');
		}
		
		// Exclude events that are not visible for the current user
		$exclude = $this->excludeEvents();
		if (!empty($exclude)) {
			$query->where($db->qn('e.id').' NOT IN ('.implode(',',$exclude).')');
		}
		
		$query->orWhere($db->qn('e.id').' IN ('.$allday.')');
		
		// Order events
		$query->order($db->qn('e.'.$order).' '.$db->escape($direction));
		
		$db->setQuery($query,0,$limit);
		$events = $db->loadColumn();
		
		array_map('intval',$events);
		
		return $events;
	}
	
	protected function excludeEvents() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser(); 
		$ids	= array();
		
		$query->clear()
			->select($db->qn('ide'))
			->from($db->qn('#__rseventspro_taxonomy'))
			->where($db->qn('type').' = '.$db->q('groups'));
		
		$db->setQuery($query);
		$eventids = $db->loadColumn();
		
		if (!empty($eventids)) {
			foreach ($eventids as $id) {
				$query->clear()
					->select($db->qn('owner'))
					->from($db->qn('#__rseventspro_events'))
					->where($db->qn('id').' = '.(int) $id);
				
				$db->setQuery($query);
				$owner = (int) $db->loadResult();
				
				if (!rseventsproHelper::canview($id) && $owner != $user->get('id'))
					$ids[] = $id;
			}
			
			if (!empty($ids)) {
				array_map('intval',$ids);
				$ids = array_unique($ids);
			}
		}
		
		return $ids;
	}
	
	protected function allday($params) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$subquery	= $db->getQuery(true);
		$now		= rseventsproHelper::showdate('now', 'Y-m-d H:i:s');
		
		$type		= $params->get('type','past');
		$archived	= (int) $params->get('archived',0);
		$repeating	= (int) $params->get('child',1);
		
		$from		= $params->get('from','');
		$to			= $params->get('to','');
		$categories	= $params->get('categories','');
		$locations	= $params->get('locations','');
		$tags		= $params->get('tags','');
		
		$offset 	= $this->offset();
		
		$query->clear()
			->select($db->qn('e.id'))
			->from($db->qn('#__rseventspro_events','e'))
			->where($db->qn('e.allday').' = 1');
		
		// Include archived events
		if ($type != 'archived') {
			if ($archived) {
				$query->where($db->qn('e.published').' IN (1,2)');
			} else {
				$query->where($db->qn('e.published').' = 1');
			}
		}
		
		// Exclude child events
		if (!$repeating) {
			$query->where($db->qn('e.parent').' = 0');
		}
		
		$todayUTC = JFactory::getDate();
		$todayUTC->setTime(0,0,0);
		$todayUTC = $todayUTC->format('Y-m-d H:i:s');
		
		$today = JFactory::getDate();
		$today->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
		$today->setTime(0,0,0);
		$today = $today->format('Y-m-d H:i:s');
		
		if ($type == 'past') {
			// Past events
			$query->where('('.$db->qn('e.start').' < '.$db->q($today).' AND '.$db->qn('e.start').' < '.$db->q($todayUTC).')');
		} elseif ($type == 'today') {
			// Today events
			$query->where('('.$db->qn('e.start').' = '.$db->q($today).' OR '.$db->qn('e.start').' = '.$db->q($todayUTC).')');
		} elseif ($type == 'archived') {
			// Archived
			$where = $db->qn('e.published').' = 2';
		} elseif ($type == 'thisweek') {
			// This week
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->modify('this sunday');
			$date->setTime(23,59,59);
			$endofweek = $date->toSql();
			
			$tomorrow = JFactory::getDate();
			$tomorrow->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$tomorrow->modify('+1 days');
			$tomorrow->setTime(0,0,0);
			
			$query->where('DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($tomorrow->toSql()).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($endofweek));
		} elseif ($type == 'nextweek') {
			// Next week
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->modify('next monday');
			$date->setTime(0,0,0);
			$startofnextweek = $date->toSql();
			$date->modify('this sunday');
			$date->setTime(23,59,59);
			$endofnextweek = $date->toSql();
			
			$query->where('DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($startofnextweek).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($endofnextweek));
		} elseif ($type == 'thisweekend') {
			// This weekend
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->modify('this saturday');
			$date->setTime(0,0,0);
			$startweekend = $date->toSql();
			$date->modify('this sunday');
			$date->setTime(23,59,59);
			$endweekend = $date->toSql();
			
			$query->where('DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($startweekend).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($endweekend));
		} elseif ($type == 'nextweekend') {
			// Next weekend
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->modify('next monday');
			$date->modify('this saturday');
			$date->setTime(0,0,0);
			$startnextweekend = $date->toSql();
			$date->modify('this sunday');
			$date->setTime(23,59,59);
			$endnextweekend = $date->toSql();
			
			$query->where('DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($startnextweekend).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($endnextweekend));
		} elseif ($type == 'thismonth') {
			// This month
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->setTime(23,59,59);
			$endofmonth = $date->format('Y-m-t H:i:s');
			
			$query->where('DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($now).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($endofmonth));
		} elseif ($type == 'nextmonth') {
			// Next month
			$date = JFactory::getDate();
			$date->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
			$date->modify('first day of next month');
			$date->setTime(0,0,0);
			$start = $date->toSql();
			$date->setTime(23,59,59);
			$end = $date->format('Y-m-t H:i:s');
			
			$query->where('DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($start).' AND DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($end));
		} elseif ($type == 'upcoming') {
			// Upcoming
			$query->where('DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($now));
		} elseif ($type == 'ongoing') {
			// Ongoing
			$query->where('('.$db->qn('e.start').' = '.$db->q($today).' OR '.$db->qn('e.start').' = '.$db->q($todayUTC).')');
		}
		
		// From and to
		if (in_array($type,array('past','archived','thismonth','nextmonth','upcoming','timeframe'))) {
			if (!empty($from) && $from != $db->getNullDate()) {
				$from = rseventsproHelper::showdate($from,'Y-m-d H:i:s');
			}
			
			if (!empty($to) && $to != $db->getNullDate()) {
				$to = rseventsproHelper::showdate($to,'Y-m-d H:i:s');
			}
			
			if (empty($from) && !empty($to)) {
				$timeframe = 'DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) <= '.$db->q($to);
			} elseif (!empty($from) && empty($to)) {
				$timeframe = 'DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($from);
			} elseif (!empty($from) && !empty($to)) {
				$timeframe = '((DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) <= '.$db->q($from).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) >= '.$db->q($to).') OR (DATE_ADD('.$db->qn('e.start').', INTERVAL '.$offset.' SECOND) >= '.$db->q($from).' AND DATE_ADD('.$db->qn('e.end').', INTERVAL '.$offset.' SECOND) <= '.$db->q($to).'))';
			}
		}
		
		// Filter by categories
		if (!empty($categories)) {
			array_map('intval',$categories);
			
			$subquery->clear()
				->select($db->qn('tx.ide'))
				->from($db->qn('#__rseventspro_taxonomy','tx'))
				->join('left', $db->qn('#__categories','c').' ON '.$db->qn('c.id').' = '.$db->qn('tx.id'))
				->where($db->qn('c.id').' IN ('.implode(',',$categories).')')
				->where($db->qn('tx.type').' = '.$db->q('category'))
				->where($db->qn('c.extension').' = '.$db->q('com_rseventspro'));
			
			if (JLanguageMultilang::isEnabled()) {
				$subquery->where('c.language IN ('.$db->q(JFactory::getLanguage()->getTag()).','.$db->q('*').')');
			}
			
			$user	= JFactory::getUser();
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$subquery->where('c.access IN ('.$groups.')');
			
			$query->where($db->qn('e.id').' IN ('.$subquery.')');
		}
		
		// Filter by tags
		if (!empty($tags)) {
			array_map('intval',$tags);
			
			$subquery->clear()
				->select($db->qn('tx.ide'))
				->from($db->qn('#__rseventspro_taxonomy','tx'))
				->join('left', $db->qn('#__rseventspro_tags','t').' ON '.$db->qn('t.id').' = '.$db->qn('tx.id'))
				->where($db->qn('t.id').' IN ('.implode(',',$tags).')')
				->where($db->qn('tx.type').' = '.$db->q('tag'));
			
			$query->where($db->qn('e.id').' IN ('.$subquery.')');
		}
		
		// Filter by locations
		if (!empty($locations)) {
			array_map('intval',$locations);
			
			$query->where($db->qn('e.location').' IN ('.implode(',',$locations).')');
		}
		
		// Exclude events that are not visible for the current user
		$exclude = $this->excludeEvents();
		if (!empty($exclude)) {
			$query->where($db->qn('e.id').' NOT IN ('.implode(',',$exclude).')');
		}
		
		return $query;
	}
	
	protected function offset() {
		$timezone = new DateTimeZone(rseventsproHelper::getTimezone());
		return $timezone->getOffset(new DateTime('now', new DateTimeZone('UTC')));
	}
	
	protected function getIDs($type, $values) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$ids	= array();
		
		if ($values = explode(',',$values)) {
			foreach ($values as $value) {
				$query->clear()->select($db->qn('id'));
				
				if ($type == 'categories') {
					$query->from($db->qn('#__categories'))
						->where($db->qn('extension').' = '.$db->q('com_rseventspro'))
						->where($db->qn('title').' = '.$db->q($value));
				} elseif ($type == 'locations') {
					$query->from($db->qn('#__rseventspro_locations'))->where($db->qn('name').' = '.$db->q($value));
				} elseif ($type == 'tags') {
					$query->from($db->qn('#__rseventspro_tags'))->where($db->qn('name').' = '.$db->q($value));
				}
				
				$db->setQuery($query);
				if ($ID = (int) $db->loadResult()) {
					$ids[] = $ID;
				}
			}
		}
		
		return !empty($ids) ? $ids : null;
	}
	
	protected function canRun() {
		if (file_exists(JPATH_SITE.'/components/com_rseventspro/rseventspro.php')) {
			require_once JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php';
			
			return true;
		}
		
		return false;
	}
}