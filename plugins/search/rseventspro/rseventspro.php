<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die;

/**
 * REvents!Pro Search plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	RSEvents!Pro.events
 */
class plgSearchRseventspro extends JPlugin
{
	/**
	 * @return array An array of search areas
	 */
	public function onContentSearchAreas() {
		static $areas = array('rseventspro' => 'PLG_SEARCH_RSEVENTSPRO_LABEL');
		return $areas;
	}

	/**
	 * RSEvents!Pro Search method
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 * @param string Target search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 * @param mixed An array if the search it to be restricted to areas, null if search all
	 */
	public function onContentSearch($text, $phrase='', $ordering='', $areas=null) {
		$db		= JFactory::getDbo();
		JFactory::getLanguage()->load('plg_search_rseventspro',JPATH_ADMINISTRATOR);

		if (!file_exists(JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php')) 
			return array();
		
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php';
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/route.php';
		require_once JPATH_SITE.'/administrator/components/com_search/helpers/search.php';

		$searchText = $text;
		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		$limit	= $this->params->def('search_limit', 50);

		$text = trim($text);
		if ($text == '') {
			return array();
		}

		$wheres = array();
		switch ($phrase) {
			case 'exact':
				$text		= $db->q('%'.$db->escape($text, true).'%', false);
				$wheres2	= array();
				$wheres2[]	= 'e.name LIKE '.$text;
				$wheres2[]	= 'e.description LIKE '.$text;
				$where		= '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();
				foreach ($words as $word) {
					$word		= $db->q('%'.$db->escape($word, true).'%', false);
					$wheres2	= array();
					$wheres2[]	= 'e.name LIKE '.$word;
					$wheres2[]	= 'e.description LIKE '.$word;
					$wheres[]	= implode(' OR ', $wheres2);
				}
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		$morder = '';
		switch ($ordering) {
			case 'oldest':
				$order = 'e.start ASC';
				break;

			case 'alpha':
				$order = 'e.name ASC';
				break;

			case 'newest':
			default:
				$order = 'e.start DESC';
				break;
		}

		$rows = array();
		$query	= $db->getQuery(true);

		// search query
		$query->clear();

		$query->select('e.id, e.name AS title, e.start AS created');
		$query->select('e.description AS text');
		$query->select('e.name AS section, \'2\' AS browsernav');
		$query->select('e.itemid');

		$query->from('#__rseventspro_events AS e');
		$query->where('('. $where .')' . ' AND e.published = 1 AND e.completed = 1 ');
		$query->group('e.id, e.name');
		$query->order($order);

		$db->setQuery($query, 0, $limit);
		$list = $db->loadObjectList();

		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		
		if (isset($list)) {
			foreach($list as $key => $item) {
				if (!rseventsproHelper::canview($item->id)) {
					unset($list[$key]);
				}

				$query->clear();
				$query->select('c.title');
				$query->from('#__categories AS c');
				$query->leftJOIN('#__rseventspro_taxonomy t ON t.id = c.id');
				$query->where("t.ide = ".(int) $item->id." AND t.`type` = 'category'");
				
				if (JLanguageMultilang::isEnabled()) {
					$query->where('c.language IN ('.$db->q(JFactory::getLanguage()->getTag()).','.$db->q('*').')');
				}
				$query->where('c.access IN ('.$groups.')');
				
				$db->setQuery($query);
				$categories = $db->loadColumn();
				$categories = !empty($categories) ? ' - '.implode(',',$categories) : '';
				$itemid		= !empty($item->itemid) ? $item->itemid : RseventsproHelperRoute::getEventsItemid();
				
				$list[$key]->href = rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($item->id,$item->title),true,$itemid);
				$list[$key]->text = strip_tags($item->text);
				$list[$key]->section = JText::_('PLG_SEARCH_RSEVENTSPRO_LABEL').$categories;
			}
		}
		$rows[] = $list;

		$results = array();
		if (count($rows)) {
			foreach($rows as $row) {
				$new_row = array();
				foreach($row as $key => $event) {
					if (searchHelper::checkNoHTML($event, $searchText, array('text', 'title'))) {
						$new_row[] = $event;
					}
				}
				$results = array_merge($results, (array) $new_row);
			}
		}

		return $results;
	}
}