<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modRseventsProEvents {

	public static function getEvents($params) {
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/query.php';

		$db		= JFactory::getDbo();
		$limit	= (int) $params->get('limit',5);
		
		modRseventsProEvents::legacy($params);
		$query	= RSEventsQuery::getInstance($params);
		$query->group('e.id');
		$query->userevents(false);
		
		$query = $query->toString();
		
		$db->setQuery($query, 0, $limit);
		return $db->loadObjectList();
	}
	
	protected static function legacy(&$params) {
		$type	= (int) $params->get('type',11);
		$repeat	= (int) $params->get('child',1);
		$list	= 'ongoing';
		
		if ($type == 1) $list = 'past';
		elseif ($type == 2) $list = 'today';
		elseif ($type == 3) $list = 'archived';
		elseif ($type == 4) $list = 'thisweek';
		elseif ($type == 5) $list = 'nextweek';
		elseif ($type == 6) $list = 'thisweekend';
		elseif ($type == 7) $list = 'nextweekend';
		elseif ($type == 8) $list = 'thismonth';
		elseif ($type == 9) $list = 'nextmonth';
		elseif ($type == 10) $list = 'upcoming';
		elseif ($type == 11) $list = 'ongoing';
		elseif ($type == 12) $list = 'timeframe';
		
		$params->set('list', $list);
		$params->set('repeat', $repeat);
	}
}