<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

class RseventsproRouter extends JComponentRouterBase {
	
	/**
	 * Build the route for the com_content component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function build(&$query) {
		$segments = array();
		
		JFactory::getLanguage()->load('com_rseventspro', JPATH_SITE);
		
		// Get current menu item
		$menu		= JFactory::getApplication()->getMenu();
		$menuItem	= empty($query['Itemid']) ? $menu->getActive() : $menu->getItem($query['Itemid']);
		$mView		= empty($menuItem->query['view']) ? null : $menuItem->query['view'];
		$ismenuitem = false;
		
		// Set the default view
		if (!isset($query['view'])) $query['view'] = 'rseventspro';
		
		// RSEvents!Pro views
		if (isset($query['view'])) {
			switch ($query['view']) {
				case 'calendar':
					
					// Set the default view
					if (!isset($query['layout'])) $query['layout'] = 'default';
					
					// Are we dealing with a calendar that is attached to a menu item?
					if (($mView == 'calendar')) {
						$ismenuitem = true;
						unset($query['view']);
					}
					
					switch($query['layout']) {
						case 'default':
							if (!$ismenuitem)
								$segments[] = JText::_('COM_RSEVENTSPRO_CALENDAR_SEF');
						break;
						
						case 'day':
							$segments[] = JText::_('COM_RSEVENTSPRO_CALENDAR_DAY_SEF');
							
							if (isset($query['date']))
								$segments[] = $query['date'];
							
							if (isset($query['mid'])) {
								$segments[] = $query['mid'];
								unset($query['mid']);
							}
						break;
						
						case 'week':
							$segments[] = JText::_('COM_RSEVENTSPRO_CALENDAR_WEEK_SEF');
							
							if (isset($query['date']))
								$segments[] = $query['date'];
						break;
					}
					
					if(isset($query['month'])) {
						$segments[] = $query['month'];
						unset($query['month']);
					}
					
					if(isset($query['year'])) {
						$segments[] = $query['year'];
						unset($query['year']);
					}
				break;
				
				case 'rseventspro':
					
					// Set the default view
					if (!isset($query['layout'])) $query['layout'] = 'rseventspro';
					
					// Are we dealing with a event list that is attached to a menu item?
					if (($mView == 'rseventspro')) {
						$ismenuitem = true;
						unset($query['view']);
					}
					
					switch($query['layout']) {
						case 'default':
							if (!$ismenuitem)
								$segments[] = JText::_('COM_RSEVENTSPRO_EVENTS_SEF');
						break;
						
						case 'show':
							$segments[] = JText::_('COM_RSEVENTSPRO_EVENT_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'edit':
							if (isset($query['id'])) {
								$segments[] = JText::_('COM_RSEVENTSPRO_EDIT_EVENT_SEF');
								$segments[] = $query['id'];
							} else {
								$segments[] = JText::_('COM_RSEVENTSPRO_ADD_EVENT_SEF');
								
								if (isset($query['date']))
									$segments[] = $query['date'];
							}
						break;
						
						case 'file':
							$segments[] = JText::_('COM_RSEVENTSPRO_FILE_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'upload':
							$segments[] = JText::_('COM_RSEVENTSPRO_UPLOAD_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'crop':
							$segments[] = JText::_('COM_RSEVENTSPRO_CROP_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'subscribe':
							$segments[] = JText::_('COM_RSEVENTSPRO_JOIN_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'invite':
							$segments[] = JText::_('COM_RSEVENTSPRO_INVITE_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'message':
							$segments[] = JText::_('COM_RSEVENTSPRO_MESSAGE_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'subscribers':
							$segments[] = JText::_('COM_RSEVENTSPRO_SUBSCRIBERS_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'wire':
							$segments[] = JText::_('COM_RSEVENTSPRO_WIRE_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
								
							if (isset($query['pid']))
								$segments[] = $query['pid'];
						break;
						
						case 'location':
							$segments[] = JText::_('COM_RSEVENTSPRO_LOCATION_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'editlocation':
							$segments[] = JText::_('COM_RSEVENTSPRO_EDIT_LOCATION_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'editsubscriber':
							$segments[] = JText::_('COM_RSEVENTSPRO_VIEW_SUBSCRIBER_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
							
							if (isset($query['ide'])) {
								$segments[] = $query['ide'];
								unset($query['ide']);
							}
						break;
						
						case 'unsubscribe':
							$segments[] = JText::_('COM_RSEVENTSPRO_VIEW_UNSUBSCRIBE_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'ticket':
							$segments[] = JText::_('COM_RSEVENTSPRO_DOWNLOAD_TICKET_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'tickets':
							$segments[] = JText::_('COM_RSEVENTSPRO_TICKETS_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'seats':
							$segments[] = JText::_('COM_RSEVENTSPRO_SEATS_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'userseats':
							$segments[] = JText::_('COM_RSEVENTSPRO_USER_SEATS_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'search':
							$segments[] = JText::_('COM_RSEVENTSPRO_SEARCH_SEF');
						break;
						
						case 'report':
							$segments[] = JText::_('COM_RSEVENTSPRO_REPORT_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
							
						break;
						
						case 'reports':
							$segments[] = JText::_('COM_RSEVENTSPRO_REPORTS_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
							
						break;
						
						case 'print':
							$segments[] = JText::_('COM_RSEVENTSPRO_PRINT_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
							
						break;
						
						case 'scan':
							$segments[] = JText::_('COM_RSEVENTSPRO_SCAN_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
							
						break;
						
						case 'forms':
							$segments[] = JText::_('COM_RSEVENTSPRO_FORMS_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'placeholders':
							$segments[] = JText::_('COM_RSEVENTSPRO_PLACEHOLDERS_SEF');
							
							if (isset($query['type'])) {
								$segments[] = $query['type'];
								unset($query['type']);
							}
						break;
						
						case 'user':
							$segments[] = JText::_('COM_RSEVENTSPRO_USER_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'edituser':
							$segments[] = JText::_('COM_RSEVENTSPRO_USER_EDIT_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
						
						case 'subscriptions':
							$segments[] = JText::_('COM_RSEVENTSPRO_USER_SUBSCRIPTIONS_SEF');
						break;
						
						case 'rsvp':
							$segments[] = JText::_('COM_RSEVENTSPRO_RSVP_SEF');
							
							if (isset($query['id']))
								$segments[] = $query['id'];
						break;
					}
					
					if(isset($query['category'])) {
						$segments[] = JText::_('COM_RSEVENTSPRO_CATEGORY_SEF');
						$segments[] = $query['category'];
						unset($query['category']);
					}
					
					if(isset($query['location'])) {
						$segments[] = JText::_('COM_RSEVENTSPRO_LOCATION_LIST_SEF');
						$segments[] = $query['location'];
						unset($query['location']);
					}
					
					if(isset($query['tag'])) {
						$segments[] = JText::_('COM_RSEVENTSPRO_TAG_SEF');
						$segments[] = $query['tag'];
						unset($query['tag']);
					}
				
					if(isset($query['parent'])) {
						$segments[] = JText::_('COM_RSEVENTSPRO_PARENT_SEF');
						$segments[] = $query['parent'];
						unset($query['parent']);
					}
				
				break;
			}
		}
		
		// RSEvents!Pro tasks
		if (isset($query['task'])) {
			switch ($query['task']) {
				case 'captcha':
					$segments[] = JText::_('COM_RSEVENTSPRO_CAPTCHA_SEF');
				break;
				
				case 'rseventspro.export':
					$segments[] = JText::_('COM_RSEVENTSPRO_EXPORT_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
				break;
				
				case 'rseventspro.exportguests':
					$segments[] = JText::_('COM_RSEVENTSPRO_EXPORT_SUBSCRIBERS_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
				break;
				
				case 'rseventspro.removesubscriber':
					$segments[] = JText::_('COM_RSEVENTSPRO_REMOVE_SUBSCRIBER_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
					
					if (isset($query['ide'])) {
						$segments[] = $query['ide'];
						unset($query['ide']);
					}
				break;
				
				case 'rseventspro.deletesubscriber':
					$segments[] = JText::_('COM_RSEVENTSPRO_DELETE_SUBSCRIBER_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
				break;
				
				case 'rseventspro.approve':
					$segments[] = JText::_('COM_RSEVENTSPRO_APPORVE_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
					
					if (isset($query['ide'])) {
						$segments[] = $query['ide'];
						unset($query['ide']);
					}
				break;
				
				case 'rseventspro.pending':
					$segments[] = JText::_('COM_RSEVENTSPRO_PENDING_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
					
					if (isset($query['ide'])) {
						$segments[] = $query['ide'];
						unset($query['ide']);
					}
				break;
				
				case 'rseventspro.denied':
					$segments[] = JText::_('COM_RSEVENTSPRO_DENIED_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
					
					if (isset($query['ide'])) {
						$segments[] = $query['ide'];
						unset($query['ide']);
					}
				break;
				
				case 'rseventspro.unsubscribe':
					$segments[] = JText::_('COM_RSEVENTSPRO_UNSUBSCRIBE_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
					
					if (isset($query['hash']))
						$segments[] = 'hash-'.$query['hash'];
					
				break;
				
				case 'rseventspro.unsubscribeuser':
					$segments[] = JText::_('COM_RSEVENTSPRO_UNSUBSCRIBEUSER_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
					
					if (isset($query['ide'])) {
						$segments[] = $query['ide'];
						unset($query['ide']);
					}
				break;
				
				case 'rseventspro.remove':
					$segments[] = JText::_('COM_RSEVENTSPRO_DELETE_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
				break;
				
				case 'reminder':
					$segments[] = JText::_('COM_RSEVENTSPRO_REMINDER_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
				break;
				
				case 'postreminder':
					$segments[] = JText::_('COM_RSEVENTSPRO_POSTREMINDER_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
				break;
				
				case 'activate':
					$segments[] = JText::_('COM_RSEVENTSPRO_ACTIVATE_SEF');
					
					if (isset($query['key']))
						$segments[] = $query['key'];
				break;
				
				case 'payment':
					$segments[] = JText::_('COM_RSEVENTSPRO_PAYMENT_SEF');
					
					if (isset($query['method']))
						$segments[] = $query['method'];
						
					if (isset($query['hash']))
						$segments[] = $query['hash'];
				break;
				
				case 'process':
					$segments[] = JText::_('COM_RSEVENTSPRO_PAYMENT_PROCESS_SEF');
				break;
				
				case 'rseventspro.deleteicon':
					$segments[] = JText::_('COM_RSEVENTSPRO_DELETE_ICON_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
				break;
				
				case 'clear':
					$segments[] = JText::_('COM_RSEVENTSPRO_CLEAR_SEF');
				break;
				
				case 'image':
					$segments[] = JText::_('COM_RSEVENTSPRO_EVENT_IMAGE_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
					
				break;
				
				case 'rseventspro.deleteimage':
					$segments[] = JText::_('COM_RSEVENTSPRO_USER_DELETE_IMAGE_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
					
				break;
				
				case 'sendsubscription':
					$segments[] = JText::_('COM_RSEVENTSPRO_SEND_SUBSCRIPTION_SEF');
				break;
				
				case 'rseventspro.removersvp':
					$segments[] = JText::_('COM_RSEVENTSPRO_RSVP_REMOVE_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
					
					if (isset($query['ide'])) {
						$segments[] = $query['ide'];
						unset($query['ide']);
					}
				break;
				
				case 'rseventspro.exportrsvpguests':
					$segments[] = JText::_('COM_RSEVENTSPRO_RSVP_EXPORT_SEF');
					
					if (isset($query['id']))
						$segments[] = $query['id'];
				break;
			}
		}
		
		if (isset($query['rsemygate'])) {
			$segments[] = 'mygate-callback';
			unset($query['rsemygate']);
		}
		
		JFactory::getApplication()->triggerEvent('rsepro_buildRoute', array(array('query' => &$query, 'segments' => &$segments)));
		
		unset($query['view'], $query['layout'], $query['controller'], $query['task'], $query['id'], $query['pid'], $query['date'], $query['key'], $query['tmpl'], $query['method'], $query['hash']);
		
		$total = count($segments);

		for ($i = 0; $i < $total; $i++) {
			$segments[$i] = str_replace(':', '-', $segments[$i]);
		}
		
		return $segments;
	}
	
	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
	public function parse(&$segments) {
		$query = array();
		
		JFactory::getLanguage()->load('com_rseventspro', JPATH_SITE);
		
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php';
		
		$links	= rseventsproHelper::getConfig('modal','int');
		$menu	= JFactory::getApplication()->getMenu();
		$item	= $menu->getActive();
		$routes = $this->getAllRseproRoutes();
		
		if ($item && isset($item->query) && isset($item->query['option']) && $item->query['option'] == 'com_rseventspro') {
			if (isset($item->query['view'])) {
				switch ($item->query['view']) {
					case 'calendar':
						$query['view']   = 'calendar';
						$segments[0] = str_replace(':','-',$segments[0]);
						if (!in_array($segments[0], $routes)) {
							array_unshift($segments, JText::_('COM_RSEVENTSPRO_CALENDAR_SEF'));
							$query['layout'] = 'default';
						}
					break;
					
					case 'rseventspro':
						$query['view']   = 'rseventspro';
						$segments[0] = str_replace(':','-',$segments[0]);
						if (!in_array($segments[0], $routes)) {
							array_unshift($segments, JText::_('COM_RSEVENTSPRO_EVENTS_SEF'));
							$query['layout'] = 'default';
						}
					break;
				}
			}
		}
		
		switch ($segments[0]) {
			// Calendar sef
			case JText::_('COM_RSEVENTSPRO_CALENDAR_SEF'):
				$query['view']		= 'calendar';
				$query['layout'] 	= 'default';
				$query['month']		= isset($segments[1]) ? (int) $segments[1] : null;
				$query['year']		= isset($segments[2]) ? (int) $segments[2] : null;
			break; 
			
			case JText::_('COM_RSEVENTSPRO_CALENDAR_DAY_SEF'):
				$query['view']		= 'calendar';
				$query['layout']	= 'day';
				$query['date']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['mid']		= isset($segments[2]) ? (int) $segments[2] : null;
			break; 
			
			case JText::_('COM_RSEVENTSPRO_CALENDAR_WEEK_SEF'):
				$query['view']		= 'calendar';
				$query['layout']	= 'week';
				$query['date']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break; 
			
			// Events sef
			case JText::_('COM_RSEVENTSPRO_CALENDAR_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout'] 	= 'default';
			break; 
			
			case JText::_('COM_RSEVENTSPRO_EVENT_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'show';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_LOCATION_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'location';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_EDIT_EVENT_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'edit';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_ADD_EVENT_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'edit';
				$query['date']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_FILE_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'file';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['tmpl']		= 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_UPLOAD_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'upload';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['tmpl']		= 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_CROP_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'crop';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['tmpl']		= 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_CATEGORY_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'default';
				$query['category']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_LOCATION_LIST_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'default';
				$query['location']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_TAG_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'default';
				$query['tag']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_JOIN_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'subscribe';
				$query['id']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				if ($links != 0) $query['tmpl'] = 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_INVITE_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'invite';
				$query['id']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				if ($links != 0) $query['tmpl'] = 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_MESSAGE_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'message';
				$query['id']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				if ($links != 0) $query['tmpl'] = 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_SUBSCRIBERS_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'subscribers';
				$query['id']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_WIRE_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'wire';
				$query['id']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['pid']	= isset($segments[2]) ? str_replace(':','-',$segments[2]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_EDIT_LOCATION_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'editlocation';
				$query['id']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_VIEW_SUBSCRIBER_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'editsubscriber';
				$query['id']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['ide']	= isset($segments[2]) ? str_replace(':','-',$segments[2]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_VIEW_UNSUBSCRIBE_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'unsubscribe';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['tmpl']		= 'component';
			break;
			
			
			// Tasks
			case JText::_('COM_RSEVENTSPRO_EXPORT_SEF'):
				$query['task']			= 'rseventspro.export';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_EXPORT_SUBSCRIBERS_SEF'):
				$query['task']			= 'rseventspro.exportguests';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_REMOVE_SUBSCRIBER_SEF'):
				$query['task']			= 'rseventspro.removesubscriber';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['ide']			= isset($segments[2]) ? str_replace(':','-',$segments[2]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_DELETE_SUBSCRIBER_SEF'):
				$query['task']			= 'rseventspro.deletesubscriber';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_APPORVE_SEF'):
				$query['task']			= 'rseventspro.approve';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['ide']			= isset($segments[2]) ? str_replace(':','-',$segments[2]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_PENDING_SEF'):
				$query['task']			= 'rseventspro.pending';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['ide']			= isset($segments[2]) ? str_replace(':','-',$segments[2]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_DENIED_SEF'):
				$query['task']			= 'rseventspro.denied';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['ide']			= isset($segments[2]) ? str_replace(':','-',$segments[2]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_UNSUBSCRIBE_SEF'):
				$segment = isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['task']			= 'rseventspro.unsubscribe';
				
				if ($segment) {
					if (strpos($segment, 'hash') !== false) {
						$query['hash'] = str_replace('hash-', '', $segment);
					} else {
						$query['id'] = $segment;
					}
				}
			break;
			
			case JText::_('COM_RSEVENTSPRO_UNSUBSCRIBEUSER_SEF'):
				$query['task']			= 'rseventspro.unsubscribeuser';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['ide']			= isset($segments[2]) ? str_replace(':','-',$segments[2]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_DELETE_SEF'):
				$query['task']			= 'rseventspro.remove';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_CAPTCHA_SEF'):
				$query['task']	= 'captcha';
			break;
			
			case JText::_('COM_RSEVENTSPRO_REMINDER_SEF'):
				$query['task']	= 'reminder';
				$query['id']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_POSTREMINDER_SEF'):
				$query['task']	= 'postreminder';
				$query['id']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_ACTIVATE_SEF'):
				$query['task']	= 'activate';
				$query['key']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_PAYMENT_SEF'):
				$query['task']		= 'payment';
				$query['method']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['hash']		= isset($segments[2]) ? str_replace(':','-',$segments[2]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_PAYMENT_PROCESS_SEF'):
				$query['task']	= 'process';
			break;
			
			case JText::_('COM_RSEVENTSPRO_DOWNLOAD_TICKET_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'ticket';
				$query['id']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_TICKETS_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'tickets';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['tmpl']		= 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_SEATS_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'seats';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['tmpl']		= 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_USER_SEATS_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'userseats';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['tmpl']		= 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_REPORT_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'report';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['tmpl']		= 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_REPORTS_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'reports';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_PRINT_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'print';
				$query['tmpl']		= 'component';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_SCAN_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'scan';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_SEARCH_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'search';
			break;
			
			case JText::_('COM_RSEVENTSPRO_DELETE_ICON_SEF'):
				$query['task']			= 'rseventspro.deleteicon';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_FORMS_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'forms';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['tmpl']		= 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_CLEAR_SEF'):
				$query['task']	= 'clear';
			break;
			
			case JText::_('COM_RSEVENTSPRO_PARENT_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'default';
				$query['parent']	= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_EVENT_IMAGE_SEF'):
				$query['task']		= 'image';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_USER_DELETE_IMAGE_SEF'):
				$query['task']		= 'rseventspro.deleteimage';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_PLACEHOLDERS_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'placeholders';
				$query['type']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['tmpl']		= 'component';
			break;
			
			case JText::_('COM_RSEVENTSPRO_USER_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'user';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_USER_EDIT_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'edituser';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_USER_SUBSCRIPTIONS_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'subscriptions';
			break;
			
			case JText::_('COM_RSEVENTSPRO_SEND_SUBSCRIPTION_SEF'):
				$query['task']		= 'sendsubscription';
			break;
			
			case JText::_('COM_RSEVENTSPRO_RSVP_SEF'):
				$query['view']		= 'rseventspro';
				$query['layout']	= 'rsvp';
				$query['id']		= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_RSVP_REMOVE_SEF'):
				$query['task']			= 'rseventspro.removersvp';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
				$query['ide']			= isset($segments[2]) ? str_replace(':','-',$segments[2]) : null;
			break;
			
			case JText::_('COM_RSEVENTSPRO_RSVP_EXPORT_SEF'):
				$query['task']			= 'rseventspro.exportrsvpguests';
				$query['id']			= isset($segments[1]) ? str_replace(':','-',$segments[1]) : null;
			break;
		}
		
		JFactory::getApplication()->triggerEvent('rsepro_parseRoute', array(array('query' => &$query, 'segments' => $segments)));
		
		foreach ($segments as $segment) {
			$segment = str_replace(':','-',$segment);
			if ($segment == 'mygate-callback') {
				$query['rsemygate'] = 1;
				break;
			}
		}
		
		// Joomla 4.x compatibility 
		$jversion = new JVersion();
		if ($jversion->isCompatible('4')) {
			$segments = array();
		}
		
		return $query;
	}
	
	protected function getAllRseproRoutes() {
		$routes = array(JText::_('COM_RSEVENTSPRO_CALENDAR_SEF'), JText::_('COM_RSEVENTSPRO_CALENDAR_DAY_SEF'), JText::_('COM_RSEVENTSPRO_CALENDAR_WEEK_SEF'), JText::_('COM_RSEVENTSPRO_EVENTS_SEF'), 
			JText::_('COM_RSEVENTSPRO_EVENT_SEF'), JText::_('COM_RSEVENTSPRO_LOCATION_SEF'), JText::_('COM_RSEVENTSPRO_EDIT_EVENT_SEF'), JText::_('COM_RSEVENTSPRO_ADD_EVENT_SEF'), JText::_('COM_RSEVENTSPRO_CATEGORY_SEF'), JText::_('COM_RSEVENTSPRO_TAG_SEF'), JText::_('COM_RSEVENTSPRO_JOIN_SEF'), JText::_('COM_RSEVENTSPRO_INVITE_SEF'), JText::_('COM_RSEVENTSPRO_MESSAGE_SEF'), JText::_('COM_RSEVENTSPRO_SUBSCRIBERS_SEF'),JText::_('COM_RSEVENTSPRO_EXPORT_SEF'), JText::_('COM_RSEVENTSPRO_UNSUBSCRIBE_SEF'), JText::_('COM_RSEVENTSPRO_WIRE_SEF'),JText::_('COM_RSEVENTSPRO_CAPTCHA_SEF'), JText::_('COM_RSEVENTSPRO_DELETE_SEF'), JText::_('COM_RSEVENTSPRO_REMINDER_SEF'), JText::_('COM_RSEVENTSPRO_POSTREMINDER_SEF'), JText::_('COM_RSEVENTSPRO_EDIT_LOCATION_SEF'), JText::_('COM_RSEVENTSPRO_VIEW_SUBSCRIBER_SEF'), JText::_('COM_RSEVENTSPRO_EXPORT_SUBSCRIBERS_SEF'), JText::_('COM_RSEVENTSPRO_DELETE_SUBSCRIBER_SEF'), JText::_('COM_RSEVENTSPRO_APPORVE_SEF'), JText::_('COM_RSEVENTSPRO_PENDING_SEF'), JText::_('COM_RSEVENTSPRO_DENIED_SEF'), JText::_('COM_RSEVENTSPRO_FILE_SEF'), JText::_('COM_RSEVENTSPRO_UPLOAD_SEF'), JText::_('COM_RSEVENTSPRO_CROP_SEF'),JText::_('COM_RSEVENTSPRO_LOCATION_LIST_SEF'), JText::_('COM_RSEVENTSPRO_ACTIVATE_SEF'), JText::_('COM_RSEVENTSPRO_PAYMENT_SEF'), JText::_('COM_RSEVENTSPRO_PAYMENT_PROCESS_SEF'), JText::_('COM_RSEVENTSPRO_VIEW_UNSUBSCRIBE_SEF'), JText::_('COM_RSEVENTSPRO_UNSUBSCRIBEUSER_SEF'), JText::_('COM_RSEVENTSPRO_DOWNLOAD_TICKET_SEF'), JText::_('COM_RSEVENTSPRO_SEARCH_SEF'), JText::_('COM_RSEVENTSPRO_DELETE_ICON_SEF'), JText::_('COM_RSEVENTSPRO_CLEAR_SEF'), JText::_('COM_RSEVENTSPRO_FORMS_SEF'), JText::_('COM_RSEVENTSPRO_PARENT_SEF'), 
			JText::_('COM_RSEVENTSPRO_TICKETS_SEF'), JText::_('COM_RSEVENTSPRO_SEATS_SEF'), JText::_('COM_RSEVENTSPRO_REPORT_SEF'), JText::_('COM_RSEVENTSPRO_REPORTS_SEF'), JText::_('COM_RSEVENTSPRO_SCAN_SEF'),
			JText::_('COM_RSEVENTSPRO_USER_SEATS_SEF'), JText::_('COM_RSEVENTSPRO_PRINT_SEF'), JText::_('COM_RSEVENTSPRO_EVENT_IMAGE_SEF'), JText::_('COM_RSEVENTSPRO_PLACEHOLDERS_SEF'), JText::_('COM_RSEVENTSPRO_USER_SEF'),
			JText::_('COM_RSEVENTSPRO_USER_EDIT_SEF'), JText::_('COM_RSEVENTSPRO_USER_DELETE_IMAGE_SEF'), JText::_('COM_RSEVENTSPRO_REMOVE_SUBSCRIBER_SEF'), JText::_('COM_RSEVENTSPRO_USER_SUBSCRIPTIONS_SEF'), JText::_('COM_RSEVENTSPRO_SEND_SUBSCRIPTION_SEF'), JText::_('COM_RSEVENTSPRO_RSVP_SEF'), JText::_('COM_RSEVENTSPRO_RSVP_REMOVE_SEF'), JText::_('COM_RSEVENTSPRO_RSVP_EXPORT_SEF')
		);
		
		JFactory::getApplication()->triggerEvent('rsepro_allRoutes', array(array('routes' => &$routes)));
		
		return $routes;
	}
}

// Legacy functions 
function rseventsproBuildRoute(&$query) {
	$router = new RseventsproRouter;
	
	return $router->build($query);
}

function rseventsproParseRoute($segments) {
	$router = new RseventsproRouter;
	
	return $router->parse($segments);
}