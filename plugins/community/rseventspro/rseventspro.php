<?php
/**
 * @category	Plugins
 * @package		JomSocial
 * @copyright (C) 2012 RSJoomla!
 * @license		GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT.'/components/com_community/libraries/core.php';

class plgCommunityRseventspro extends CApplications
{
	var $name 		= "RSEvents!Pro Events";
	var $_name		= 'rseventspro';
	
	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		JFactory::getLanguage()->load('plg_community_rseventspro', JPATH_ADMINISTRATOR );
		$this->name = JText::_('PLG_RSEVENTSPRO_APPLICATION');
    }
    
	public function onProfileDisplay() {
		JFactory::getLanguage()->load('plg_community_rseventspro', JPATH_ADMINISTRATOR );

		require_once JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php';
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/route.php';
		JFactory::getDocument()->addStyleSheet(JURI::root(true).'/plugins/community/rseventspro/rseventspro/style.css' );
		
		$config		= CFactory::getConfig();
		$this->loadUserParams();

		$app		= JFactory::getApplication();
		$user		= CFactory::getRequestUser();
		$caching 	= $this->params->get('cache', 1);
		$events		= $this->_getEvents($user,$this->userparams->get('count',5));
		$booked		= $this->_getBookedEvents($user,$this->userparams->get('count',5));
		
		if($caching) {
			$caching = $app->getCfg('caching');
		}
		
		$cache		= JFactory::getCache('plgCommunityRseventspro');
		$cache->setCaching($caching);
		$callback	= array($this , 'getEventsHTML');		
		$content	= $cache->call($callback, $events , $booked , $user , $config );
		return $content; 
	}
	
	public function getEventsHTML($rows , $booked , $user , $config) {
		ob_start();
		echo '<div class="rsepro_events_container">';
		if (empty($rows) && empty($booked)) echo '<div>'.JText::_('PLG_RSEVENTSPRO_NO_EVENTS_CREATED_BY_THE_USER').'</div>';
		$links = $this->params->get('links',0);
		$opener = !$links ? 'target="_blank"' : '';
		
		if ($rows) {
			echo '<h3>'.JText::_('PLG_RSEVENTSPRO_EVENTS_CREATED').'</h3>';
			echo '<ul class="rsepro_events">';
			foreach ($rows as $eid) {
				$details = rseventsproHelper::details($eid);
				if (isset($details['event']) && !empty($details['event'])) $event = $details['event']; else continue;
				
				if ($event->allday)
					echo '<li><a '.$opener.' href="'.rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),true,RseventsproHelperRoute::getEventsItemid()).'">'.$event->name.'</a> ('.rseventsproHelper::date($event->start,rseventsproHelper::getConfig('global_date'),true).')</li>';
				else 
					echo '<li><a '.$opener.' href="'.rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),true,RseventsproHelperRoute::getEventsItemid()).'">'.$event->name.'</a> ('.rseventsproHelper::date($event->start,null,true).' - '.rseventsproHelper::date($event->end,null,true).')</li>';
			}
			echo '</ul>';
		}
		
		if ($booked) {
			echo '<h3>'.JText::_('PLG_RSEVENTSPRO_BOOKED_EVENTS').'</h3>';
			echo '<ul class="rsepro_events">';
			foreach ($booked as $eid) {
				$details = rseventsproHelper::details($eid);
				if (isset($details['event']) && !empty($details['event'])) $event = $details['event']; else continue;
				
				if ($event->allday)
					echo '<li><a '.$opener.' href="'.rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),true,RseventsproHelperRoute::getEventsItemid()).'">'.$event->name.'</a> ('.rseventsproHelper::date($event->start,rseventsproHelper::getConfig('global_date'),true).')</li>';
				else
					echo '<li><a '.$opener.' href="'.rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),true,RseventsproHelperRoute::getEventsItemid()).'">'.$event->name.'</a> ('.rseventsproHelper::date($event->start,null,true).' - '.rseventsproHelper::date($event->end,null,true).')</li>';
				
			}
			echo '</ul>';
		}
		
		echo '</div>';
		?>
		<div class="app-box-footer">
			<a class="app-box-action" href="<?php echo rseventsproHelper::route('index.php?option=com_rseventspro&layout=default',true,RseventsproHelperRoute::getEventsItemid()); ?>">
				<?php echo JText::_('PLG_RSEVENTSPRO_ALL_EVENTS');?>
			</a>
		</div>
		<?php
		$content	= ob_get_contents();
		ob_end_clean();
		
		return $content;
	}
	
	protected function _getEvents($user, $limit) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$temp	= array();
		
		$ordering	= $this->params->get('ordering', 'start');
		$order		= $this->params->get('order', 'ASC');
		
		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('published').' = 1')
			->where($db->qn('completed').' = 1')
			->where($db->qn('owner').' = '.(int) $user->id)
			->order($db->qn($ordering).' '.$db->escape($order));
		
		$db->setQuery($query);
		$events = $db->loadColumn();
		
		if (!empty($events)) {
			$i = 0;
			foreach ($events as $eid) {
				if (!rseventsproHelper::canview($eid)) continue;
				if ($i >= $limit) break;
				$temp[] = $eid;
				$i++;
			}
		}
		
		return $temp;
	}
	
	protected function _getBookedEvents($user, $limit) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$temp	= array();
		
		$query->clear()
			->select('DISTINCT('.$db->qn('ide').')')
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('idu').' = '.(int) $user->id)
			->order($db->qn('date').' DESC');
		
		$db->setQuery($query);
		$events = $db->loadColumn();
		
		if (!empty($events)) {
			$i = 0;
			foreach ($events as $eid) {
				if (!rseventsproHelper::canview($eid)) continue;
				if ($i >= $limit) break;
				$temp[] = $eid;
				$i++;
			}
		}
		
		return $temp;
	}
}