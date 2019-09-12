<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class RseventsproViewCalendar extends JViewLegacy
{
	public function display($tpl = null) {
		$doc		= JFactory::getDocument();
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$layout		= $this->getLayout();
		$pathway	= $app->getPathWay();
		$menus		= $app->getMenu();
		$menu		= $menus->getActive();
		$jconfig	= JFactory::getConfig();
		$title		= null;
		
		// Get menu parameters , user permission etc.
		$this->user			= $user->get('id');
		$this->admin		= rseventsproHelper::admin();
		$this->params		= rseventsproHelper::getParams();
		$this->permissions	= rseventsproHelper::permissions();
		$this->config		= rseventsproHelper::getConfig();
		$this->operator		= $this->get('Operator');
		
		$this->timezoneReturn	= base64_encode(JUri::getInstance());
		$this->timezone			= JFactory::getConfig()->get('offset');
		
		$uri = JUri::getInstance();
		$clone = clone ($uri);
		
		$clone->setVar('format','feed');
		$clone->setVar('type','rss');
		$this->rss = $this->escape($clone->toString());
		$clone->setVar('format','raw');
		$clone->setVar('type','ical');
		$this->ical = $this->escape($clone->toString());
		
		$mid = $app->input->getInt('mid',0);
		$mid = $mid ? '&mid='.$mid : '';
		
		// Add Joomla! menu metadata
		if ($menu && isset($menu->title)) {
			$title = $menu->title;
		}
		
		if ($this->params->get('page_title')) {
			$title = $this->params->get('page_title');
		}
		
		if ($title) {
			if ($jconfig->get('sitename_pagetitles', 0) == 1) {
				$title = JText::sprintf('JPAGETITLE', $jconfig->get('sitename'), $title);
			} elseif ($jconfig->get('sitename_pagetitles', 0) == 2) {
				$title = JText::sprintf('JPAGETITLE', $title, $jconfig->get('sitename'));
			}
			
			$doc->setTitle($title);
		}
		
		if ($this->params->get('menu-meta_description'))
			$doc->setDescription($this->params->get('menu-meta_description'));

		if ($this->params->get('menu-meta_keywords'))
			$doc->setMetadata('keywords', $this->params->get('menu-meta_keywords'));

		if ($this->params->get('robots'))
			$doc->setMetadata('robots', $this->params->get('robots'));
		
		// Add custom scripts
		JHtml::stylesheet('com_rseventspro/calendar.css', array('relative' => true, 'version' => 'auto'));
		
		// Get events
		$events = $this->get('Events');
		
		// If the option to not show full events is enabled , then remove them from our events list
		if (!$this->params->get('full',1)) {
			foreach ($events as $i => $event) {
				if (rseventsproHelper::eventisfull($event->id)) {
					unset($events[$i]);
				}
			}
		}
		
		$this->events	= $events;
		$this->total	= $this->get('total');
		
		if ($layout == 'default') {
			require_once JPATH_SITE.'/components/com_rseventspro/helpers/calendar.php';
			
			// Get colors
			$this->legend = $this->get('colors');
			
			if (!empty($this->legend)) {
				$this->selected = $this->get('selected');
			}
			
			// Add search bar
			if ($this->params->get('search',1)) {
				if ($doc->getType() == 'html') {
					$doc->addCustomTag('<script src="'.JHtml::script('com_rseventspro/jquery.filter.js', array('relative' => true, 'pathOnly' => true, 'version' => 'auto')).'" type="text/javascript"></script>');
				}
				
				$filters			= $this->get('filters');
				$this->columns		= $filters[0];
				$this->operators	= $filters[1];
				$this->values		= $filters[2];
				$this->extra		= $this->get('ExtraFilters');
				$this->showCondition= $this->get('Conditions');
				
				// Price slider assets
				JHtml::stylesheet('com_rseventspro/bootstrap-slider.css', array('relative' => true, 'version' => 'auto'));
				JHtml::script('com_rseventspro/bootstrap-slider.js', array('relative' => true, 'version' => 'auto'));
				$this->maxPrice = $this->get('MaxPrice');
			}
			
			$this->mask		= empty($this->config->payment_mask) ? '%p %c' : $this->config->payment_mask;
			$this->currency	= empty($this->config->payment_currency_sign) ? $this->config->payment_currency : $this->config->payment_currency_sign;
			$this->decimals	= $this->config->payment_decimals;
			$this->decimal	= $this->config->payment_decimal;
			$this->thousands= $this->config->payment_thousands;
			
			// Set the pathway
			if (!$menu) {
				$pathway->addItem(JText::_('COM_RSEVENTSPRO_BC_CALENDAR'));
			}
			
			$cmonth	= $app->input->getInt('month', '0');
			$cyear	= $app->input->getInt('year', '0');
			
			// Get a new instance of the calendar
			$calendar = new RSEPROCalendar($this->events,$this->params);
			
			if ($cmonth && $cyear) {
				$calendar->setDate($cmonth, $cyear);
			}
			
			$this->calendar = $calendar;
			$this->months	= array();
			$this->years	= array();
			
			if (!empty($this->calendar->months)) {
				foreach ($this->calendar->months as $i => $month) {
					$this->months[] = JHTML::_('select.option', $i, $month);
				}
			}
			
			$current_year = empty($this->calendar->cyear) ? gmdate('Y') : $this->calendar->cyear;
			
			for($j = ($current_year - 5); $j <= ($current_year + 5); $j++) {
				$this->years[] = JHTML::_('select.option', $j, $j);
			}
			
		} elseif ($layout == 'day') {
			$date = $app->input->getString('date');
			$date = str_replace(array('-',':'),'/',$date);
			list($m,$d,$y) = explode('/',$date,3);
			
			$start = JFactory::getDate($y.'-'.$m.'-'.$d.' 00:00:00');
			$this->date = rseventsproHelper::translatedate($start->format(rseventsproHelper::getConfig('global_date'), false, false));
			$start->modify('-1 days');
			$prev = $start->format('m-d-Y');
			$start->modify('+2 days');
			$next = $start->format('m-d-Y');
			
			$this->next = rseventsproHelper::route('index.php?option=com_rseventspro&view=calendar&layout=day&date='.$next.$mid);
			$this->prev = rseventsproHelper::route('index.php?option=com_rseventspro&view=calendar&layout=day&date='.$prev.$mid);
		} elseif ($layout == 'week') {
			$date = $app->input->getString('date');
			$date	= str_replace(array('-',':'),'/',$date);
			list($m,$d,$y) = explode('/',$date,3);
			
			$start	= JFactory::getDate($y.'-'.$m.'-'.$d.' 00:00:00');
			$from	= $start->format(rseventsproHelper::getConfig('global_date'), false, false);
			$start->modify('+6 days');
			$to		= $start->format(rseventsproHelper::getConfig('global_date'), false, false);
			
			$this->from	= rseventsproHelper::translatedate($from);
			$this->to	= rseventsproHelper::translatedate($to);
			
			$current = JFactory::getDate($y.'-'.$m.'-'.$d.' 00:00:00');
			$current->modify('-7 days');
			$prev = $current->format('m-d-Y');
			$current->modify('+14 days');
			$next = $current->format('m-d-Y');
			
			$this->next = rseventsproHelper::route('index.php?option=com_rseventspro&view=calendar&layout=week&date='.$next.$mid);
			$this->prev = rseventsproHelper::route('index.php?option=com_rseventspro&view=calendar&layout=week&date='.$prev.$mid);
		}
		
		parent::display($tpl);
	}
	
	public function getColour($id) {
		static $cache = array();
		if (empty($cache)) {
			$db			= JFactory::getDbo();
			$query		= $db->getQuery(true);
			$excluded	= rseventsproHelper::excludeEvents();

			$query->clear()
				->select($db->qn('t.ide'))->select($db->qn('c.params'))
				->from($db->qn('#__categories','c'))
				->join('left', $db->qn('#__rseventspro_taxonomy','t').' ON '.$db->qn('t.id').' = '.$db->qn('c.id'))
				->where($db->qn('t.type').' = '.$db->q('category'))
				->where($db->qn('c.params').' <> '.$db->q(''))
				->where($db->qn('c.extension').' = '.$db->q('com_rseventspro'));
			
			if (JLanguageMultilang::isEnabled()) {
				$query->where('c.language IN ('.$db->q(JFactory::getLanguage()->getTag()).','.$db->q('*').')');
			}
			
			$user	= JFactory::getUser();
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('c.access IN ('.$groups.')');
			
			if ($excluded) {
				$query->where($db->qn('t.ide').' NOT IN ('.implode(',',$excluded).')');
			}
			
			$db->setQuery($query);
			$cache = $db->loadObjectList('ide');
			
			if (!empty($cache)) {
				foreach ($cache as $ide => $object) {
					try {
						$registry = new JRegistry;
						$registry->loadString($object->params);
						$cache[$ide]->color = $registry->get('color','');
					} catch (Exception $e) {
						$cache[$ide]->color = '';
					}
				}
			}
		}
		
		return !empty($cache[$id]) ? $cache[$id]->color : '';
	}
	
	public function getDetailsBig($event) {
		$details = '';
		
		if ($event->allday) {
			$details .= '<b>'.JText::_('COM_RSEVENTSPRO_GLOBAL_ON').'</b> '.rseventsproHelper::showdate($event->start,rseventsproHelper::getConfig('global_date'),true).'<br />';
		} else {
			$details .= '<b>'.JText::_('COM_RSEVENTSPRO_CALENDAR_FROM').'</b> '.rseventsproHelper::showdate($event->start,null,true).'<br />';
			$details .= '<b>'.JText::_('COM_RSEVENTSPRO_CALENDAR_TO').'</b> '.rseventsproHelper::showdate($event->end,null,true).'<br />';
		}
		
		return $details;
	}
	
	public function getDetailsSmall($ids) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$details	= '';
		
		if (!empty($ids)) {
			$ids = array_map('intval',$ids);
			$query->clear()
				->select($db->qn('name'))
				->from($db->qn('#__rseventspro_events'))
				->where($db->qn('id').' IN ('.implode(',',$ids).')');
			
			$db->setQuery($query);
			$eventnames = $db->loadColumn();
			$details .= $this->escape(implode('<br />',$eventnames));
		} else {
			$details = JText::_('COM_RSEVENTSPRO_GLOBAL_NO_EVENTS').'<br />'.JText::_('COM_RSEVENTSPRO_GLOBAL_NO_EVENTS');
		}
		
		return $details;
	}
}