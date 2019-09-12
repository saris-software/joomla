<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

abstract class modRseventsProSlider {

	public static function getEvents($params) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$subquery	= $db->getQuery(true);
		$return		= array();
		$config		= rseventsproHelper::getConfig();
		$categories	= $params->get('categories','');
		$locations	= $params->get('locations','');
		$tags		= $params->get('tags','');
		$order		= $params->get('ordering','start');
		$direction	= $params->get('order','DESC');
		$events		= (int) $params->get('events',0);
		$archived	= (int) $params->get('archived',0);
		$repeating	= (int) $params->get('repeating',0);
		$image_type	= (int) $params->get('image_type',1);
		$custom		= (int) $params->get('custom',300);
		$limit		= (int) $params->get('limit',4);
		
		$todayDate = JFactory::getDate();
		$todayDate->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
		$todayDate->setTime(0,0,0);
		$today = $todayDate->toSql();
		$todayDate->modify('+1 days');
		$tomorrow = $todayDate->toSql();
		
		$query->clear()
			->select($db->qn('e.id'))->select($db->qn('e.name'))->select($db->qn('e.description'))
			->select($db->qn('e.icon'))->select($db->qn('e.start'))->select($db->qn('e.end'))
			->select($db->qn('e.allday'))
			->from($db->qn('#__rseventspro_events','e'))
			->where($db->qn('e.completed').' = 1');
		
		if (!$repeating) {
			$query->where ($db->qn('e.parent').' = '.$db->q('0'));
		}
		
		$alldayEvents = modRseventsProSlider::_getAllDayEvents();
		
		if ($alldayEvents) {
			$active_today = '(((('.$db->qn('e.start').' <= '.$db->q($today).' AND '.$db->qn('e.end').' >= '.$db->q($today).') OR ('.$db->qn('e.start').' >= '.$db->q($today).' AND '.$db->qn('e.start').' < '.$db->q($tomorrow).')) AND '.$db->qn('e.end').' <> '.$db->q($db->getNullDate()).') OR '.$db->qn('e.id').' IN ('.implode(',',$alldayEvents).'))';
		} else {
			$active_today = '(('.$db->qn('e.start').' <= '.$db->q($today).' AND '.$db->qn('e.end').' >= '.$db->q($today).') OR ('.$db->qn('e.start').' >= '.$db->q($today).' AND '.$db->qn('e.start').' < '.$db->q($tomorrow).'))';
		}
		$upcoming = $db->qn('e.start').' >= '.$db->q(JFactory::getDate()->toSql());
		
		if ($events == 0) // active today + upcoming
			$query->where('('.$active_today.' OR ('.$upcoming.'))');
		elseif ($events == 2) // upcoming
			$query->where($upcoming);
		elseif ($events == 1) // active today
			$query->where($active_today);
		
		if ($archived) {
			$query->where($db->qn('e.published').' IN (1,2)');
		} else {
			$query->where($db->qn('e.published').' = 1');
		}
		
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
		
		if (!empty($locations)) {
			array_map('intval',$locations);
			
			$query->where($db->qn('e.location').' IN ('.implode(',',$locations).')');
		}
		
		$exclude = modRseventsProSlider::excludeEvents();
		
		if (!empty($exclude))
			$query->where($db->qn('e.id').' NOT IN ('.implode(',',$exclude).')');
		
		$query->order($db->qn('e.'.$order).' '.$db->escape($direction));
		
		$db->setQuery($query,0,$limit);
		if ($return = $db->loadObjectList()) {
			foreach ($return as &$event) {
				if (!empty($event->icon)) {
					if ($image_type == 0) {
						$event->image = rseventsproHelper::thumb($event->id, $config->icon_small_width);
					} elseif ($image_type == 1) {
						$event->image = rseventsproHelper::thumb($event->id, $config->icon_big_width);
					} elseif ($image_type == 2) {
						$event->image = JURI::root().'components/com_rseventspro/assets/images/events/'.$event->icon;
					} else {
						$event->image = rseventsproHelper::thumb($event->id, $custom);
					}
				}
			}
		}
		
