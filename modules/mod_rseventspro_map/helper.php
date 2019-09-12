<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

abstract class modRseventsProMap {

	public static function getEvents($params) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$subquery	= $db->getQuery(true);
		$user		= JFactory::getUser();
		$categories	= $params->get('categories','');
		$locations	= $params->get('locations','');
		$tags		= $params->get('tags','');
		$return		= array();
		
		$query->clear()
			->select($db->qn('e.id'))->select($db->qn('e.name'))->select($db->qn('e.start'))->select($db->qn('e.owner'))->select($db->qn('e.end'))->select($db->qn('e.allday'))
			->select($db->qn('l.id','lid'))->select($db->qn('l.name','lname'))->select($db->qn('l.address'))->select($db->qn('l.coordinates'))->select($db->qn('l.marker'))
			->from($db->qn('#__rseventspro_events','e'))
			->join('left', $db->qn('#__rseventspro_locations','l').' ON '.$db->qn('e.location').' = '.$db->qn('l.id'))
			->where($db->qn('e.published').' = 1')
			->where($db->qn('e.completed').' = 1');
		
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
		
		$db->setQuery($query);
		$events = $db->loadObjectList();
		
		if (!empty($events)) {
			foreach ($events as $event) {
				if (!rseventsproHelper::canview($event->id) && $event->owner != $user->get('id')) continue;
				$return[$event->lid][] = $event;
			}
		}
		
		return $return;
	}
	
	public static function escape($string) {
		return htmlentities($string,ENT_COMPAT,'UTF-8');
	}
}