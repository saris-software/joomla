<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modRseventsProCalendar {

	public static function getObject($params) {
		// Get a new instance of the calendar
		$calendar = new RSEPROCalendar(modRseventsProCalendar::getEvents($params), $params, true);
		$calendar->class_suffix = $params->get('moduleclass_sfx','');
		
		return $calendar;
	}

	public static function getEvents($params) {
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/query.php';
		
		$db		= JFactory::getDbo();
		$full	= (int) $params->get('full',1);
		$query	= RSEventsQuery::getInstance($params);
		
		list($start, $end) = modRseventsProCalendar::getStartEndCurrentMonth($params);
		$where = $query->betweenQuery($start, $end);
		$where = substr_replace($where,'',0,5);
		
		$query->select(array('e.id', 'e.name', 'e.start', 'e.end', 'e.allday'));
		$query->where($where);
		$query->featured(false);
		$query->userevents(false);
		
		$query = $query->toString();

		$db->setQuery($query);
		$events = $db->loadObjectList();
		
		if (!$full) {
			foreach ($events as $i => $event)
				if (rseventsproHelper::eventisfull($event->id)) unset($events[$i]);
		}
		
		return $events;
	}
	
	public static function getDetailsSmall($ids) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$details	= '';
		
		if (!empty($ids)) {
			$count = count($ids);
			$details = $count.' ';
			$details .= JText::plural('COM_RSEVENTSPRO_CALENDAR_EVENTS',$count);
			$details .= '::';
			
			array_map('intval',$ids);
			
			$query->clear()
				->select($db->qn('name'))
				->from($db->qn('#__rseventspro_events'))
				->where($db->qn('id').' IN ('.implode(',',$ids).')');
			
			$db->setQuery($query);
			$eventnames = $db->loadColumn();
			$details .= htmlentities(implode(', ',$eventnames),ENT_COMPAT,'UTF-8');
		} else {
			$details = JText::_('COM_RSEVENTSPRO_GLOBAL_NO_EVENTS').'::';
		}
		
		return $details;
	}
	
	protected static function getStartEndCurrentMonth($params) {
		$now	= JFactory::getDate();
		$input	= JFactory::getApplication()->input;
		$month	= $input->getInt('month',0);
		$year	= $input->getInt('year',0);
		
		if ($month == 0) {
			$paramsMonth = (int) $params->get('startmonth',0);
			$month		 = $paramsMonth == 0 ? (int) $now->format('n') : $paramsMonth;
		}
		
		if ($year == 0) {
			$paramsYear = (int) $params->get('startyear',0);
			$year		= empty($paramsYear) ? (int) $now->format('Y') : $paramsYear;
		}
		
		if (strlen($month) == 1) $month = '0'.$month;
		
		$startMonth			= JFactory::getDate($year.'-'.$month.'-01 00:00:00');
		$month_start_day	= $startMonth->format('w');
		$weekstart			= $params->get('startday',1);
		$weekdays			= modRseventsProCalendar::getWeekdays($weekstart);
		
		$prevDays = 0;
		if ($month_start_day != $weekstart) {
			foreach ($weekdays as $position)
				if ($position == $month_start_day)
					break;
				else
					$prevDays++;
		}
		
		if ($prevDays) {
			$startMonth->modify('-'.$prevDays.' days');
		}
		
		$endofmonth = JFactory::getDate($year.'-'.$month.'-01 00:00:00')->format($year.'-'.$month.'-t H:i:s');
		$endMonth	= JFactory::getDate($endofmonth);
		$weekend	= modRseventsProCalendar::getWeekdays($weekstart,true);
		$day		= $endMonth->format('w');
		
		$k = 1;
		$nextDays = 0;
		if ($day != $weekend) {
			while($day != $weekend) {
				$nextmonth = $month+1 > 12 ? ($month+1)-12 : $month+1;
				$nextyear  = $month+1 > 12 ? $year+1 : $year;
				
				if (strlen($nextmonth) == 1)
					$nextmonth = '0'.$nextmonth;
				
				$cday = $k;
				if (strlen($cday) == 1)
					$cday = '0'.$cday;
				
				$day = JFactory::getDate($nextyear.'-'.$nextmonth.'-'.$cday.' 00:00:00')->format('w');
				
				$k++;
				$nextDays++;
			}
		}
		
		if ($weekstart == 0) {
			$nextDays++;
		}
		
		if ($nextDays) {
			$endMonth->modify('+'.$nextDays.' days');
		}
		
		$endMonth->modify('+86399 seconds');
		
		return array($startMonth->toSql(), $endMonth->toSql());
	}
	
	protected static function getWeekdays($i,$weekend = false) {
		if ($i == 0) {
			return $weekend ? 6 : array(0,1,2,3,4,5,6);
		} elseif ($i == 1) {
			return $weekend ? 0 : array(1,2,3,4,5,6,0);
		} else if ($i == 6) {
			return $weekend ? 5 : array(6,0,1,2,3,4,5);
		}
	}
}