		return $return;
	}
	
	protected static function excludeEvents() {
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
	
	protected static function _getAllDayEvents() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$todayUTC = JFactory::getDate();
		$todayUTC->setTime(0,0,0);
		$todayUTC = $todayUTC->format('Y-m-d H:i:s');
		
		$today = JFactory::getDate();
		$today->setTimezone(new DateTimeZone(rseventsproHelper::getTimezone()));
		$today->setTime(0,0,0);
		$today = $today->format('Y-m-d H:i:s');
		
		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('allday').' = 1')
			->where('('.$db->qn('start').' = '.$db->q($today).' OR '.$db->qn('start').' = '.$db->q($todayUTC).')');
		
		$db->setQuery($query);
		if ($events = $db->loadColumn()) {
			array_map('intval',$events);
			return $events;
		}
		
		return false;
	}
	
	public static function getTimeline($events,$nr_events) {
        $timeline = array();

		$pages = ceil(count($events) / $nr_events);
		for ($i=1; $i<=$pages; $i++) {
			$start 	= $i*$nr_events - $nr_events;
			$end 	= $i*$nr_events - 1;
			
			if (!isset($events[$end]))
				$end = array_search(end($events), $events);
			
			$startdate	= rseventsproHelper::showdate($events[$start]->start,'d') . ' ' . rseventsproHelper::showdate($events[$start]->start,'M');
			$enddate	= rseventsproHelper::showdate($events[$end]->start,'d') . ' ' . rseventsproHelper::showdate($events[$end]->start,'M');
			
			array_push($timeline, array('start' => $startdate, 'end' => $enddate));
		}
		
		return $timeline;
	}
	
	public static function carousel($selector, $params) {
		$version 	= new JVersion();
		$is30		= $version->isCompatible('3.0');
		$stop_over 	= (int) $params->get('stop_over', 1);
		$interval  	= (int) $params->get('responsive_interval', 5);
		$interval 	= $interval * 1000;
		$autoplay 	= (int) $params->get('autoplay', 1);
		$effect		= $params->get('responsive_effect', 'slide');
		$theme		= $params->get('responsive_theme', 'dark');
		$direction 	= $params->get('responsive_slide_direction', 'left');
		$options	= '{"interval": '.$interval.',"pause": "'.($stop_over ? 'hover' : '').'", "effect":"'.$effect.'", "direction":"'.$direction.'"}';
		$root		= JURI::root(true);
		$document	= JFactory::getDocument();
		
		JHtml::stylesheet('mod_rseventspro_slider/bootstrap-carousel.css', array('relative' => true, 'version' => 'auto'));
		JHtml::stylesheet('mod_rseventspro_slider/fixes.css', array('relative' => true, 'version' => 'auto'));
		JHtml::stylesheet('mod_rseventspro_slider/'.$theme.'.css', array('relative' => true, 'version' => 'auto'));
		
		JHtml::script('mod_rseventspro_slider/jcarousel.js', array('relative' => true, 'version' => 'auto'));
		JHtml::script('mod_rseventspro_slider/jquery.touchSwipe.min.js', array('relative' => true, 'version' => 'auto'));
		
		$document->addScriptDeclaration(
			"
			jQuery(document).ready(function($){
				$('.$selector').each(function(index, element) { $(this)[index].slide = null; });
				$('.$selector').rseprocarousel(".($autoplay ? "$options".($is30 ? ",'cycle'" : "") : "'pause'").");
				$('.$selector .carousel-inner .item').swipe( {
					//Generic swipe handler for all directions
					swipeLeft:function(event, direction, distance, duration, fingerCount) {
						$(this).parents('.$selector').rseprocarousel('next'); 
					},
					swipeRight: function() {
						$(this).parents('.$selector').rseprocarousel('prev'); 
					},
					threshold:0
				});
			});
			"
		);
	}
}