<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_SITE.'/components/com_rseventspro/helpers/version.php';

if (!function_exists('mb_strlen')) {
	function mb_strlen($str, $encoding = 'iso-8859-1') {
		switch (str_replace('-', '', strtolower($encoding))) {
			case "utf8": return strlen(utf8_encode($str));
			case "8bit": return strlen($str);
			default:     return strlen(utf8_decode($str));
		}
	}
}

if (!function_exists('mb_substr')) {
	function mb_substr($string, $start, $length = null, $encoding = 'UTF-8') {
		return implode("", array_slice(preg_split("//u", $string, -1, PREG_SPLIT_NO_EMPTY), $start, $length));
	}
}

class rseventsproHelper
{
	protected static $groups = null;
	protected static $users = null;
	
	// Generate code for updates
	public static function genKeyCode() {
		if ($code = rseventsproHelper::getConfig('global_code')) {
			$version = new RSEventsProVersion();
			return md5($code.$version->key);
		} else return '';
	}
	
	// Check for Joomla! version
	public static function isJ3() {
		return version_compare(JVERSION, '3.0', '>=');
	}
	
	// Check for Joomla! version
	public static function isJ34() {
		return version_compare(JVERSION, '3.4', '>=');
	}
	
	// Get component configuration
	public static function getConfig($name = null, $type = 'none' , $default = null) {
		static $config;
		
		if (!is_object($config)) {
			$db		= JFactory::getDBO();
			$query	= $db->getQuery(true);
			$config	= new stdClass();
			
			$query->clear()->select('*')->from($db->qn('#__rseventspro_config'));
			$db->setQuery($query);
			$configuration = $db->loadObjectList();
			
			if (!empty($configuration)) {
				foreach ($configuration as $c) {
					$config->{$c->name} = $c->value;
				}
			}
		}
		
		if ($name != null) {
			if (isset($config->{$name})) {
				switch($type) {
					default:
					case 'none': return $config->{$name}; break;
					case 'int': return (int) $config->{$name}; break;
					case 'string': return (string) $config->{$name}; break;
					case 'bool': return (bool) $config->{$name}; break;
				}
			} else {
				return is_null($default) ? false : $default;
			}
		} else {
			return $config;
		}
	}
	
	// Load files and scripts
	public static function loadHelper() {
		
		if (version_compare(PHP_VERSION,'5.3.0','>='))
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
		
		// Load the language
		rseventsproHelper::loadLang();
		
		// Load javascript and style scripts
		rseventsproHelper::loadScripts();
		
		// Load tooltips
		rseventsproHelper::tooltipLoad();
		
		// Load the JHTML class
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/html.php';
		
		// Load emails class
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/emails.php';
		
		// Delete incomplete events
		rseventsproHelper::incomplete();
		
		// Archive events
		rseventsproHelper::archive();
		
		// Payment rules
		rseventsproHelper::rules();
	}
	
	// Load language files
	public static function loadLang($system = false) {
		$lang = JFactory::getLanguage();
		$from = JFactory::getApplication()->isClient('administrator') ? JPATH_ADMINISTRATOR : JPATH_SITE;
		
		$lang->load('com_rseventspro', $from, 'en-GB', true);
		$lang->load('com_rseventspro', $from, $lang->getDefault(), true);
		$lang->load('com_rseventspro', $from, null, true);
		
		if ($system) {
			$lang->load('com_rseventspro.sys', $from, 'en-GB', true);
			$lang->load('com_rseventspro.sys', $from, $lang->getDefault(), true);
			$lang->load('com_rseventspro.sys', $from, null, true);
		}
	}
	
	// Load scripts
	public static function loadScripts() {
		$doc	= JFactory::getDocument();
		$app	= JFactory::getApplication();
		$view	= $app->input->get('view');
		
		// Load jQuery
		self::loadjQuery();
		
		// Load FontAwesome
		self::loadFA();
		
		$doc->addScriptDeclaration("var rsepro_root = '".addslashes(JURI::root(true).'/'.($app->isClient('administrator') ? 'administrator/' : ''))."';");
		
		// Load admin or site scripts
		if ($app->isClient('administrator')) {
			// Add CSS files
			JHtml::stylesheet('com_rseventspro/admin.css', array('relative' => true, 'version' => 'auto'));
			
			// Add JS files
			JHtml::script('com_rseventspro/admin.js', array('relative' => true, 'version' => 'auto'));
		} else {
			// Load Bootstrap
			self::loadBootstrap();
			
			// Add CSS files
			JHtml::stylesheet('com_rseventspro/site.css', array('relative' => true, 'version' => 'auto'));
			
			// Add JS files
			if ($doc->getType() == 'html') {
				$doc->addCustomTag('<script src="'.JHtml::script('com_rseventspro/site.js', array('relative' => true, 'pathOnly' => true, 'version' => 'auto')).'" type="text/javascript"></script>');
			}
		}
	}
	
	// Load jQuery
	public static function loadjQuery($noconflict = true) {
		$admin	 = JFactory::getApplication()->isClient('administrator') ? 'admin' : '';
		$enabled = rseventsproHelper::getConfig($admin.'jquery','int',0);
		
		if ($enabled) {
			JHtml::_('jquery.framework', $noconflict);
		}
	}
	
	// Load bootstrap
	public static function loadBootstrap($force = false) {
		$document = JFactory::getDocument();
		
		if (rseventsproHelper::getConfig('bootstrap','int',0) || $force) {
			JHtml::_('bootstrap.framework');
			JHtmlBootstrap::loadCss(true);
		}
		
		if ($document->getType() == 'html') {
			$document->addCustomTag('<script src="'.JHtml::script('com_rseventspro/bootstrap.fix.js', array('relative' => true, 'pathOnly' => true, 'version' => 'auto')).'" type="text/javascript"></script>');
		}
	}
	
	// Load FontAwesome
	public static function loadFA() {
		if (rseventsproHelper::getConfig('fontawesome','int',1)) {
			JHtml::stylesheet('com_rseventspro/font-awesome.min.css', array('relative' => true, 'version' => 'auto'));
		}
	}
	
	// Delete incomplete events
	public static function incomplete() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$config	= rseventsproHelper::getConfig();
		$now	= new DateTime('now', new DateTimezone('UTC'));
		$unix	= $now->format('U');
		
		if (!$config->incomplete || $config->incomplete_minutes_check + 60 > $unix) {
			return;
		}
		
		$query->clear()
			->update($db->qn('#__rseventspro_config'))
			->set($db->qn('value').' = '.$db->q($unix))
			->where($db->qn('name').' = '.$db->q('incomplete_minutes_check'));
		
		$db->setQuery($query);
		$db->execute();
		
		$seconds = ((int) $config->incomplete_minutes) * 60;
		
		$query->clear()
				->select($db->qn('id'))
				->from($db->qn('#__rseventspro_events'))
				->where($db->qn('completed').' = '.$db->q(0))
				->where($db->q($now->format('Y-m-d H:i:s')).' > '.$db->qn('created').' + INTERVAL '.(int) $seconds.' SECOND');
		
		$db->setQuery($query);
		if ($events = $db->loadColumn()) {
			foreach($events as $cid) {
				rseventsproHelper::remove($cid);
			}
		}
	}
	
	// Auto-archive events
	public static function archive() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$config	= rseventsproHelper::getConfig();
		$now	= new DateTime('now', new DateTimezone('UTC'));
		$unix	= $now->format('U');
		$container = array();
		
		if (!$config->auto_archive || (int) $config->archive_check + 120 > $unix) {
			return false;
		}
		
		$query->clear()
			->update($db->qn('#__rseventspro_config'))
			->set($db->qn('value').' = '.$db->q($unix))
			->where($db->qn('name').' = '.$db->q('archive_check'));
		
		$db->setQuery($query);
		$db->execute();
		
		$seconds = rseventsproHelper::getConfig('archive_days','int') * 86400;
		
		$query->clear()
				->update($db->qn('#__rseventspro_events'))
				->set($db->qn('archived').' = '.$db->q(1))
				->set($db->qn('published').' = '.$db->q(2))
				->where($db->q($now->format('Y-m-d H:i:s')).' > '.$db->qn('end').' + INTERVAL '.(int) $seconds.' SECOND')
				->where($db->qn('end').' <> '.$db->q($db->getNullDate()))
				->where($db->qn('archived').' = '.$db->q(0));
		$db->setQuery($query);
		$db->execute();
		
		$query->clear()
			->select($db->qn('id'))->select($db->qn('start'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('allday').' = 1');
		
		$db->setQuery($query);
		if ($allDayEvents = $db->loadObjectList()) {
			if (empty($seconds)) {
				$seconds = 86400;
			}
			
			foreach ($allDayEvents as $event) {
				$start	= new DateTime($event->start, new DateTimezone('UTC'));
				$start	= $start->format('U');
				
				if ($unix > $start + $seconds)
					$container[] = $event->id;
			}
		}
		
		if (!empty($container)) {
			$container = array_map('intval',$container);
			JFactory::getApplication()->triggerEvent('rsepro_beforeArchive',array(array('events'=>&$container)));
			
			$query->clear()
				->update($db->qn('#__rseventspro_events'))
				->set($db->qn('archived').' = '.$db->q(1))
				->set($db->qn('published').' = '.$db->q(2))
				->where($db->qn('id').' IN ('.implode(',',$container).')')
				->where($db->qn('archived').' = '.$db->q(0));
			
			$db->setQuery($query);
			$db->execute();
		}
		
		return true;
	}
	
	// Payment rules
	public static function rules() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$config	= rseventsproHelper::getConfig();
		$now	= JFactory::getDate();
		$unix	= $now->toUnix();
		
		if ((int) $config->rules_check + 300 > $unix) {
			return;
		}
		
		$query->clear()
			->update($db->qn('#__rseventspro_config'))
			->set($db->qn('value').' = '.$db->q($unix))
			->where($db->qn('name').' = '.$db->q('rules_check'));
		
		$db->setQuery($query);
		$db->execute();
		
		// Get rules
		$query->clear()
			->select('*')
			->from($db->qn('#__rseventspro_rules'))
			->order($db->qn('payment').' DESC')
			->order($db->qn('interval').' ASC');
		
		$db->setQuery($query);
		$rules = $db->loadObjectList();
		
		if (empty($rules)) 
			return;
		
		foreach ($rules as $rule) {
			$interval = (int) $rule->interval;
			
			$query->clear()
				->select($db->qn('id'))
				->from($db->qn('#__rseventspro_users'))
				->where($db->qn('state').' = '.(int) $rule->status)
				->where($db->qn('gateway').' = '.$db->q($rule->payment))
				->where($db->q($now->toSql()).' > DATE_ADD('.$db->qn('date').', INTERVAL '.$interval.' HOUR)');
			
			$db->setQuery($query);
			$subscribers = $db->loadColumn();
			
			if (empty($subscribers)) 
				continue;
			
			foreach ($subscribers as $subscriber) {
				// Approve user
				if ($rule->rule == 1)
					rseventsproHelper::confirm($subscriber);
				// Deny user
				else if ($rule->rule == 2)
					rseventsproHelper::denied($subscriber);
				// Delete user
				else if ($rule->rule == 3) {
					$query->clear()
						->delete($db->qn('#__rseventspro_user_tickets'))
						->where($db->qn('ids'). ' = '.(int) $subscriber);
					
					$db->setQuery($query);
					$db->execute();
					
					$query->clear()
						->delete($db->qn('#__rseventspro_user_seats'))
						->where($db->qn('ids').' = '.(int) $subscriber);
					
					$db->setQuery($query);
					$db->execute();
					
					$query->clear()
						->delete($db->qn('#__rseventspro_confirmed'))
						->where($db->qn('id').' = '.(int) $subscriber);
						
					$db->setQuery($query);
					$db->execute();
					
					$query->clear()
						->select($db->qn('e.id'))->select($db->qn('e.sync'))->select($db->qn('u.SubmissionId'))
						->from($db->qn('#__rseventspro_users','u'))
						->join('left', $db->qn('#__rseventspro_events','e').' ON '.$db->qn('e.id').' = '.$db->qn('u.ide'))
						->where($db->qn('u.id').' = '.(int) $subscriber);
					
					$db->setQuery($query);
					$subscription = $db->loadObject();
					
					// Delete RSForm!Pro submission
					if (file_exists(JPATH_SITE.'/components/com_rsform/rsform.php') && $subscription->sync) {
						$query->clear()
							->delete($db->qn('#__rsform_submission_values'))
							->where($db->qn('SubmissionId').' = '.(int) $subscription->SubmissionId);
						
						$db->setQuery($query);
						$db->execute();
						
						$query->clear()
							->delete($db->qn('#__rsform_submissions'))
							->where($db->qn('SubmissionId').' = '.(int) $subscription->SubmissionId);
						
						$db->setQuery($query);
						$db->execute();
					}
					
					$query->clear()
						->delete($db->qn('#__rseventspro_users'))
						->where($db->qn('id'). ' = '.(int) $subscriber);
					
					$db->setQuery($query);
					$db->execute();
				}
				// Send reminder to user
				else if ($rule->rule == 4) {
					$query->clear()
						->select('COUNT(id)')
						->from($db->qn('#__rseventspro_taxonomy'))
						->where($db->qn('type').' = '.$db->q('rule'))
						->where($db->qn('ide').' = '.$db->q($rule->id))
						->where($db->qn('id').' = '.(int) $subscriber);
					
					$db->setQuery($query);
					if (!$db->loadResult()) {
						rseventsproEmails::rule($subscriber,$rule->mid);
						
						$query->clear()
							->insert($db->qn('#__rseventspro_taxonomy'))
							->set($db->qn('type').' = '.$db->q('rule'))
							->set($db->qn('ide').' = '.$db->q($rule->id))
							->set($db->qn('id').' = '.(int) $subscriber);
						
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
		}
	}
	
	// Create the backend submenu
	public static function subMenu() {
		$app	= JFactory::getApplication();
		$jinput = $app->input;
		$view   = $jinput->getCmd('view');
		$layout = $jinput->getCmd('layout');
		$views  = array('events','locations','categories','tags','speakers','subscriptions','discounts','payments','groups','users','imports','backup','messages','settings');
		
		$app->triggerEvent('rsepro_adminSubMenu',array(array('views' => &$views)));
		
		JHtmlSidebar::addEntry(JText::_('COM_RSEVENTSPRO_SUBMENU_DASHBOARD'), 'index.php?option=com_rseventspro',(empty($view) && empty($layout)));
		
		foreach ($views as $theview) {
			JHtmlSidebar::addEntry(JText::_('COM_RSEVENTSPRO_SUBMENU_'.strtoupper($theview)), 'index.php?option=com_rseventspro&view='.strtolower($theview), ($theview == $view) );
		}
	}
	
	// Check for paypal plugin
	public static function paypal() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('enabled'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type').' = '.$db->q('plugin'))
			->where($db->qn('folder').' = '.$db->q('system'))
			->where($db->qn('element').' = '.$db->q('rsepropaypal'));
			
		$db->setQuery($query);
		$enabled = $db->loadResult();
		
		if ($enabled && file_exists(JPATH_SITE.'/plugins/system/rsepropaypal/rsepropaypal.php'))
			return true;
		
		return false;
	}
	
	// Check for pdf plugin
	public static function pdf() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('enabled'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type').' = '.$db->q('plugin'))
			->where($db->qn('folder').' = '.$db->q('system'))
			->where($db->qn('element').' = '.$db->q('rsepropdf'));
			
		$db->setQuery($query);
		$enabled = $db->loadResult();
		
		if ($enabled && file_exists(JPATH_SITE.'/plugins/system/rsepropdf/rsepropdf.php'))
			return true;
		
		return false;
	}
	
	// Check for the iDeal plugin
	public static function ideal() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('enabled'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type').' = '.$db->q('plugin'))
			->where($db->qn('folder').' = '.$db->q('system'))
			->where($db->qn('element').' = '.$db->q('rseproideal'));
			
		$db->setQuery($query);
		$enabled = $db->loadResult();
		
		if ($enabled && file_exists(JPATH_SITE.'/plugins/system/rseproideal/rseproideal.php'))
			return true;
		
		return false;
	}
	
	// Check for RSMediaGallery!
	public static function isGallery() {
		if (file_exists(JPATH_ADMINISTRATOR.'/components/com_rsmediagallery/helpers/integration.php') && rseventsproHelper::getConfig('enable_gallery', 'int', 1)) {
			return true;
		}
		
		return false;
	}
	
	// Get available payments
	public static function getPayments($addNone = false, $available = null) {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$app	= JFactory::getApplication();
		
		$return = array();
		
		if ($addNone)
			$return[] = JHTML::_('select.option', 'none', JText::_( 'COM_RSEVENTSPRO_CONF_DEFAULT_PAYMENT_NONE' ) );				
		
		if (!is_null($available) && !empty($available)) {
			try {
				$registry = new JRegistry;
				$registry->loadString($available);
				$available = $registry->toArray();
			} catch (Exception $e) {
				$available = '';
			}
		} else $available = '';
		
		//payment plugins
		$payment_methods = $app->triggerEvent('rsepro_addOptions');
		if (!empty($payment_methods)) {
			foreach ($payment_methods as $i => $item) {
				if (empty($item->value)) continue;
				if (!empty($available) && !in_array($item->value,$available)) continue;
				$return[] = $item;
			}
		}
		
		//database payments
		$query->clear()
			->select($db->qn('id','value'))
			->select($db->qn('name','text'))
			->from($db->qn('#__rseventspro_payments'))
			->where($db->qn('published').' = '.$db->q(1));
			
		$db->setQuery($query);
		$db_payments = $db->loadObjectList();
		
		if (!empty($db_payments)) {
			foreach ($db_payments as $payment) {
				if (!empty($available) && !in_array($payment->value,$available)) continue;
				$return[] = $payment;
			}
		}
		
		return $return;
	}
	
	// Get Rules
	public static function getRules($selected = null) {
		$rules =  array( 
			JHTML::_('select.option', 1, JText::_('COM_RSEVENTSPRO_RULE_APPROVE_SUBSCRIBER')),
			JHTML::_('select.option', 2, JText::_('COM_RSEVENTSPRO_RULE_DENY_SUBSCRIBER')),
			JHTML::_('select.option', 3, JText::_('COM_RSEVENTSPRO_RULE_DELETE_SUBSCRIBER')),
			JHTML::_('select.option', 4, JText::_('COM_RSEVENTSPRO_RULE_EMAIL_SUBSCRIBER'))
		);
		
		if (!is_null($selected)) {
			foreach ($rules as $rule)
				if ($rule->value == $selected)
					return $rule->text;
		}
		
		return $rules;
	}
	
	// Get statuses
	public static function getStatuses($selected = null) {
		$statuses = array( 
			JHTML::_('select.option', 0, JText::_('COM_RSEVENTSPRO_RULE_STATUS_INCOMPLETE')),
			JHTML::_('select.option', 1, JText::_('COM_RSEVENTSPRO_RULE_STATUS_COMPLETE')),
			JHTML::_('select.option', 2, JText::_('COM_RSEVENTSPRO_RULE_STATUS_DENIED'))
		);
		
		if (!is_null($selected)) {
			foreach ($statuses as $status)
				if ($status->value == $selected)
					return $status->text;
		}
		
		return $statuses;
	}
	
	// Get the list of all locations
	public static function getLocations() {
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('id','value'))->select($db->qn('name','text'))
			->from($db->qn('#__rseventspro_locations'))
			->where($db->qn('published').' = '.$db->q(1))
			->order($db->qn('name').' ASC');
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	// Get the list of all tags
	public static function getTags() {
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('id','value'))->select($db->qn('name','text'))
			->from($db->qn('#__rseventspro_tags'))
			->where($db->qn('published').' = '.$db->q(1))
			->order($db->qn('name').' ASC');
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	// Get the list of all events
	public static function getEvents($date = false) {
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('id','value'))->select($db->qn('name','text'))->select('start')
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('published').' = '.$db->q(1))
			->order($db->qn('start').' ASC');
		
		$db->setQuery($query);
		$events =  $db->loadObjectList();
		
		if ($date) {
			foreach ($events as $i => $event) {
				$events[$i]->text = $event->text .' ('.rseventsproHelper::showdate($event->start).')';
			}
		}
		
		return $events;
	}
	
	// Resize images
	public static function resize($image, $width = 150, $path) {
		if (empty($image)) {
			return false;
		}
		
		jimport('joomla.filesystem.file');
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/phpthumb/phpthumb.class.php';
		
		$thumb = new phpThumb();
		$thumb->src = $image;
		if (!empty($width)) 
			$thumb->w = (int) $width;
		$thumb->q = 75;
		$thumb->config_output_format = JFile::getExt($image);
		$thumb->config_error_die_on_error = false;
		$thumb->config_cache_disable_warning = true;
		$thumb->config_allow_src_above_docroot = true;
		$thumb->cache_filename = $path;
		
		if ($thumb->GenerateThumbnail()) {
			$thumb->RenderToFile($thumb->cache_filename);
			return true;
		}
		
		return false;
	}
	
	// Crop images
	public static function crop($image, $width = 150, $path) {
		if (empty($image)) {
			return false;
		}
		
		jimport('joomla.filesystem.file');
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/phpthumb/phpthumb.class.php';
		$jinput = JFactory::getApplication()->input;
		
		$thumb = new phpThumb();
		$thumb->src = $image;
		$thumb->w = $width;
		$thumb->iar = 1;
		
		$thumb->sx = round($jinput->getInt('x1'));
		$thumb->sy = round($jinput->getInt('y1'));
		$thumb->sw = round($jinput->getInt('width'));
		$thumb->sh = round($jinput->getInt('height'));
		$thumb->zc = 0;
		$thumb->cache_filename = $path;
		
		if ($thumb->GenerateThumbnail()) {
			$thumb->RenderToFile($thumb->cache_filename);
			return true;
		}
		
		return false;
	}
	
	// Close the modal
	public static function modalClose($script = true, $parent = false) {
		$modal	= rseventsproHelper::getConfig('modal', 'int', 0);
		$html	= '';
		
		if ($modal == 0) {
			return false;
		}
		
		if ($script) {
			$html .= '<script type="text/javascript">';
		}
		
		if ($parent) {
			$html .= 'window.parent.';
		}
		
		if ($modal == 1) {
			$html .= 'jQuery.colorbox.close();';
		} else {
			$html .= 'jQuery(\'.modal.in .close\').click();';
		}
		
		if ($script) {
			$html .= '</script>';
		}
		
		return $html;
	}
	
	// Get RSMediaGallery! tags
	public static function getGalleryTags() {
		if (!rseventsproHelper::isGallery()) { 
			return array();
		}
		
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		
		$query->clear();
		$query->select('DISTINCT('.$db->qn('tag').')')
			->from($db->qn('#__rsmediagallery_tags'))
			->order($db->qn('tag').' ASC');
		
		$db->setQuery($query);
		if ($tags = $db->loadColumn()) {
			foreach ($tags as $tag)
				$return[] = JHTML::_('select.option', $tag, $tag);
			return $return;
		}
		return array();
	}
	
	// Get filter events
	public static function getFilterEvents($date = true, $registration = false, $ordering = 'ASC') {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$return = array();
		
		$query->clear()
			->select($db->qn('id'))->select($db->qn('name'))->select($db->qn('start'))
			->from($db->qn('#__rseventspro_events'))
			->order($db->qn('start').' '.$db->escape($ordering));
		
		if ($registration)
			$query->where($db->qn('registration').' = 1');
		
		$db->setQuery($query);
		if ($events = $db->loadObjectList()) {
			foreach ($events as $event) {
				if ($date) {
					$event->name .= ' ('.rseventsproHelper::showdate($event->start).')';
				}
				
				$return[] = JHtml::_('select.option', $event->id, $event->name);
			}
		}
		
		return $return;
	}
	
	// Get date	
	public static function date($input = 'now', $format = null, $replace = false, $object = false) {
		return rseventsproHelper::showdate($input, $format, $replace);
	}
	
	// Get current timezone
	public static function getTimezone() {
		$layout = JFactory::getApplication()->input->get('layout','');
		$task	= JFactory::getApplication()->input->get('task','');
		$offset	= JFactory::getConfig()->get('offset');
		
		if ($layout == 'edit' || $task == 'save') {
			return $offset;
		}
		
		$session = JFactory::getSession();
		return $session->get('rsepro.timezone', $offset);
	}
	
	// Translate the date
	public static function translatedate($date) {
		JFactory::getLanguage()->load('com_rseventspro.dates',JPATH_SITE);
		
		$replace = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday',
						 'January','February','March','April','May','June','July','August','September','October','November','December',
						 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',
						 'Mon','Tue','Wed','Thu','Fri','Sat','Sun');
		
		$with	 = array(JText::_('COM_RSEVENTSPRO_MONDAY'),JText::_('COM_RSEVENTSPRO_TUESDAY'),JText::_('COM_RSEVENTSPRO_WEDNESDAY'),JText::_('COM_RSEVENTSPRO_THURSDAY'),JText::_('COM_RSEVENTSPRO_FRIDAY'),JText::_('COM_RSEVENTSPRO_SATURDAY'),JText::_('COM_RSEVENTSPRO_SUNDAY'),	JText::_('COM_RSEVENTSPRO_JANUARY'),JText::_('COM_RSEVENTSPRO_FEBRUARY'),JText::_('COM_RSEVENTSPRO_MARCH'),JText::_('COM_RSEVENTSPRO_APRIL'),JText::_('COM_RSEVENTSPRO_MAY'),JText::_('COM_RSEVENTSPRO_JUNE'),JText::_('COM_RSEVENTSPRO_JULY'),JText::_('COM_RSEVENTSPRO_AUGUST'),JText::_('COM_RSEVENTSPRO_SEPTEMBER'),JText::_('COM_RSEVENTSPRO_OCTOBER'),JText::_('COM_RSEVENTSPRO_NOVEMBER'),JText::_('COM_RSEVENTSPRO_DECEMBER'), JText::_('COM_RSEVENTSPRO_JANUARY_SHORT'),JText::_('COM_RSEVENTSPRO_FEBRUARY_SHORT'),JText::_('COM_RSEVENTSPRO_MARCH_SHORT'),JText::_('COM_RSEVENTSPRO_APRIL_SHORT'),JText::_('COM_RSEVENTSPRO_MAY_SHORT'),JText::_('COM_RSEVENTSPRO_JUNE_SHORT'),JText::_('COM_RSEVENTSPRO_JULY_SHORT'),JText::_('COM_RSEVENTSPRO_AUGUST_SHORT'),JText::_('COM_RSEVENTSPRO_SEPTEMBER_SHORT'),JText::_('COM_RSEVENTSPRO_OCTOBER_SHORT'),JText::_('COM_RSEVENTSPRO_NOVEMBER_SHORT'),JText::_('COM_RSEVENTSPRO_DECEMBER_SHORT'), JText::_('COM_RSEVENTSPRO_MONDAY_SHORT'),JText::_('COM_RSEVENTSPRO_TUESDAY_SHORT'),JText::_('COM_RSEVENTSPRO_WEDNESDAY_SHORT'),JText::_('COM_RSEVENTSPRO_THURSDAY_SHORT'),JText::_('COM_RSEVENTSPRO_FRIDAY_SHORT'),JText::_('COM_RSEVENTSPRO_SATURDAY_SHORT'),JText::_('COM_RSEVENTSPRO_SUNDAY_SHORT'));
		
		return str_replace($replace, $with, $date);
	}
	
	// Get user tickets
	public static function getUserTickets($id, $print = false) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('ut.quantity'))->select($db->qn('t').'.*')
			->from($db->qn('#__rseventspro_user_tickets','ut'))
			->join('left', $db->qn('#__rseventspro_tickets','t').' ON '.$db->qn('t.id').' = '.$db->qn('ut.idt'))
			->where($db->qn('ut.ids').' = '. (int) $id);

		$db->setQuery($query);
		$tickets = $db->loadObjectList();
		
		// Print result
		if ($print) {
			$html = array();
			if (!empty($tickets)) {
				foreach ($tickets as $ticket) {
					if ($ticket->price > 0) {
						$html[] = $ticket->quantity. ' x '.$ticket->name.' ('.rseventsproHelper::currency($ticket->price).') <br />';
					} else {
						if ($ticket->id) {
							$html[] = $ticket->quantity. ' x '.$ticket->name.' ('.JText::_('COM_RSEVENTSPRO_GLOBAL_FREE').') <br />';
						} else {
							$html[] = $ticket->quantity. ' x '.JText::_('COM_RSEVENTSPRO_SUBSCRIPTION_FREE_ENTRANCE').' <br />';
						}
					}
				}
			}
			
			return implode("\n",$html);
		}
		
		return $tickets;
	}
	
	// Convert price
	public static function convertprice($price, $decimals = null, $decimal = null, $thousands = null) {		
		$decimals	= !is_null($decimals)	? $decimals		: rseventsproHelper::getConfig('payment_decimals','int');
		$decimal	= !is_null($decimal)	? $decimal		: rseventsproHelper::getConfig('payment_decimal');
		$thousands	= !is_null($thousands)	? $thousands	: rseventsproHelper::getConfig('payment_thousands');
		
		return number_format($price, $decimals, $decimal, $thousands); 
	}
	
	// Show formated price
	public static function currency($price, $hideprice = false, $decimals = null, $decimal = null, $thousands = null) {
		// Get the payment mask
		$mask = rseventsproHelper::getConfig('payment_mask');		
		$mask = empty($mask) ? '%p %c' : $mask;
		
		// Get the currency
		$currency = rseventsproHelper::getConfig('payment_currency_sign');
		$currency = empty($currency) ? rseventsproHelper::getConfig('payment_currency') : $currency;
		
		// Convert price
		$price = $hideprice ? '{price}' : rseventsproHelper::convertprice($price, $decimals, $decimal, $thousands);
		
		// Return the payment
		return str_replace(array('%p','%c'),array($price,$currency),$mask);
	}
	
	// Get payment name
	public static function getPayment($pid) {
        $app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('name'))
			->from($db->qn('#__rseventspro_payments'))
			->where($db->qn('id').' = '.(int) $pid);
		
        $db->setQuery($query);
        $name =  $db->loadResult();
       
        if (!empty($name)) {
            return $name;
		}
       
        $gateway = $app->triggerEvent('rsepro_name',array(array('gateway' => $pid)));
       
        if (!empty($gateway) && isset($gateway[0])) {
            return $gateway[0];
		}
       
        return $pid == 'twoco' ? '2Checkout' : ucfirst($pid);
	}
	
	// Send activation email
	public static function confirm($ids, $registration = false, $update = true) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$total	= 0;
		$uid	= 0;
		$info	= '';
		
		// Load language
		JFactory::getLanguage()->load('com_rseventspro',JPATH_SITE);
		
		// Get the subscriber user ID
		$query->clear()->select($db->qn('idu'))->from($db->qn('#__rseventspro_users'))->where($db->qn('id').' = '.(int) $ids);
		$db->setQuery($query);
		$idSubscriber = (int) $db->loadResult();
		
		$create_user = rseventsproHelper::getConfig('create_user','int');
		if ($create_user == 2 && !$idSubscriber) {
			$query->clear()
				->select($db->qn('idu'))->select($db->qn('email'))->select($db->qn('name'))
				->from($db->qn('#__rseventspro_users'))
				->where($db->qn('create_user').' = 1')
				->where($db->qn('id').' = '.(int) $ids);
			$db->setQuery($query);
			$subscriber = $db->loadObject();
			
			if (!is_null($subscriber) && empty($subscriber->idu)) {
				$uid = rseventsproHelper::returnUser($subscriber->email,$subscriber->name);
			}
		}
		
		if ($update) {
			// Set the confirm status
			$query->clear()
				->update($db->qn('#__rseventspro_users'))
				->set($db->qn('state').' = 1')
				->where($db->qn('id').' = '.(int) $ids);
			
			if ($create_user == 2 && $uid) {
				$query->set($db->qn('idu').' = '.(int) $uid);
			}
			
			$db->setQuery($query);
			$db->execute();
		} else {
			if ($create_user == 2 && $uid) {
				$query->clear()
					->update($db->qn('#__rseventspro_users'))
					->set($db->qn('idu').' = '.(int) $uid)
					->where($db->qn('id').' = '.(int) $ids);
				
				$db->setQuery($query);
				$db->execute();
			}
		}
		
		JFactory::getApplication()->triggerEvent('rsepro_afterSubscriberConfirm',array(array('ids'=>$ids)));
		
		// Get subscription details
		$query->clear()
			->select($db->qn('ide'))->select($db->qn('name'))->select($db->qn('email'))
			->select($db->qn('discount'))->select($db->qn('early_fee'))->select($db->qn('late_fee'))
			->select($db->qn('tax'))->select($db->qn('gateway'))->select($db->qn('ip'))->select($db->qn('coupon'))
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('id').' = '.(int) $ids);
		
		$db->setQuery($query);
		$subscription = $db->loadObject();
		
		// Get tickets
		$tickets = rseventsproHelper::getUserTickets($ids);
		
		if (!empty($tickets)) {
			foreach ($tickets as $ticket) {
				// Calculate the total
				if ($ticket->price > 0) {
					$price = $ticket->price * $ticket->quantity;
					$total += $price;
					$info .= $ticket->quantity . ' x ' .$ticket->name.' ('.rseventsproHelper::currency($ticket->price).') '.rseventsproHelper::getSeats($ids,$ticket->id).' <br />';
				} else {
					$info .= $ticket->quantity . ' x ' .$ticket->name.' ('.JText::_('COM_RSEVENTSPRO_GLOBAL_FREE').') <br />';
				}
			}
		}
		
		if (!empty($subscription->discount) && !empty($total)) {
			$total = $total - $subscription->discount;
		}
		
		if (!empty($subscription->early_fee) && !empty($total)) {
			$total = $total - $subscription->early_fee;
		}
		
		if (!empty($subscription->late_fee) && !empty($total)) {
			$total = $total + $subscription->late_fee;
		}
		
		if (!empty($subscription->tax) && !empty($total)) {
			$total = $total + $subscription->tax;
		}
		
		$discount			= (int) @$subscription->discount;
		$tax				= (int) @$subscription->tax;
		$late				= (int) @$subscription->late_fee;
		$early				= (int) @$subscription->early_fee;
		$ticketstotal		= rseventsproHelper::currency($total);
		$ticketsdiscount	= rseventsproHelper::currency($discount);
		$subscriptionTax	= rseventsproHelper::currency($tax);
		$lateFee			= rseventsproHelper::currency($late);
		$earlyDiscount		= rseventsproHelper::currency($early);
		$gateway			= rseventsproHelper::getPayment($subscription->gateway);
		$IP					= $subscription->ip;
		$coupon				= !empty($subscription->coupon) ? $subscription->coupon : '';
		$optionals			= array($info, $ticketstotal, $ticketsdiscount, $subscriptionTax, $lateFee, $earlyDiscount, $gateway, $IP, $coupon);
		$override			= false;
		
		JFactory::getApplication()->triggerEvent('rsepro_cartEmails', array(array('ids' => $ids, 'override' => &$override, 'registration' => $registration)));
		
		if (!$override) {
			if ($registration) {
				rseventsproEmails::registration($subscription->email, $subscription->ide, $subscription->name, $optionals, $ids);
			} else {
				rseventsproEmails::activation($subscription->email, $subscription->ide, $subscription->name, $optionals, $ids);
			}
		}
		
		// JomSocial
		if (file_exists(JPATH_BASE.'/components/com_community/libraries/core.php')) {
			$query->clear()
				->select($db->qn('name'))->select($db->qn('owner'))
				->from($db->qn('#__rseventspro_events'))
				->where($db->qn('id').' = '.$db->q($subscription->ide));
			$db->setQuery($query);
			if ($event = $db->loadObject()) {				
				$query->clear()
					->select($db->qn('id'))
					->from($db->qn('#__community_activities'))
					->where($db->qn('actor').' = '.$db->q($event->owner))
					->where($db->qn('app').' = '.$db->q('rseventspro'))
					->where($db->qn('params').' = '.$db->q('register'))
					->where($db->qn('cid').' = '.$db->q($subscription->ide));
				
				$db->setQuery($query);
				$activity = $db->loadResult();
				
				if (empty($activity) && rseventsproHelper::getConfig('jsactivity','int')) {
					require_once JPATH_SITE.'/components/com_rseventspro/helpers/route.php';
					require_once JPATH_BASE.'/components/com_community/libraries/core.php';
					
					JFactory::getLanguage()->load('com_rseventspro');
					
					$eitemid  = RseventsproHelperRoute::getEventsItemid();
					$jsitemid = rseventsproHelper::itemid($subscription->ide);
					$jsitemid = empty($jsitemid) ? $eitemid : $jsitemid;
					
					$root	= JURI::getInstance()->toString(array('scheme','host'));
					$link 	= '<a href="'.$root.rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($subscription->ide, $event->name),true,$jsitemid).'">'.$event->name.'</a>';
					
					$act = new stdClass();
					$act->cmd		= 'rseventspro.register';
					$act->actor		= $event->owner;
					$act->target	= $event->owner;
					$act->title		= JText::sprintf('COM_RSEVENTSPRO_JOMSOCIAL_ACTIVITY_JOIN',$link);
					$act->app		= 'rseventspro';
					$act->cid		= $subscription->ide;
					
					CFactory::load('libraries', 'activities');
					$act->comment_type  = 'rseventspro.addcomment';
					$act->comment_id    = CActivities::COMMENT_SELF;

					$act->like_type     = 'rseventspro.like';
					$act->like_id     = CActivities::LIKE_SELF;
					
					CActivities::add($act,'register');
				}
			}
		}
		
		return true;
	}
	
	// Send denied email
	public static function denied($ids) {
		$db	= JFactory::getDbo();
		$query = $db->getQuery(true);
		
		// Load language
		JFactory::getLanguage()->load('com_rseventspro',JPATH_SITE);
		
		// Set the denied status
		$query->clear()
			->update($db->qn('#__rseventspro_users'))
			->set($db->qn('state').' = 2')
			->where($db->qn('id').' = '.(int) $ids);
			
		$db->setQuery($query);
		$db->execute();
		
		// Get subscription details
		$query->clear()
			->select($db->qn('ide'))->select($db->qn('name'))
			->select($db->qn('email'))
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('id').' = '.(int) $ids);
			
		$db->setQuery($query);
		$subscription = $db->loadObject();
		
		$override = false;
		
		JFactory::getApplication()->triggerEvent('rsepro_cartEmails', array(array('ids' => $ids, 'override' => &$override, 'denied' => true)));
		
		if (!$override) {
			rseventsproEmails::denied($subscription->email, $subscription->ide, $subscription->name, $ids);
		}
		
		return true;
	}
	
	// Export subscribers
	public static function exportSubscribersCSV($query) {
		$db		= JFactory::getDbo();
		$id		= JFactory::getApplication()->input->getInt('id', 0);
		$csv	= '';
		
		if (!$id || !$query)
			return;
		
		JFactory::getApplication()->triggerEvent('rsepro_exportEventSubscribers', array(array('query' => $query)));
		
		$db->setQuery($query);
		$subscribers = $db->loadObjectList();
		
		if (rseventsproHelper::getConfig('export_headers')) {
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_ID').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_NAME').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_EMAIL').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_EXPORT_HEADER_DATE').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_IP').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_STATE').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_PAYMENT').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_EXPORT_HEADER_TICKETS').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_EXPORT_HEADER_TICKET_PRICE').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_EXPORT_HEADER_TICKET_CODE').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_EXPORT_HEADER_TICKET_CONFIRMED').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_EXPORT_HEADER_TOTAL').'"';
			
			if (file_exists(JPATH_SITE.'/components/com_rsform/rsform.php')) {
				$query = $db->getQuery(true);
				$query->clear()
					->select($db->qn('form'))
					->from($db->qn('#__rseventspro_events'))
					->where($db->qn('id').' = '.$id);
				
				$db->setQuery($query);
				if ($formId = (int) $db->loadResult()) {
					$headers = rseventsproHelper::getHeaders($formId);
					$csv .= ','.'"'.implode('","',$headers).'"';
				}
			}
			
			$csv .= "\n";
		}
		
		if (!empty($subscribers)) {
			foreach ($subscribers as $subscriber) {
				$total				= 0;
				$tickets			= array();
				$purchasedTickets	= rseventsproHelper::getUserTickets($subscriber->id);
				
				if (!empty($purchasedTickets)) { 
					foreach ($purchasedTickets as $ticket) {
						
						if ($ticket->price > 0) {
							$total += (int) $ticket->quantity * $ticket->price;
						}
						
						for ($j=0;$j<$ticket->quantity;$j++) {
							$ticket = clone $ticket;
							
							if (!$ticket->id) {
								$ticket->name = JText::_('COM_RSEVENTSPRO_FREE_ENTRANCE');
							}
							
							$code	= md5($subscriber->id.$ticket->id.($j+1));
							$code	= substr($code,0,4).substr($code,-4);
							$code	= rseventsproHelper::getBarcodeOptions('barcode_prefix', 'RST-').$subscriber->id.'-'.$code;
							$code	= in_array(rseventsproHelper::getBarcodeOptions('barcode', 'C39'), array('C39', 'C93')) ? strtoupper($code) : $code;
							
							$ticket->code = $ticket->id ? $code : '-';
							$tickets[] = $ticket;
						}
					}
				}
				
				if ($subscriber->discount) {
					$total = $total - $subscriber->discount;
				}
				if ($subscriber->early_fee) {
					$total = $total - $subscriber->early_fee;
				}
				if ($subscriber->late_fee) {
					$total = $total + $subscriber->late_fee;
				}
				if ($subscriber->tax) {
					$total = $total + $subscriber->tax;
				}
				
				foreach ($tickets as $ticket) { 
					$csv .= '"'.$db->escape($subscriber->id).'",';
					$csv .= '"'.$db->escape($subscriber->name).'",';
					$csv .= '"'.$db->escape($subscriber->email).'",';
					$csv .= '"'.$db->escape(rseventsproHelper::showdate($subscriber->date,'Y-m-d H:i:s')).'",';
					$csv .= '"'.$db->escape($subscriber->ip).'",';
					$csv .= '"'.$db->escape(rseventsproHelper::getStatuses($subscriber->state)).'",';
					$csv .= '"'.$db->escape(rseventsproHelper::getPayment($subscriber->gateway)).'",';
					$csv .= '"'.$db->escape($ticket->name).'",';
					$csv .= '"'.$db->escape(rseventsproHelper::currency($ticket->price)).'",';
					$csv .= '"'.$db->escape($ticket->code).'",';
					$csv .= '"'.$db->escape(rseventsproHelper::confirmed($subscriber->id, $ticket->code) ? JText::_('JYES') : JText::_('JNO')).'",';
					$csv .= '"'.$db->escape(rseventsproHelper::currency($total)).'"';
					
					if ($subscriber->SubmissionId) {
						if ($submissions = rseventsproHelper::getSubmission($subscriber->SubmissionId)) {
							$csv .= ','.'"'.implode('","',$submissions).'"';
						}
					}
					
					$csv .= "\n";
				}
			}
		}
		
		$file = 'Event'.$id.'.csv';
		header("Content-type: text/csv; charset=UTF-8");
		header("Content-Disposition: attachment; filename=$file");
		echo rtrim($csv,"\n");
		JFactory::getApplication()->close();
	}
	
	// Get RSForm!Pro submissions
	public static function getSubmission($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$secret = JFactory::getConfig()->get('secret');
		$data	= array();
		
		if (!file_exists(JPATH_SITE.'/components/com_rsform/rsform.php'))
			return false;
		
		$query->clear()
			->select($db->qn('FormId'))
			->from($db->qn('#__rsform_submissions'))
			->where($db->qn('SubmissionId').' = '.(int) $id);
		
		$db->setQuery($query);
		$formId = $db->loadResult();
		
		if (!$formId)
			return false;
		
		$query->clear()
			->select($db->qn('MultipleSeparator'))->select($db->qn('TextareaNewLines'))
			->from($db->qn('#__rsform_forms'))
			->where($db->qn('FormId').' = '.(int) $formId);
		
		$db->setQuery($query);
		$form = $db->loadObject();
		
		if (empty($form))
			return false;
		
		$form->MultipleSeparator = str_replace(array('\n', '\r', '\t'), array("\n", "\r", "\t"), $form->MultipleSeparator);
		
		$query->clear()
			->select($db->qn('c.ComponentTypeId'))->select($db->qn('p.ComponentId'))
			->select($db->qn('p.PropertyName'))->select($db->qn('p.PropertyValue'))
			->from($db->qn('#__rsform_components','c'))
			->join('left',$db->qn('#__rsform_properties','p').' ON '.$db->qn('c.ComponentId').' = '.$db->qn('p.ComponentId'))
			->where($db->qn('c.FormId').' = '.(int) $formId)
			->where($db->qn('c.Published').' = 1')
			->where($db->qn('p.PropertyName').' IN ('.$db->q('NAME').','.$db->q('WYSIWYG').')');
		
		$db->setQuery($query);
		$components = $db->loadObjectList();			
		$uploadFields 	= array();
		$multipleFields = array();
		$textareaFields = array();
		
		foreach ($components as $component) {
			// Upload fields
			if ($component->ComponentTypeId == 9) {
				$uploadFields[] = $component->PropertyValue;
			}
			// Multiple fields
			elseif (in_array($component->ComponentTypeId, array(3, 4))) {
				$multipleFields[] = $component->PropertyValue;
			}
			// Textarea fields
			elseif ($component->ComponentTypeId == 2) {
				if ($component->PropertyName == 'WYSIWYG' && $component->PropertyValue == 'NO')
					$textareaFields[] = $component->ComponentId;
			}
		}
		
		if (!empty($textareaFields)) {
			$query->clear()
				->select($db->qn('p.PropertyValue'))
				->from($db->qn('#__rsform_components','c'))
				->join('left',$db->qn('#__rsform_properties','p').' ON '.$db->qn('c.ComponentId').' = '.$db->qn('p.ComponentId'))
				->where($db->qn('c.ComponentId').' IN ('.implode(',',$textareaFields).')');
			
			$db->setQuery($query);
			$textareaFields = $db->loadColumn();
		}
		
		$query->clear()
			->select($db->qn('p.PropertyValue'))
			->from($db->qn('#__rsform_components','c'))
			->join('left',$db->qn('#__rsform_properties','p').' ON ('.$db->qn('c.ComponentId').' = '.$db->qn('p.ComponentId').' AND '.$db->qn('p.PropertyName').' = '.$db->q('NAME').' )')
			->join('left',$db->qn('#__rsform_component_types','ct').' ON '.$db->qn('c.ComponentTypeId').' = '.$db->qn('ct.ComponentTypeId'))
			->where($db->qn('c.FormId').' = '.(int) $formId)
			->where($db->qn('c.Published').' = 1')
			->where($db->qn('ct.ComponentTypeName').' NOT IN ('.$db->q('button').','.$db->q('captcha').','.$db->q('freeText').','.$db->q('imageButton').','.$db->q('submitButton').')')
			->where($db->qn('p.PropertyValue').' NOT IN ('.$db->q('RSEProName').','.$db->q('RSEProEmail').','.$db->q('RSEProTickets').','.$db->q('RSEProPayment').','.$db->q('RSEProCoupon').')')
			->order($db->qn('c.Order'));
		
		$db->setQuery($query);
		$headers = $db->loadColumn();
		ksort($headers);
		
		$query->clear()
			->select('*')
			->from($db->qn('#__rsform_submission_values'))
			->where($db->qn('SubmissionId').' = '.(int) $id);
		
		$db->setQuery($query);
		$results = $db->loadObjectList();
		
		if (!empty($results)) {
			$values = array();
			foreach ($results as $result) {
				if (in_array($result->FieldName,array('RSEProName','RSEProEmail','RSEProTickets','RSEProPayment','RSEProCoupon','formId'))) 
					continue;
				
				// Check if this is an upload field
				if (in_array($result->FieldName, $uploadFields) && !empty($result->FieldValue)) {
					$result->FieldValue = JURI::root().'index.php?option=com_rsform&amp;task=submissions.view.file&amp;hash='.md5($id.$secret.$result->FieldName);
				} else {
					// Check if this is a multiple field
					if (in_array($result->FieldName, $multipleFields))
						$result->FieldValue = str_replace("\n", $form->MultipleSeparator, $result->FieldValue);
					// Transform new lines
					elseif ($form->TextareaNewLines && in_array($result->FieldName, $textareaFields))
						$result->FieldValue = nl2br($result->FieldValue);
				}
				
				$values[$result->FieldName] = $result->FieldValue;
			}
			
			foreach ($headers as $i => $header) {
				if (isset($values[$header])) {
					$values[$header] = preg_replace("/\015(\012)?/", "\012", $values[$header]);
					if (strpos($values[$header],"\n") !== false)
						$values[$header] = str_replace("\n",' ',$values[$header]);
					
					$values[$header] = str_replace(array('\\r','\\n','\\t'), array("\015","\012","\011"),$values[$header]);
					
					$data[] = $values[$header];
				} else $data[] = '';
			}
		}
		
		return $data;
	}
	
	// Get RSForm!Pro headers
	public static function getHeaders($formId) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('p.PropertyValue'))
			->from($db->qn('#__rsform_components','c'))
			->join('left',$db->qn('#__rsform_properties','p').' ON ('.$db->qn('c.ComponentId').' = '.$db->qn('p.ComponentId').' AND '.$db->qn('p.PropertyName').' = '.$db->q('NAME').' )')
			->join('left',$db->qn('#__rsform_component_types','ct').' ON '.$db->qn('c.ComponentTypeId').' = '.$db->qn('ct.ComponentTypeId'))
			->where($db->qn('c.FormId').' = '.(int) $formId)
			->where($db->qn('c.Published').' = 1')
			->where($db->qn('ct.ComponentTypeName').' NOT IN ('.$db->q('button').','.$db->q('captcha').','.$db->q('freeText').','.$db->q('imageButton').','.$db->q('submitButton').')')
			->where($db->qn('p.PropertyValue').' NOT IN ('.$db->q('RSEProName').','.$db->q('RSEProEmail').','.$db->q('RSEProTickets').','.$db->q('RSEProPayment').','.$db->q('RSEProCoupon').')')
			->order($db->qn('c.Order'));
		
		$db->setQuery($query);
		$headers = $db->loadColumn();
		ksort($headers);
		
		return $headers;
	}
	
	// Get Card details
	public static function getCardDetails($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$return = new stdClass();
		$secret	= JFactory::getConfig()->get('secret');
		$newPHP	= false;
		
		$query->clear()
			->select('*')
			->from($db->qn('#__rseventspro_cards'))
			->where($db->qn('ids').' = '.$id);
		
		$db->setQuery($query);
		$details = $db->loadObject();
		
		if (!empty($details)) {
			require_once JPATH_SITE.'/components/com_rseventspro/helpers/crypt.php';
			
			$name	= $details->card_fname. ' '.$details->card_lname;
			$key	= base64_encode(md5($id.$secret));
			$crypt	= new RseventsproCryptHelper($key);
			
			$cryptedCardMessage = base64_decode($details->card_number);
			$cryptedCscMessage = base64_decode($details->card_csc);
				
			if (strpos($cryptedCardMessage, '::') === false) {
				if (version_compare(phpversion(), '7.2', '>')) {
					$newPHP = true;
				} else {
					$oldCrypt = new RseventsproCryptHelperLegacy(null, null, $name);
					$cc_number = $oldCrypt->decrypt($details->card_number);
					
					if ($cc_number) {
						$new_cc_number = $crypt->encrypt($cc_number);
						$query->clear()
							->update($db->qn('#__rseventspro_cards'))
							->set($db->qn('card_number').' = '.$db->q($new_cc_number))
							->where($db->qn('id').' = '.$db->q($details->id));
						$db->setQuery($query);
						$db->execute();
					}
				}
			} else {
				$cc_number = $crypt->decrypt($details->card_number);
			}
				
			if (strpos($cryptedCscMessage, '::') === false) {
				if (version_compare(phpversion(), '7.2', '>')) {
					$newPHP = true;
				} else {
					$oldCrypt = new RseventsproCryptHelperLegacy(null, null, $name);
					$cc_csc = $oldCrypt->decrypt($details->card_csc);
					
					if ($cc_csc) {
						$new_cc_csc = $crypt->encrypt($cc_csc);
						$query->clear()
							->update($db->qn('#__rseventspro_cards'))
							->set($db->qn('card_csc').' = '.$db->q($new_cc_csc))
							->where($db->qn('id').' = '.$db->q($details->id));
						$db->setQuery($query);
						$db->execute();
					}
				}
			} else {
				$cc_csc = $crypt->decrypt($details->card_csc);
			}
				
			if ($newPHP) {
				JFactory::getApplication()->enqueueMessage(JText::_('COM_RSEVENTSPRO_MYCRYPT_ERROR'));
			}
			
			$return->card_number = $cc_number;
			$return->card_csc = $cc_csc;
			$return->card_exp = $details->card_exp;
			$return->name =  $name;
		} else {
			$return->card_number = $return->card_csc = $return->card_exp = $return->name =  '';
		}
		
		return $return;
	}
	
	// Get RSForm!Pro details
	public static function getRSFormData($id) {
		if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/rsform.php')) 
			return false;
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('SubmissionId'))
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('id').' = '.$id);
		
		$db->setQuery($query);
		$SubmissionId = (int) $db->loadResult();
		
		$query->clear()
			->select($db->qn('FormId'))
			->from($db->qn('#__rsform_submission_values'))
			->where($db->qn('SubmissionId').' = '.$SubmissionId);
		
		$db->setQuery($query);
		$formId = (int) $db->loadResult();
		
		
		$query->clear()
			->select($db->qn('p.PropertyValue'))
			->from($db->qn('#__rsform_components','c'))
			->join('left',$db->qn('#__rsform_properties','p').' ON ('.$db->qn('c.ComponentId').' = '.$db->qn('p.ComponentId').' AND '.$db->qn('p.PropertyName').' = '.$db->q('NAME').' )')
			->join('left',$db->qn('#__rsform_component_types','ct').' ON '.$db->qn('c.ComponentTypeId').' = '.$db->qn('ct.ComponentTypeId'))
			->where($db->qn('c.FormId').' = '.(int) $formId)
			->where($db->qn('c.Published').' = 1')
			->order($db->qn('c.Order'));
		
		$db->setQuery($query);
		$headers = $db->loadColumn();
		
		$query->clear()
			->select('DISTINCT '.$db->qn('FieldName'))
			->select($db->qn('FieldValue'))
			->from($db->qn('#__rsform_submission_values'))
			->where($db->qn('SubmissionId').' = '.$SubmissionId);
		
		$db->setQuery($query);
		$values = $db->loadObjectList();
		
		$fields		= array();
		$captions	= array();
		
		if (!empty($values)) {
			foreach ($headers as $header) {
				foreach ($values as $value) {
					if ($value->FieldName == $header) {
						if (strpos($value->FieldValue,JPATH_SITE) !== FALSE) {
							$value->FieldValue = str_replace(array(JPATH_SITE,'/'),array(substr(JURI::root(),0,-1),'/'),$value->FieldValue);
						}
						$fields[$value->FieldName] = $value->FieldValue;
					}
				}
			}
			unset($fields['RSEProName'],$fields['RSEProEmail'],$fields['RSEProTickets'],$fields['RSEProPayment'],$fields['RSEProCoupon'] ,$fields['formId']);
		}
		
		if (!empty($fields)) {
			foreach ($fields as $fieldname => $fieldvalue) {
				$query->clear()
					->select($db->qn('c.ComponentId'))
					->from($db->qn('#__rsform_properties','p'))
					->join('LEFT',$db->qn('#__rsform_components','c').' ON '.$db->qn('c.ComponentId').' = '.$db->qn('p.ComponentId'))
					->where($db->qn('c.FormId').' = '.$db->q($formId))
					->where($db->qn('PropertyName').' = '.$db->q('NAME'))
					->where($db->qn('PropertyValue').' = '.$db->q($fieldname))
					->where($db->qn('c.ComponentTypeId').' NOT IN (7,8,12,13,41)');
				$db->setQuery($query);
				$cid = (int) $db->loadResult();
				
				$query->clear()
					->select($db->qn('PropertyValue'))
					->from($db->qn('#__rsform_properties'))
					->where($db->qn('PropertyName').' = '.$db->q('CAPTION'))
					->where($db->qn('ComponentId').' = '.$db->q($cid));
				
				$db->setQuery($query);
				$value = $db->loadResult();
				
				$captions[$fieldname] = array('name' => $value, 'value' => $fieldvalue);
			}
		}
		
		return $captions;
	}
	
	// Get filter translation table
	public static function translate($text) {
		switch ($text) {
			// columns & ordering
			case 'events':
				return JText::_('COM_RSEVENTSPRO_FILTER_NAME');
			break;
			
			case 'description':
				return JText::_('COM_RSEVENTSPRO_FILTER_DESCRIPTION');
			break;
			
			case 'locations':
				return JText::_('COM_RSEVENTSPRO_FILTER_LOCATION');
			break;
			
			case 'categories':
				return JText::_('COM_RSEVENTSPRO_FILTER_CATEGORY');
			break;
			
			case 'tags':
				return JText::_('COM_RSEVENTSPRO_FILTER_TAG');
			break;
			
			// operators
			case 'is':
				return JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_IS');
			break;
			
			case 'isnot':
				return JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_ISNOT');
			break;
			
			case 'contains':
				return JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_CONTAINS');
			break;
			
			case 'notcontain':
				return JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_NOTCONTAINS');
			break;
		}
	}
	
	// Get event categories
	public static function categories($id, $print = false) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('c.id'))->select($db->qn('c.title'))
			->from($db->qn('#__categories','c'))
			->join('left', $db->qn('#__rseventspro_taxonomy','t').' ON '.$db->qn('t.id').' = '.$db->qn('c.id'))
			->where($db->qn('t.ide').' = '.(int) $id)
			->where($db->qn('t.type').' = '.$db->q('category'))
			->where($db->qn('c.extension').' = '.$db->q('com_rseventspro'));
		
		$db->setQuery($query);
		$categories = $db->loadObjectList();
		
		if ($print) {
			$html = array();
			if (!empty($categories)) {
				foreach ($categories as $category) {
					$html[] = '<a href="'.JRoute::_('index.php?option=com_rseventspro&task=category.edit&id='.$category->id, false).'">'.$category->title.'</a>';
				}
			}
			
			return implode('<br />',$html);
		}
		
		return $categories;
	}
	
	// Get event tags
	public static function tags($id, $print = false) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('t.id'))->select($db->qn('t.name'))
			->from($db->qn('#__rseventspro_tags','t'))
			->join('left',$db->qn('#__rseventspro_taxonomy','tx').' ON '.$db->qn('tx.id').' = '.$db->qn('t.id'))
			->where($db->qn('tx.ide').' = '.(int) $id)
			->where($db->qn('tx.type').' = '.$db->q('tag'));
		
		$db->setQuery($query);
		$tags = $db->loadObjectList();
		
		if ($print) {
			$html = array();
			if (!empty($tags)) {
				foreach ($tags as $tag) {
					$html[] = '<a href="'.JRoute::_('index.php?option=com_rseventspro&task=tag.edit&id='.$tag->id).'">'.$tag->name.'</a>';
				}
			}
			
			return implode(' , ',$html);
		}
		
		return $tags;
	}
	
	// Get event rating
	public static function stars($id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->clear()
			->select('CEIL(IFNULL(SUM(value)/COUNT(id),0))')
			->from($db->qn('#__rseventspro_rating'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		return (int) $db->loadResult();
	}
	
	// Get filter values
	public static function filter() {
		$jinput		= JFactory::getApplication()->input;
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$condition	= $jinput->getCmd('condition');
		$search		= $jinput->getString('search');
		$type		= $jinput->getCmd('type');
		$method		= $jinput->getCmd('method');
		$output		= $jinput->getInt('output',0);
		$table		= '';
		$column		= 'name';
		$operator	= '=';
		$html		= array();
		
		// Get table
		if ($type == 'events') {
			$table = '#__rseventspro_events';
		} elseif ($type == 'locations') {
			$table = '#__rseventspro_locations';
		} elseif ($type == 'categories') {
			$table = '#__categories';
			$column = 'title';
		} elseif ($type == 'tags') {
			$table = '#__rseventspro_tags';
		}
		
		if ($condition == 'is') {
			$operator = '=';
		} else if ($condition == 'isnot') {
			$operator = '<>';
		} else if ($condition == 'contains') {
			$operator = 'LIKE';
			$search = '%'.$search.'%';
		} else if ($condition == 'notcontain') {
			$operator = 'NOT LIKE';
			$search = '%'.$search.'%';
		}
		
		$query->clear()
			->select($db->qn($column))
			->from($db->qn($table))
			->where($db->qn($column).' '.$operator.' '.$db->q($search));
		
		if ($type == 'categories') {
			$query->where($db->qn('extension').' = '.$db->q('com_rseventspro'));
			
			if (JFactory::getApplication()->isClient('site')) {
				if (JLanguageMultilang::isEnabled()) {
					$query->where('language IN ('.$db->q(JFactory::getLanguage()->getTag()).','.$db->q('*').')');
				}
				
				$user	= JFactory::getUser();
				$groups	= implode(',', $user->getAuthorisedViewLevels());
				$query->where('access IN ('.$groups.')');
			}
		}
		
		$db->setQuery($query);
		$results = $db->loadColumn();
		
		if ($method == 'json') {
			
			if ($output) {
				$arr = array();
				
				if ($results) {
					foreach ($results as $result) {
						$arr[] = JHtml::_('select.option', $result,$result);
					}
				}
				
				if ($arr) {
					return json_encode($arr);
				}
				
				return;
			}
			
			return json_encode($results);
		}
		
		if (!empty($results)) {
			$html[] = '<li><a class="rs_close" href="javascript:void(0)" onclick="$(\'rs_results\').style.display = \'none\';"></a></li>';
			foreach ($results as $result) {
				$html[] = '<li><a href="javascript:void(0)" onclick="rs_add_option(\''.addslashes($result).'\')">'.$result.'</a></li>';
			}
		}
		
		return implode("\n", $html);
	}
	
	// Copy event
	public static function copy($eventID, $dates) {
		jimport('joomla.filesystem.file');
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->select('*')
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $eventID);
		
		$db->setQuery($query);
		$row = $db->loadObject();
		
		if (!$row) {
			return 0;
		}
		
		// Clone the parent
		$clone		= clone($row);
		$parent		= $eventID;
		
		// Get the event icon
		$query->clear()
			->select($db->qn('icon'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $parent);
		
		$db->setQuery($query);
		$icon = $db->loadResult();
		
		$copy = !is_array($dates) ? true : false;
		
		if (!is_array($dates)) {
			$dates = array();
			$newobject = new stdClass();
			$newobject->date = $row->start;
			$newobject->end = $row->end;
			$newobject->task = 'insert';
			$newobject->id = '';
			$dates[] = $newobject;
		}
		
		if (!empty($dates)) {
			// Get event categories, groups, tags
			$query->clear()
				->select($db->qn('type'))->select($db->qn('id'))
				->from($db->qn('#__rseventspro_taxonomy'))
				->where($db->qn('ide').' = '.(int) $parent)
				->where($db->qn('type').' IN ('.$db->q('category').','.$db->q('tag').','.$db->q('groups').')');
			
			$db->setQuery($query);
			$taxonomies = $db->loadObjectList();
			
			// Get event tickets
			$query->clear()
				->select($db->qn('id'))
				->select($db->qn('name'))->select($db->qn('price'))
				->select($db->qn('seats'))->select($db->qn('user_seats'))
				->select($db->qn('description'))->select($db->qn('groups'))
				->select($db->qn('attach'))->select($db->qn('layout'))
				->select($db->qn('position'))->select($db->qn('order'))
				->from($db->qn('#__rseventspro_tickets'))
				->where($db->qn('ide').' = '.(int) $parent);
			
			$db->setQuery($query);
			$tickets = $db->loadObjectList();
			
			// Get event coupons
			$query->clear()
				->select($db->qn('id'))->select($db->qn('name'))
				->select($db->qn('from'))->select($db->qn('to'))
				->select($db->qn('usage'))->select($db->qn('discount'))
				->select($db->qn('type'))->select($db->qn('action'))
				->select($db->qn('groups'))
				->from($db->qn('#__rseventspro_coupons'))
				->where($db->qn('ide').' = '.(int) $parent);
			
			$db->setQuery($query);
			$coupons = $db->loadObjectList();
			
			// Get event files
			$query->clear()
				->select($db->qn('name'))->select($db->qn('location'))
				->select($db->qn('permissions'))
				->from($db->qn('#__rseventspro_files'))
				->where($db->qn('ide').' = '.(int) $parent);
			
			$db->setQuery($query);
			$files = $db->loadObjectList();
			
			JFactory::getApplication()->triggerEvent('rsepro_copyEventVariables', array(array('vars' => &$vars, 'id' => $parent)));
			
			// Adjust Start Registration, End Registration, Unsubscribe Date, Start RSVP, End RSVP
			if ($row->start_registration == $db->getNullDate() || $row->start_registration == '') {
				$start_registration = false;
			} else {
				$start_registration	= new DateTime($row->start_registration, new DateTimezone(rseventsproHelper::getTimezone()));
			}
			
			if ($row->end_registration == $db->getNullDate() || $row->end_registration == '') {
				$end_registration = false;
			} else {
				$end_registration	= new DateTime($row->end_registration, new DateTimezone(rseventsproHelper::getTimezone()));
			}
			
			if ($row->unsubscribe_date == $db->getNullDate() || $row->unsubscribe_date == '') {
				$unsubscribe_date = false;
			} else {
				$unsubscribe_date	= new DateTime($row->unsubscribe_date, new DateTimezone(rseventsproHelper::getTimezone()));
			}
			
			if ($row->rsvp_start == $db->getNullDate() || $row->rsvp_start == '') {
				$rsvp_start = false;
			} else {
				$rsvp_start	= new DateTime($row->rsvp_start, new DateTimezone(rseventsproHelper::getTimezone()));
			}
			
			if ($row->rsvp_end == $db->getNullDate() || $row->rsvp_end == '') {
				$rsvp_end = false;
			} else {
				$rsvp_end = new DateTime($row->rsvp_end, new DateTimezone(rseventsproHelper::getTimezone()));
			}
			
			$interval	= $row->repeat_interval;
			
			$return = 0;
			$count = 1;
			foreach ($dates as $object) {
				// Get the task
				$task = $object->task;
				
				// Get the date
				$date = $object->date;
				
				// Get the end date
				$dateend = $object->end;
				
				// Get the id
				$id = $object->id;
				
				// Remove event
				if ($task == 'remove') {
					if (!empty($id)) {
						rseventsproHelper::remove($id);
					}
					continue;
				}
				
				// Get already used tickets
				$query->clear()
					->select('DISTINCT '.$db->qn('ut.idt'))
					->from($db->qn('#__rseventspro_user_tickets','ut'))
					->join('left', $db->qn('#__rseventspro_users','u').' ON '.$db->qn('u.id').' = '.$db->qn('ut.ids'))
					->where($db->qn('u.ide').' = '.(int) $id);
				
				$db->setQuery($query);
				$usedtickets = $db->loadColumn();
				
				if (!empty($usedtickets)) {
					$usedtickets = array_map('intval',$usedtickets);
				}
				
				// Update events
				if ($task == 'update') {
					// Delete categories, tags, repeating days, event groups
					$query->clear()
						->delete($db->qn('#__rseventspro_taxonomy'))
						->where($db->qn('ide').' = '.(int) $id);
					
					$db->setQuery($query);
					$db->execute();
					
					// Delete coupon codes
					$query->clear()
						->select($db->qn('id'))
						->from($db->qn('#__rseventspro_coupons'))
						->where($db->qn('ide').' = '.(int) $id);
					
					$db->setQuery($query);
					if ($couponids = $db->loadColumn()) {
						$couponids = array_map('intval',$couponids);
						$query->clear()
							->delete($db->qn('#__rseventspro_coupon_codes'))
							->where($db->qn('idc').' IN ('.implode(',',$couponids).')');
						
						$db->setQuery($query);
						$db->execute();
					}
					
					// Delete coupons
					$query->clear()
						->delete($db->qn('#__rseventspro_coupons'))
						->where($db->qn('ide').' = '.(int) $id);
					
					$db->setQuery($query);
					$db->execute();
					
					// Delete tickets
					$query->clear()
						->delete($db->qn('#__rseventspro_tickets'))
						->where($db->qn('ide').' = '.(int) $id);
					
					if (!empty($usedtickets))
						$query->where($db->qn('id').' NOT IN ('.implode(',',$usedtickets).')');
					
					$db->setQuery($query);
					$db->execute();
					
					//Get event files
					$query->clear()
						->select($db->qn('location'))
						->from($db->qn('#__rseventspro_files'))
						->where($db->qn('ide').' = '.(int) $id);
						
					$db->setQuery($query);
					$oldchildfiles = $db->loadColumn();
					
					if (!empty($oldchildfiles)) {
						foreach($oldchildfiles as $childfile) {
							if (JFile::exists(JPATH_SITE.'/components/com_rseventspro/assets/images/files/'.$childfile))
								JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/files/'.$childfile);
						}
						
						$query->clear()
							->delete($db->qn('#__rseventspro_files'))
							->where($db->qn('ide').' = '.(int) $id);
						$db->setQuery($query);
						$db->execute();
					}
					
					//Event icon
					$query->clear()
						->select($db->qn('icon'))
						->from($db->qn('#__rseventspro_events'))
						->where($db->qn('id').' = '.(int) $id);
					
					$db->setQuery($query);
					$oldicon = $db->loadResult();
					
					// Delete event icon
					if (JFile::exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$oldicon))
						JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$oldicon);
				}
				
				$cstart		= new DateTime($date, new DateTimezone(rseventsproHelper::getTimezone()));
				$clonestart	= $cstart->format('Y-m-d H:i:s');
				
				if (!$clone->allday) {
					$cend		= new DateTime($dateend, new DateTimezone(rseventsproHelper::getTimezone()));
					$cloneend	= $cend->format('Y-m-d H:i:s');
				}
				
				$clone->id				= $task == 'insert' ? null : $id;
				$clone->start			= $clonestart;
				$clone->end				= !$clone->allday ? $cloneend : $db->getNullDate();
				$clone->icon			= '';
				$clone->recurring		= '0';
				$clone->repeat_interval	= '0';
				$clone->repeat_type		= '0';
				$clone->repeat_end		= $db->getNullDate();
				$clone->repeat_also		= '';
				$clone->archived		= 0;
				$clone->hits			= 0;
				
				if ($task == 'update') {
					$query->clear()
						->select($db->qn('hits'))
						->from($db->qn('#__rseventspro_events'))
						->where($db->qn('id').' = '.$db->q($id));
					$db->setQuery($query);
					$clone->hits = (int) $db->loadResult();
				}
				
				if ($start_registration) {
					$startDate = new DateTime($row->start, new DateTimezone(rseventsproHelper::getTimezone()));
					$startDate = $startDate->format('U');
					
					$cloneStartDate = clone($cstart);
					if ($startDate > $start_registration->format('U')) {
						$sec = $startDate - $start_registration->format('U');
						if ($sec) {
							$cloneStartDate->modify('- '.$sec.' seconds');
							$clone->start_registration = $cloneStartDate->format('Y-m-d H:i:s');
						}
					} else if ($start_registration->format('U') > $startDate) {
						$sec = $start_registration->format('U') - $startDate;
						if ($sec) {
							$cloneStartDate->modify('+ '.$sec.' seconds');
							$clone->start_registration = $cloneStartDate->format('Y-m-d H:i:s');
						}
					} else if ($start_registration->format('U') == $startDate) {
						$clone->start_registration = $cloneStartDate->format('Y-m-d H:i:s');
					}
				}
				
				if ($end_registration) {
					$startDate = new DateTime($row->start, new DateTimezone(rseventsproHelper::getTimezone()));
					$startDate = $startDate->format('U');
					
					$cloneStartDate = clone($cstart);
					if ($startDate > $end_registration->format('U')) {
						$sec = $startDate - $end_registration->format('U');
						if ($sec) {
							$cloneStartDate->modify('- '.$sec.' seconds');
							$clone->end_registration = $cloneStartDate->format('Y-m-d H:i:s');
						}
					} else if ($end_registration->format('U') > $startDate) {
						$sec = $end_registration->format('U') - $startDate;
						if ($sec) {
							$cloneStartDate->modify('+ '.$sec.' seconds');
							$clone->end_registration = $cloneStartDate->format('Y-m-d H:i:s');
						}
					} else if ($end_registration->format('U') == $startDate) {
						$clone->end_registration = $cloneStartDate->format('Y-m-d H:i:s');
					}
				}
				
				if ($unsubscribe_date) {
					$startDate = new DateTime($row->start, new DateTimezone(rseventsproHelper::getTimezone()));
					$startDate = $startDate->format('U');
					
					$cloneStartDate = clone($cstart);
					if ($startDate > $unsubscribe_date->format('U')) {
						$sec = $startDate - $unsubscribe_date->format('U');
						if ($sec) {
							$cloneStartDate->modify('- '.$sec.' seconds');
							$clone->unsubscribe_date = $cloneStartDate->format('Y-m-d H:i:s');
						}
					} else if ($unsubscribe_date->format('U') > $startDate) {
						$sec = $unsubscribe_date->format('U') - $startDate;
						if ($sec) {
							$cloneStartDate->modify('+ '.$sec.' seconds');
							$clone->unsubscribe_date = $cloneStartDate->format('Y-m-d H:i:s');
						}
					}else if ($unsubscribe_date->format('U') == $startDate) {
						$clone->unsubscribe_date = $cloneStartDate->format('Y-m-d H:i:s');
					}
				}
				
				if ($rsvp_start) {
					$startDate = new DateTime($row->start, new DateTimezone(rseventsproHelper::getTimezone()));
					$startDate = $startDate->format('U');
					
					$cloneStartDate = clone($cstart);
					if ($startDate > $rsvp_start->format('U')) {
						$sec = $startDate - $rsvp_start->format('U');
						if ($sec) {
							$cloneStartDate->modify('- '.$sec.' seconds');
							$clone->rsvp_start = $cloneStartDate->format('Y-m-d H:i:s');
						}
					} else if ($rsvp_start->format('U') > $startDate) {
						$sec = $rsvp_start->format('U') - $startDate;
						if ($sec) {
							$cloneStartDate->modify('+ '.$sec.' seconds');
							$clone->rsvp_start = $cloneStartDate->format('Y-m-d H:i:s');
						}
					} else if ($rsvp_start->format('U') == $startDate) {
						$clone->rsvp_start = $cloneStartDate->format('Y-m-d H:i:s');
					}
				}
				
				if ($rsvp_end) {
					$startDate = new DateTime($row->start, new DateTimezone(rseventsproHelper::getTimezone()));
					$startDate = $startDate->format('U');
					
					$cloneStartDate = clone($cstart);
					if ($startDate > $rsvp_end->format('U')) {
						$sec = $startDate - $rsvp_end->format('U');
						if ($sec) {
							$cloneStartDate->modify('- '.$sec.' seconds');
							$clone->rsvp_end = $cloneStartDate->format('Y-m-d H:i:s');
						}
					} else if ($rsvp_end->format('U') > $startDate) {
						$sec = $rsvp_end->format('U') - $startDate;
						if ($sec) {
							$cloneStartDate->modify('+ '.$sec.' seconds');
							$clone->rsvp_end = $cloneStartDate->format('Y-m-d H:i:s');
						}
					} else if ($rsvp_end->format('U') == $startDate) {
						$clone->rsvp_end = $cloneStartDate->format('Y-m-d H:i:s');
					}
				}
				
				if ($copy) {
					$clone->parent = 0;
					$clone->name = JText::_('COM_RSEVENTSPRO_GLOBAL_COPY_OF').' '.$clone->name;
					$clone->archived = 0;
					$clone->published = 0;
				} else {
					$clone->parent		= $parent;
					$clone->archived	= 0;
				}
					
				// Save the new event
				if ($clone->id) {
					$db->updateObject('#__rseventspro_events', $clone, 'id');
				} else {
					$db->insertObject('#__rseventspro_events', $clone, 'id');
				}
				
				$return = $clone->id;
				
				// Add taxonomy
				if (!empty($taxonomies)) {
					foreach ($taxonomies as $taxonomy) {
						$query->clear()
							->insert($db->qn('#__rseventspro_taxonomy'))
							->set($db->qn('id').' = '.(int) $taxonomy->id)
							->set($db->qn('type').' = '.$db->q($taxonomy->type))
							->set($db->qn('ide').' = '.(int) $clone->id);
						
						$db->setQuery($query);
						$db->execute();
					}
				}
				
				//Add tickets
				if (!empty($tickets)) {
					foreach ($tickets as $ticket) {
						$ticketid = 0;
						if ($task == 'update') {
							if (!empty($usedtickets)) {
								$query->clear()
									->select($db->qn('id'))
									->from($db->qn('#__rseventspro_tickets'))
									->where($db->qn('name').' = '.$db->q($ticket->name))
									->where($db->qn('price').' = '.$db->q($ticket->price))
									->where($db->qn('ide').' = '.(int) $id);
								
								$db->setQuery($query);
								$ticketid = (int) $db->loadResult();
							}
						}
						
						if (!$ticketid) {
							$query->clear()
								->insert($db->qn('#__rseventspro_tickets'))
								->set($db->qn('ide').' = '.(int) $clone->id)
								->set($db->qn('name').' = '.$db->q($ticket->name))
								->set($db->qn('price').' = '.$db->q($ticket->price))
								->set($db->qn('seats').' = '.$db->q($ticket->seats))
								->set($db->qn('user_seats').' = '.$db->q($ticket->user_seats))
								->set($db->qn('description').' = '.$db->q($ticket->description))
								->set($db->qn('position').' = '.$db->q($ticket->position))
								->set($db->qn('attach').' = '.$db->q($ticket->attach))
								->set($db->qn('layout').' = '.$db->q($ticket->layout))
								->set($db->qn('order').' = '.$db->q($ticket->order))
								->set($db->qn('groups').' = '.$db->q($ticket->groups));
						
							$db->setQuery($query);
							$db->execute();
							
							JFactory::getApplication()->triggerEvent('rsepro_copyEventFields', array(array('vars' => $vars, 'old' => $ticket->id, 'new' => $db->insertid())));
						}
					}
				}
				
				// Add coupons
				if (!empty($coupons)) {
					foreach ($coupons as $coupon) {
						$query->clear()
							->insert($db->qn('#__rseventspro_coupons'))
							->set($db->qn('ide').' = '.(int) $clone->id)
							->set($db->qn('name').' = '.$db->q($coupon->name))
							->set($db->qn('from').' = '.$db->q($coupon->from))
							->set($db->qn('to').' = '.$db->q($coupon->to))
							->set($db->qn('usage').' = '.$db->q($coupon->usage))
							->set($db->qn('discount').' = '.$db->q($coupon->discount))
							->set($db->qn('type').' = '.$db->q($coupon->type))
							->set($db->qn('action').' = '.$db->q($coupon->action))
							->set($db->qn('groups').' = '.$db->q($coupon->groups));
						
						$db->setQuery($query);
						$db->execute();
						$couponID = $db->insertid();
						
						// Add coupon codes
						$query->clear()
							->select($db->qn('code'))
							->from($db->qn('#__rseventspro_coupon_codes'))
							->where($db->qn('idc').' = '.(int) $coupon->id);
						
						$db->setQuery($query);
						if ($couponcodes = $db->loadColumn()) {
							foreach ($couponcodes as $code) {
								$query->clear()
									->insert($db->qn('#__rseventspro_coupon_codes'))
									->set($db->qn('code').' = '.$db->q($code))
									->set($db->qn('idc').' = '.$db->q($couponID))
									->set($db->qn('used').' = 0');
								
								$db->setQuery($query);
								$db->execute();
							}
						}
						
					}
				}
				
				// Add files
				if (!empty($files)) {
					foreach ($files as $file) {
						$filename	= JFile::stripExt($file->location);
						$ext		= JFile::getExt($file->location);
						
						while(JFile::exists(JPATH_SITE.'/components/com_rseventspro/assets/images/files/'.$filename.'.'.$ext))
							$filename .= rand(1,999);
						
						if (JFile::copy(JPATH_SITE.'/components/com_rseventspro/assets/images/files/'.$file->location, JPATH_SITE.'/components/com_rseventspro/assets/images/files/'.$filename.'.'.$ext)) {
							$query->clear()
								->insert($db->qn('#__rseventspro_files'))
								->set($db->qn('ide').' = '.(int) $clone->id)
								->set($db->qn('name').' = '.$db->q($file->name))
								->set($db->qn('location').' = '.$db->q($filename.'.'.$ext))
								->set($db->qn('permissions').' = '.$db->q($file->permissions));
							
							$db->setQuery($query);
							$db->execute();
						}
					}
				}
				
				// Add event icon
				if (!empty($icon)) {
					$filename	= JFile::stripExt($icon);
					$ext		= JFile::getExt($icon);
					$path		= JPATH_SITE.'/components/com_rseventspro/assets/images/events/';
					
					while(JFile::exists($path.$filename.'.'.$ext))
						$filename .= rand(1,999);
					
					if (JFile::copy($path.$icon,$path.$filename.'.'.$ext)) {
						$query->clear()
							->update($db->qn('#__rseventspro_events'))
							->set($db->qn('icon').' = '.$db->q($filename.'.'.$ext))
							->where($db->qn('id').' = '.(int) $clone->id);
						
						$db->setQuery($query);
						$db->execute();
					}
				}
				
				$count++;
			}
			
			if ($copy) 
				return $return;
		}
	}
	
	// Remove event
	public static function remove($id) {
		jimport('joomla.filesystem.file');
		
		$app	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$config = rseventsproHelper::getConfig();
		
		// Delete from sync db
		$query->clear()
			->delete($db->qn('#__rseventspro_sync'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$db->execute();
		
		// Delete coupon codes
		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__rseventspro_coupons'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		if ($couponids = $db->loadColumn()) {
			$couponids = array_map('intval',$couponids);
			$query->clear()
				->delete($db->qn('#__rseventspro_coupon_codes'))
				->where($db->qn('idc').' IN ('.implode(',',$couponids).')');
			
			$db->setQuery($query);
			$db->execute();
		}
		
		// Delete coupons
		$query->clear()
			->delete($db->qn('#__rseventspro_coupons'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$db->execute();
		
		$app->triggerEvent('rsepro_beforeEventDeleteTickets', array(array('id' => $id)));
		
		// Delete tickets
		$query->clear()
			->delete($db->qn('#__rseventspro_tickets'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$db->execute();
		
		// Delete RSVP
		$query->clear()
			->delete($db->qn('#__rseventspro_rsvp_users'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$db->execute();
		
		// Delete subscriptions
		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$subscriptions = $db->loadColumn();
		
		if (!empty($subscriptions)) {
			$subscriptions = array_map('intval',$subscriptions);
			
			$query->clear()
				->delete($db->qn('#__rseventspro_users'))
				->where($db->qn('id').' IN ('.implode(',', $subscriptions).')');
			
			$db->setQuery($query);
			$db->execute();
			
			$query->clear()
				->delete($db->qn('#__rseventspro_user_tickets'))
				->where($db->qn('ids').' IN ('.implode(',', $subscriptions).')');
				
			$db->setQuery($query);
			$db->execute();
		}
		
		$app->triggerEvent('rsepro_deleteCartSubscriptions', array(array('id' => $id)));
		
		// Delete categories, tags, repeating days, event groups
		$query->clear()
			->delete($db->qn('#__rseventspro_taxonomy'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$db->execute();
		
		//Get event files
		$query->clear()
			->select($db->qn('location'))
			->from($db->qn('#__rseventspro_files'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$oldfiles = $db->loadColumn();
		
		if (!empty($oldfiles)) {
			foreach($oldfiles as $file) {
				if (JFile::exists(JPATH_SITE.'/components/com_rseventspro/assets/images/files/'.$file))
					JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/files/'.$file);
			}
		}
		
		// Delete files
		$query->clear()
			->delete($db->qn('#__rseventspro_files'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$db->execute();
		
		//Event icon
		$query->clear()
			->select($db->qn('icon'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $id);
			
		$db->setQuery($query);
		if ($icon = $db->loadResult()) {
			// Delete event icon
			if (JFile::exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$icon)) {
				JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$icon);
			}
			
			$ext	= JFile::getExt($icon);
			$name	= JFile::stripExt($icon);
			
			// Delete small icon
			if (file_exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/'.$config->icon_small_width.'/'.md5($config->icon_small_width.$name).'.'.$ext)) {
				JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/'.$config->icon_small_width.'/'.md5($config->icon_small_width.$name).'.'.$ext);
			}
			
			// Delete big icon
			if (file_exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/'.$config->icon_big_width.'/'.md5($config->icon_big_width.$name).'.'.$ext)) {
				JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/'.$config->icon_big_width.'/'.md5($config->icon_big_width.$name).'.'.$ext);
			}
			
			// Delete event listing icon from backend
			if (file_exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/70/'.md5('70'.$name).'.'.$ext)) {
				JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/70/'.md5('70'.$name).'.'.$ext);
			}
			
			// Delete event edit icon
			if (file_exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/188/'.md5('188'.$name).'.'.$ext)) {
				JFile::delete(JPATH_SITE.'/components/com_rseventspro/assets/images/events/thumbs/188/'.md5('188'.$name).'.'.$ext);
			}
		}
		
		// Delete event
		$query->clear()
			->delete($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $id);
		
		$db->setQuery($query);
		$db->execute();
		
		return true;
	}
	
	// Load RSEvents!Pro plugins
	public static function loadPlugins() {
		jimport('joomla.plugin.helper');
		JPluginHelper::importPlugin('rseventspro');
	}
	
	// Get locations filter
	public static function filterlocations() {
		$db			 = JFactory::getDbo();
		$html		 = array();
		$query		 = $db->getQuery(true);
		$input		 = JFactory::getApplication()->input;
		$permissions = rseventsproHelper::permissions();
		
		$json	= $input->getInt('json',0);
		$search = $input->getString('rs_location',0);
		
		$query->clear()
			->select($db->qn('id'))->select($db->qn('name'))
			->from($db->qn('#__rseventspro_locations'))
			->where($db->qn('published').' = 1')
			->where($db->qn('name').' LIKE '.$db->q('%'.$search.'%'));
		
		$db->setQuery($query);
		if ($locations = $db->loadObjectList()) {
			foreach ($locations as $location) {
				if ($json) {
					$html[] = $location;
				} else {
					$html[] = '<li id="'.$location->id.'">'.$location->name.'</li>';
				}
			}
			
			$canAdd = false;
			
			if (JFactory::getApplication()->isClient('administrator')) {
				$canAdd = true;
			} else {
				if (!empty($permissions['can_add_locations']) || rseventsproHelper::admin()) {
					$canAdd = true;
				}
			}
			
			if ($canAdd) {
				if ($json) {
					$html[] = (object) array('id' => '-', 'name' => '<strong>'.JText::_('COM_RSEVENTSPRO_ADD_LOCATION').'</strong>');
				} else {
					$html[] = '<li id="-" onclick="rs_add_loc();">'.JText::_('COM_RSEVENTSPRO_ADD_LOCATION',true).'</li>';
				}
			}
		}
		
		if ($json) {
			return json_encode($html);
		} else {
			return implode("\n",$html);
		}
	}
	
	// Check if we can use the RSForm!Pro form in our registration process
	public static function checkform($fid, $id) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$messages	= array();
		
		jimport('joomla.plugin.helper');
		
		if (!file_exists(JPATH_SITE.'/components/com_rsform/rsform.php')) 
			return array('result' => false, 'message' => JText::_('COM_RSEVENTSPRO_RSFP_INSTALL_RSFORMPRO'));
		
		if (!file_exists(JPATH_SITE.'/plugins/system/rsfprseventspro/rsfprseventspro.php'))
			return array('result' => false, 'message' => JText::_('COM_RSEVENTSPRO_RSFP_INSTALL_PLUGIN'));
		
		if (!JPluginHelper::isEnabled('system','rsfprseventspro'))
			return array('result' => false, 'message' => JText::_('COM_RSEVENTSPRO_RSFP_UNPUBLISHED_PLUGIN'));
		
		$query->clear()
			->select($db->qn('registration'))->select($db->qn('discounts'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $id);
		
		$db->setQuery($query);
		$event = $db->loadObject();
		
		$query->clear()
			->select('COUNT('.$db->qn('id').')')
			->from($db->qn('#__rseventspro_tickets'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$tickets = $db->loadResult();
		
		$query->clear()
			->select('SUM('.$db->qn('price').')')
			->from($db->qn('#__rseventspro_tickets'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if (!$event->registration)
			return array('result' => false, 'message' => JText::_('COM_RSEVENTSPRO_NO_REGISTRATION'));
		
		// Search for the Name component
		$query->clear()
			->select('COUNT('.$db->qn('ComponentId').')')
			->from($db->qn('#__rsform_components'))
			->where($db->qn('FormId').' = '.(int) $fid)
			->where($db->qn('Published').' = 1')
			->where($db->qn('ComponentTypeId').' = 30');
		
		$db->setQuery($query);
		$name = $db->loadResult();
		
		if (!$name)
			$messages[30] = JText::_('COM_RSEVENTSPRO_RSFP_NAME');
		
		// Search for the Email component
		$query->clear()
			->select('COUNT('.$db->qn('ComponentId').')')
			->from($db->qn('#__rsform_components'))
			->where($db->qn('FormId').' = '.(int) $fid)
			->where($db->qn('Published').' = 1')
			->where($db->qn('ComponentTypeId').' = 31');
		
		$db->setQuery($query);
		$email = $db->loadResult();
		
		if(!$email)
			$messages[31] = JText::_('COM_RSEVENTSPRO_RSFP_EMAIL');
		
		// Search for the Tickets component
		$query->clear()
			->select('COUNT('.$db->qn('ComponentId').')')
			->from($db->qn('#__rsform_components'))
			->where($db->qn('FormId').' = '.(int) $fid)
			->where($db->qn('Published').' = 1')
			->where($db->qn('ComponentTypeId').' = 32');
		
		$db->setQuery($query);
		$ticket = $db->loadResult();
		
		if ($tickets && !$ticket)
			$messages[32] = JText::_('COM_RSEVENTSPRO_RSFP_TICKETS');
		
		if (!$tickets && $ticket)
			$messages[32] = JText::_('COM_RSEVENTSPRO_RSFP_REMOVE_TICKETS');
		
		// Search for the Payments component
		$query->clear()
			->select('COUNT('.$db->qn('ComponentId').')')
			->from($db->qn('#__rsform_components'))
			->where($db->qn('FormId').' = '.(int) $fid)
			->where($db->qn('Published').' = 1')
			->where($db->qn('ComponentTypeId').' = 33');
		
		$db->setQuery($query);
		$payment = $db->loadResult();		
		
		if ($tickets && $total && !$payment)
			$messages[33] = JText::_('COM_RSEVENTSPRO_RSFP_PAYMENTS');
		
		if (!$tickets && $payment)
			$messages[33] = JText::_('COM_RSEVENTSPRO_RSFP_REMOVE_PAYMENTS');
		
		$query->clear()
			->select('COUNT('.$db->qn('ComponentId').')')
			->from($db->qn('#__rsform_components'))
			->where($db->qn('FormId').' = '.(int) $fid)
			->where($db->qn('Published').' = 1')
			->where($db->qn('ComponentTypeId').' = 34');
		
		$db->setQuery($query);
		$coupon = $db->loadResult();
		
		if ($event->discounts && !$coupon)
			$messages[34] = JText::_('COM_RSEVENTSPRO_RSFP_COUPONS');
		
		if (count($messages) == 1 && isset($messages[34]) && $event->discounts && $event->registration)
			return array('result' => true, 'message' => $messages[34]);
		
		if (empty($messages))
			return array('result' => true, 'message' => '');
		
		return array('result' => false, 'message' => implode('<br />',$messages));
	}
	
	// Get event default options
	public static function getDefaultOptions() {
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/events.php';
		$event = RSEvent::getInstance();
		
		$defaults = $event->getDefaultOptions();
		$registry = new JRegistry;
		$registry->loadArray($defaults);
		return $registry->toString();
	}
	
	// Create sef alias
	public static function sef($id, $name) {
		if (JFactory::getConfig()->get('unicodeslugs') == 1) {
			$output = $id.':'.JFilterOutput::stringURLUnicodeSlug($name);
		} else {
			$output = $id.':'.JFilterOutput::stringURLSafe($name);
		}
		
		return $output;
	}
	
	// Route links
	public static function route($url, $xhtml=true, $Itemid='') {
		$app 		= JFactory::getApplication();
		$input		= $app->input;
		$current	= $input->getInt('Itemid',0);
		$option		= $input->get('option','');
		
		if (!$Itemid) {
			$menu 		= $app->getMenu();
			$menuItem 	= $menu->getActive();
			
			if (is_object($menuItem) && isset($menuItem->home) && empty($current)) {
				if ($menuItem->home) {
					$Itemid = $menuItem->id;
				}
			}
		}
		
		if (!$Itemid && $current && $option == 'com_rseventspro') {
			$Itemid = $current;
		}
		
		if ($Itemid) {
			$url .= (strpos($url, '?') === false) ? '?Itemid='.$Itemid : '&Itemid='.$Itemid;
		}
		
		return JRoute::_($url, $xhtml);
	}
	
	// Get page params
	public static function getParams() {
		$app	= JFactory::getApplication();
		$itemid = $app->input->getInt('Itemid',0);
		$params = null;
		
		if ($app->isClient('administrator')) {
			return new JRegistry;
		}
		
		if ($itemid) {
			$menu = $app->getMenu();
			$active = $menu->getItem($itemid);
			if ($active) {
				$params = $active->params;
			}
		}
		
		if (empty($params)) {
			$params = $app->getParams();
		}
		
		return $params;
	}
	
	// Get cached groups
	public static function getCachedGroupDetails($all = false) {
		static $group_acls_cache;
		if (empty($group_acls_cache)) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->clear();
			$all ? $query->select('*') : $query->select($db->qn('id'))->select($db->qn('jgroups'))->select($db->qn('jusers'));
			$query->from($db->qn('#__rseventspro_groups'));
			$query->order($db->qn('id').' ASC');
			
			$db->setQuery($query);
			$group_acls_cache = $db->loadObjectList();
		}
		
		return $group_acls_cache;
	}
	
	// Get user permissions
	public static function permissions() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
		$userid = $user->get('id');
		
		static $groups;
		if (empty($groups)) {
			$groups = rseventsproHelper::getCachedGroupDetails(true);
		}
		
		$permissions = array('can_edit_events' => 0, 'can_post_events' => 0, 'can_repeat_events' => 0, 'event_moderation' => 1, 'can_delete_events' => 0, 'can_register' => 1, 'can_unsubscribe' => 1, 'can_download' => 1, 'can_upload' => 0, 'can_create_categories' => 0, 'tag_moderation' => 0, 'can_add_locations' => 0, 'can_edit_locations' => 0, 'can_approve_events' => 0, 'can_approve_tags' => 0, 'can_change_options' => 1, 'can_select_speakers' => 1, 'can_add_speaker' => 0);
		
		if (!empty($groups)) {
			$rsgroups = array();
			
			foreach ($groups as $group) {
				if (!empty($group->jgroups)) {
					
					try {
						$registry = new JRegistry;
						$registry->loadString($group->jgroups);
						$joomlagroups = $registry->toArray();
					} catch (Exception $e) {
						$joomlagroups = array();
					}
					
					if (!empty($joomlagroups)) {
						$user_groups = JAccess::getGroupsByUser($user->id);
						
						if (!$user->guest) {
							foreach ($user_groups as $key => $value) {
								if ($value == 1) {
									unset($user_groups[$key]);
								}
							}
						}
						
						if (!empty($joomlagroups)) {
							foreach($user_groups as $ugroup) {
								if (in_array($ugroup,$joomlagroups)) {
									$rsgroups[] = $group;
								}
							}
						}
					}
				}
				
				if (!empty($group->jusers)) {
					try {
						$registry = new JRegistry;
						$registry->loadString($group->jusers);
						$joomlausers = $registry->toArray();
					} catch (Exception $e) {
						$joomlausers = array();
					}
					
					if (!empty($joomlausers)) {
						if (in_array($userid,$joomlausers)) {
							$rsgroups[] = $group;
						}
					}
				}
			}
			
			if (!empty($rsgroups)) {
				foreach ($rsgroups as $group) {
					$group =  get_object_vars($group);
					foreach($group as $name => $value) {
						if (isset($permissions[$name])) {
							$permissions[$name] = $value;
						}
					}
				}
			}
		}
		
		return $permissions;
	}
	
	// Can I view this event ?
	public static function canview($id) {
		static $cache = array();
		
		if (!isset($cache[$id])) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$user	= JFactory::getUser();
			
			// If the user is an admin we let him view the event by default
			if (self::admin()) {
				$cache[$id] = true;
				return $cache[$id];
			}
			
			// Get the group list that are restricted to view this event
			$query->clear()
				->select($db->qn('g.jgroups'))->select($db->qn('g.jusers'))
				->from($db->qn('#__rseventspro_groups','g'))
				->join('left', $db->qn('#__rseventspro_taxonomy','tx').' ON '.$db->qn('tx.id').' = '.$db->qn('g.id'))
				->where($db->qn('tx.ide').' = '.(int) $id)
				->where($db->qn('tx.type').' = '.$db->q('groups'));
			
			$db->setQuery($query);
			$groups = $db->loadObjectList();
			
			if (!empty($groups)) {
				// Initialize the can view variable to true
				$canview = true;
				
				foreach ($groups as $group) {
					// Parse joomla groups
					if (!empty($group->jgroups)) {
						try {
							$registry = new JRegistry;
							$registry->loadString($group->jgroups);
							$jgroups = $registry->toArray();
						} catch (Exception $e) {
							$jgroups = array();
						}
						
						if (!empty($jgroups)) {
							// Get current users Joomla! groups
							$groups = JAccess::getGroupsByUser($user->get('id'));
							
							// If the user is logged in remove the Public group
							if (!$user->get('guest')) {
								foreach ($groups as $key => $value) {
									if ($value == 1) {
										unset($groups[$key]);
									}
								}
							}
							
							// If the user has multiple groups parse them
							foreach ($groups as $gr) {
								// If one of the users group is in the restricted groups we return false;
								if (in_array($gr,$jgroups)) {
									$canview = false;
								}
							}
						}
					}
					
					// Parse user ids
					// User id check overwrites the user group return
					if (!empty($group->jusers)) {
						try {
							$registry = new JRegistry;
							$registry->loadString($group->jusers);
							$jusers = $registry->toArray();
						} catch (Exception $e) {
							$jusers = array();
						}
						
						if (!empty($jusers)) {
							$userid = $user->get('id');
							
							// If the current user id is in the restricted groups then we return false;
							if (in_array($userid,$jusers)) {
								$canview = false;
							}
						}
					}
					
					if (empty($group->jgroups) && empty($group->jusers)) 
						$canview = true;
				}
				
				$cache[$id] = $canview;
				
				return $cache[$id];
			}
			
			$cache[$id] = true;
		}
		
		return $cache[$id];
	}
	
	// Is the current user an admin
	public static function admin() {
		$user	= JFactory::getUser();
		$admins = self::getAdminUsers();
		
		if (in_array($user->get('id'), $admins))
			return true;
		
		return false;
	}
	
	public static function getAdminGroups() {
		if (!is_array(self::$groups)) {
			$db 	= JFactory::getDbo();
			$query 	= $db->getQuery(true);
			$query->select($db->qn('id'))
				  ->from($db->qn('#__usergroups'));
			$db->setQuery($query);
			$groups = $db->loadColumn();
			
			self::$groups = array();
			foreach ($groups as $group_id) {
				if (JAccess::checkGroup($group_id, 'core.admin'))
					self::$groups[] = $group_id;
			}
			
			self::$groups = array_unique(self::$groups);
		}
		
		return self::$groups;
	}
	
	public static function getAdminUsers() {
		if (!is_array(self::$users)) {
			self::$users = array();
			
			if ($groups	= self::getAdminGroups()) {
				$db 	= JFactory::getDbo();
				$query 	= $db->getQuery(true);
				$query->select($db->qn('u.id'))
					  ->from($db->qn('#__user_usergroup_map','m'))
					  ->join('right', $db->qn('#__users','u').' ON ('.$db->qn('u.id').' = '.$db->qn('m.user_id').')')
					  ->where($db->qn('m.group_id').' IN ('.implode(',', $groups).')')
					  ->group($db->qn('u.id'));
				$db->setQuery($query);
				self::$users = $db->loadColumn();
			}
		}
		
		return self::$users;
	}
	
	// Check to see if the event is full
	public static function eventisfull($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$array	= array();
		
		// Load events
		$events = self::getCachedEventDetails();
		
		if (isset($events[$id]) && ($events[$id]->registration == 0 || $events[$id]->overbooking == 1)) {
			return false;
		}
		
		// Get the maximum amount of tickets allowed
		$max = $events[$id]->max_tickets == 1 ? $events[$id]->max_tickets_amount : false;
		
		// Load tickets
		$tickets = self::getCachedTickets($id);
		
		// Parse tickets
		if (!empty($tickets)) {
			
			// Load quantities
			$quantities = self::getCachedQuantities();
			
			if ($max) {
				$quantity = 0;
				foreach($tickets as $ticket) {
					if (isset($quantities[$ticket->id])) {
						$quantity += (int) $quantities[$ticket->id];
					}
				}
				
				if ($quantity && $quantity >= $max) 
					return true;
			}
			
			foreach($tickets as $ticket) {
				if (!empty($ticket->seats)) {
					$quantity = isset($quantities[$ticket->id]) ? $quantities[$ticket->id] : 0;
					if($ticket->seats <= $quantity) 
						continue;
				}
				$array[] = $ticket;
			}
		} else {
			if ($max) {
				$subscriptions = self::getCachedSubscriptions($id);
				
				if ($subscriptions) {
					if ($subscriptions >= $max)
						return true;
					else return false;
				} else return false;
			} else return false;
		}
		
		return empty($array) ? true : false;
	}
	
	protected static function getCachedEventDetails() {
		static $cache = array();
		
		if (empty($cache)) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$query->clear()
				->select($db->qn('id'))
				->select($db->qn('registration'))->select($db->qn('overbooking'))
				->select($db->qn('max_tickets'))->select($db->qn('max_tickets_amount'))
				->from($db->qn('#__rseventspro_events'));
			
			$db->setQuery($query);
			$cache = $db->loadObjectList('id');
		}
		
		return $cache;
	}
	
	protected static function getCachedTickets($id) {
		static $cache = array();
		
		if (empty($cache)) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$query->clear()
				->select($db->qn('id'))->select($db->qn('ide'))
				->select($db->qn('price'))->select($db->qn('seats'))
				->select($db->qn('user_seats'))
				->from($db->qn('#__rseventspro_tickets'));
			
			$db->setQuery($query);
			if ($tickets = $db->loadObjectList()) {
				foreach ($tickets as $ticket) {
					if (!isset($cache[$ticket->ide])) {
						$cache[$ticket->ide] = array();
					}
					
					$cache[$ticket->ide][] = $ticket;
				}
			}
		}
		
		return isset($cache[$id]) ? $cache[$id] : array();
	}
	
	protected static function getCachedQuantities() {
		static $cache = array();
		
		if (empty($cache)) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$query->clear()
				->select('SUM('.$db->qn('ut.quantity').') AS sum')
				->select($db->qn('t.id'))
				->from($db->qn('#__rseventspro_user_tickets','ut'))
				->join('left', $db->qn('#__rseventspro_users','u').' ON '.$db->qn('u.id').' = '.$db->qn('ut.ids'))
				->join('left', $db->qn('#__rseventspro_tickets','t').' ON '.$db->qn('t.id').' = '.$db->qn('ut.idt'))
				->where($db->qn('u.state').' IN (0,1)')
				->group($db->qn('t.id'));
				
			$db->setQuery($query);
			if ($quantities = $db->loadObjectList()) {
				foreach ($quantities as $quantity) {
					$cache[$quantity->id] = $quantity->sum;
				}
			}
		}
		
		return $cache;
	}
	
	protected static function getCachedSubscriptions($id) {
		static $cache = array();
		
		if (empty($cache)) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$query->clear()
				->select('COUNT('.$db->qn('id').') AS number')
				->select($db->qn('ide'))
				->from($db->qn('#__rseventspro_users'))
				->where($db->qn('state').' IN (0,1)')
				->group($db->qn('ide'));
				
			$db->setQuery($query);
			if ($subscriptions = $db->loadObjectList()) {
				foreach ($subscriptions as $subscription) {
					$cache[$subscription->ide] = $subscription->number;
				}
			}
		}
		
		return isset($cache[$id]) ? $cache[$id] : 0;
	}
	
	// Get a list of excluded events
	public static function excludeEvents() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$ids	= array();
		
		$query->clear()
			->select($db->qn('ide'))
			->from($db->qn('#__rseventspro_taxonomy'))
			->where($db->qn('type').' = '.$db->q('groups'));
		
		$db->setQuery($query);
		if ($eventids = $db->loadColumn()) {
			foreach ($eventids as $id) {
				$query->clear()
					->select($db->qn('owner'))
					->from($db->qn('#__rseventspro_events'))
					->where($db->qn('id').' = '.(int) $id);
				
				$db->setQuery($query);
				$owner = (int) $db->loadResult();
				
				if (!rseventsproHelper::canview($id) && $owner != JFactory::getUser()->get('id')) {
					$ids[] = $id;
				}
			}
			
			if (!empty($ids)) {
				$ids = array_map('intval',$ids);
				$ids = array_unique($ids);
			}
		}
		
		return $ids;
	}
	
	// Prepare all event details
	public static function details($id, $itemid = null, $content = false) {
		$u			= JURI::getInstance();
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$app		= JFactory::getApplication();
		$root		= $u->toString(array('scheme','host','port'));
		$itemid		= !is_null($itemid) ? '&Itemid='.$itemid : '';
		$params   	= rseventsproHelper::getParams();
		$archived 	= (int) $params->get('archived',0);
		$return		= array();
		
		$query->clear()
			->select('e.*')
			->select($db->qn('l.id','locationid'))->select($db->qn('l.name','location'))->select($db->qn('l.url','locationlink'))
			->select($db->qn('l.address'))->select($db->qn('l.description','ldescription'))->select($db->qn('l.coordinates'))
			->select($db->qn('l.published','lpublished'))->select($db->qn('l.marker'))
			->from($db->qn('#__rseventspro_events','e'))
			->join('left',$db->qn('#__rseventspro_locations','l').' ON '.$db->qn('e.location').' = '.$db->qn('l.id'));
		
		if (is_array($id)) {
			$query->where($db->qn('e.id').' IN ('.implode(',',$id).')');
			$query->order(self::getEventsOrdering());
		} else {
			$query->where($db->qn('e.id').' = '.(int) $id);
		}
		
		$db->setQuery($query);
		$events = $db->loadObjectList();
		
		$ids = array();
		if ($events) {
			foreach ($events as $event) {
				$ids[] = $event->id;
				$ids[] = $event->parent;
			}
		}
		
		$categs			= self::getAllCategories();
		$eventtags		= self::getAllTags();
		$eventtickets	= self::getAllTickets();
		$repeats		= self::getEventRepeats($ids);
		$defaults 		= rseventsproHelper::getDefaults();
		
		foreach ($events as $event) {
			$container = array();
			
			if (!empty($event->URL)) {
				if (substr($event->URL,0,4) != 'http')
					$event->URL = 'http://'.$event->URL;
			}
			
			// Set owner name
			$event->ownername = rseventsproHelper::getUser($event->owner);
			
			// Set the owner profile link
			$event->ownerprofile = rseventsproHelper::getProfile('owner', $event->owner);
			
			// Content trigger
			if (rseventsproHelper::getConfig('content_prepare','int', 1) && $content) {
				$event->description = JHtml::_('content.prepare',$event->description);
			}
			
			// Event options
			if ($event->options) {
				try {
					$registry = new JRegistry;
					$registry->loadString($event->options);
				} catch (Exception $e) {
					$registry = new JRegistry;
				}
				
				if ($options = $registry->toArray()) {
					$event->options = $defaults;
					foreach ($options as $option => $value) {
						if (isset($event->options[$option])) {
							$event->options[$option] = $value;
						}
					}
				}
			}
			
			// Event
			$container['event'] = $event;
			
			$categories = array();
			if (!empty($categs[$event->id])) {
				foreach ($categs[$event->id] as $cat) {
					if (rseventsproHelper::getConfig('color','int')) {
						$color = '';
						if ($cat->params) {
							try {
								$registry = new JRegistry;
								$registry->loadString($cat->params);
								$color = $registry->get('color');
							} catch (Exception $e) {
								$color = '';
							}
						}
						
						$style = $color ? 'style="color: '.$color.'"' : '';
					} else $style = '';
					
					$cURL = $root.rseventsproHelper::route('index.php?option=com_rseventspro&category='.rseventsproHelper::sef($cat->id,$cat->title).$itemid);
					if ($app->isClient('administrator')) {
						$cURL = str_replace('administrator/','',$cURL);
					}
					
					$categories[] = '<a href="'.$cURL.'" class="rs_cat_link" '.$style.'>'.$cat->title.'</a>';
				}
			}
			
			// Add categories
			$container['categories'] = !empty($categories) ? implode(', ',$categories) : '';
			
			$tags = array();
			if (!empty($eventtags[$event->id])) {
				foreach ($eventtags[$event->id] as $tag) {
					$tURL = $root.rseventsproHelper::route('index.php?option=com_rseventspro&tag='.rseventsproHelper::sef($tag->id,$tag->name).$itemid);
					if ($app->isClient('administrator')) {
						$tURL = str_replace('administrator/','',$tURL);
					}
					
					$tags[] = '<a href="'.$tURL.'" class="rs_tag_link">'.$tag->name.'</a>';
				}
			}
			
			// Add tags
			$container['tags'] = !empty($tags) ? implode(', ',$tags) : '';
			
			// Add icons
			$fileExists = file_exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$event->icon);
			$image_b = '';
			if (!empty($event->icon) && $fileExists) {
				$image_b = rseventsproHelper::thumb($event->id, rseventsproHelper::getConfig('icon_big_width','int'));
			}
			
			$image_s = '';
			if (!empty($event->icon) && $fileExists) {
				$image_s = rseventsproHelper::thumb($event->id, rseventsproHelper::getConfig('icon_small_width','int'));
			}
			
			$image = '';
			if (!empty($event->icon) && $fileExists) {
				$image = JURI::root().'components/com_rseventspro/assets/images/events/'.$event->icon;
			}
			
			$container['image_b'] = $image_b;
			$container['image_s'] = $image_s;
			$container['image'] = $image;
			
			// Speakers
			$container['speakers'] = rseventsproHelper::getSpeakers($event->id);
			
			// Tickets	
			$tdata	 = array();
			$arr	 = array();
			$tickets = '';
			
			if (!empty($eventtickets[$event->id])) {
				foreach ($eventtickets[$event->id] as $ticket) {
					$object			= new stdClass;
					$object->name	= $ticket->name;
					$object->price	= $ticket->price;
					
					$query->clear()
						->select('SUM('.$db->qn('quantity').')')
						->from($db->qn('#__rseventspro_user_tickets'))
						->where($db->qn('idt').' = '.(int) $ticket->id);
					
					$db->setQuery($query);
					$purchased = $db->loadResult();
					
					if ($ticket->seats > 0) {
						$available = $ticket->seats - $purchased;
						if ($available > 0) {
							$object->available = $available;
							if ($ticket->price > 0) {
								$arr[] = $available. ' x '.$ticket->name.' ('.rseventsproHelper::currency($ticket->price).')';
							} else {
								$arr[] = $available. ' x '.$ticket->name.' ('.JText::_('COM_RSEVENTSPRO_GLOBAL_FREE').')';
							}
						}
					} else {
						if ($ticket->price > 0) {
							$arr[] = JText::_('COM_RSEVENTSPRO_GLOBAL_UNLIMITED'). ' '.$ticket->name.' ('.rseventsproHelper::currency($ticket->price).')';
						} else {
							$arr[] = JText::_('COM_RSEVENTSPRO_GLOBAL_UNLIMITED'). ' '.$ticket->name.' ('.JText::_('COM_RSEVENTSPRO_GLOBAL_FREE').')';
						}
					}
					
					$tdata[] = $object;
				}
			}
			
			if (!empty($arr)) {
				$tickets .= JText::_('COM_RSEVENTSPRO_GLOBAL_AVAILABLE_TICKETS');
				$tickets .= '<ul class="rs_av_tickets">';
				foreach ($arr as $ticket)
					$tickets .= '<li>'.$ticket.'</li>';
				$tickets .= '</ul>';
			}
			
			$container['tickets'] = $tickets;
			$container['ticket_info'] = $arr;
			$container['tickets_data'] = $tdata;
			
			// Add event files
			$container['files'] = rseventsproHelper::getEventFiles($event->id);
			
			// Get event repeated events			
			$container['repeats'] = array();
			$idr = $event->parent == 0 ? $event->id : $event->parent;
			if (!empty($repeats[$idr])) {
				$container['repeats'] = $repeats[$idr];
			}
			
			$app->triggerEvent('rsepro_details',array(array('details'=>&$container)));
			
			$return[$event->id] = $container;
		}
		
		return is_array($id) ? $return : (isset($return[$id]) ? $return[$id] : array());
	}
	
	protected static function getEventsOrdering() {
		$db			= JFactory::getDBo();
		$params 	= rseventsproHelper::getParams();
		$order		= $params->get('ordering','start');
		$direction	= $params->get('order','DESC');
		
		if ($order == 'title' || $order == 'c.title')
			$order = 'name';
		
		if ($order == 'lft' || $order == 'c.lft')
			$order = 'start';
		
		$featured_condition = rseventsproHelper::getConfig('featured','int') ? $db->qn('e.featured').' DESC, ' : '';
		return $featured_condition.$db->qn('e.'.$order).' '.$db->escape($direction);
	}
	
	protected static function getEventRepeats($ids) {
		if (empty($ids)) {
			return array();
		}
		
		if (!is_array($ids)) {
			$ids = array($ids);
		}
		
		$ids = array_unique($ids);
		$ids = array_map('intval',$ids);
		$db = JFactory::getDbo();
		
		// Get event repeated events
		$query = $db->getQuery(true)
			->select($db->qn('id'))->select($db->qn('parent'))
			->select($db->qn('name'))->select($db->qn('start'))
			->select($db->qn('end'))->select($db->qn('allday'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('parent').' IN ('.implode(',', $ids).')')
			->where($db->qn('completed').' = 1')
			->order($db->qn('start').' ASC');

		$params   	= rseventsproHelper::getParams();
		$archived 	= (int) $params->get('archived',0);
		
		if ($archived) {
			$query->where($db->qn('published').' IN (1,2)');
		} else {
			$query->where($db->qn('published').' = 1');
		}
		
		$db->setQuery($query);
		$repeats = $db->loadObjectList();
		
		$return = array();
		foreach ($repeats as $event) {
			if (empty($return[$event->parent])) {
				$return[$event->parent] = array();
			}
			
			$return[$event->parent][] = $event;
		}
		
		return $return;
	}
	
	protected static function getAllCategories() {
		static $categories = array();
		
		if (empty($categories)) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$app	= JFactory::getApplication();
			
			$query->clear()
				->select($db->qn('tx.ide'))->select($db->qn('c.id'))
				->select($db->qn('c.title'))->select($db->qn('c.params'))
				->from($db->qn('#__categories','c'))
				->join('left',$db->qn('#__rseventspro_taxonomy','tx').' ON '.$db->qn('tx.id').' = '.$db->qn('c.id'))
				->where($db->qn('tx.type').' = '.$db->q('category'))
				->where($db->qn('c.published').' = 1')
				->where($db->qn('c.extension').' = '.$db->q('com_rseventspro'));
			
			if ($app->isClient('site')) {
				if (JLanguageMultilang::isEnabled()) {
					$query->where('c.language IN ('.$db->q(JFactory::getLanguage()->getTag()).','.$db->q('*').')');
				}
				
				$user	= JFactory::getUser();
				$groups	= implode(',', $user->getAuthorisedViewLevels());
				$query->where('c.access IN ('.$groups.')');
			}
			
			$db->setQuery($query);
			$allcategories = $db->loadObjectList();
			
			if (!empty($allcategories)) {
				foreach($allcategories as $category) {
					$categories[$category->ide][] = $category;
				}
			}
		}
		
		return $categories;
	}
	
	protected static function getAllTags() {
		static $tags = array();
		
		if (empty($tags)) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$tags	= array();
			
			$query->clear()
				->select($db->qn('tx.ide'))->select($db->qn('t.id'))->select($db->qn('t.name'))
				->from($db->qn('#__rseventspro_tags','t'))
				->join('left',$db->qn('#__rseventspro_taxonomy','tx').' ON '.$db->qn('tx.id').' = '.$db->qn('t.id'))
				->where($db->qn('tx.type').' = '.$db->q('tag'))
				->where($db->qn('t.published').' = 1');
			
			$db->setQuery($query);
			$alltags = $db->loadObjectList();
			
			if (!empty($alltags)) {
				foreach ($alltags as $tag) {
					$tags[$tag->ide][] = $tag;
				}
			}
		}
		
		return $tags;
	}
	
	protected static function getAllTickets() {
		static $tickets = array();
		
		if (empty($tickets)) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->clear()
				->select($db->qn('id'))->select($db->qn('ide'))->select($db->qn('name'))
				->select($db->qn('price'))->select($db->qn('seats'))
				->from($db->qn('#__rseventspro_tickets'));
			
			$db->setQuery($query);
			$alltickets = $db->loadObjectList();
			
			if (!empty($alltickets)) {
				foreach ($alltickets as $ticket) {
					$tickets[$ticket->ide][] = $ticket; 
				}
			}
		}
		
		return $tickets;
	}
	
	public static function getEventIds($container, $key = 'id') {
		$array = array();
		
		if ($container) {
			foreach ($container as $object) {
				if (isset($object->$key)) {
					$array[] = $object->$key;
				}
			}
		}
		
		return $array;
	}
	
	// Get the name of the user
	public static function getUser($uid, $type = 'owner', $name = null) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser($uid);
		$option = $type == 'guest' ? rseventsproHelper::getConfig('user_display','int') : rseventsproHelper::getConfig('event_owner','int');
		
		if (!$uid) {
			return JText::_('COM_RSEVENTSPRO_GLOBAL_GUEST');
		}
		
		if ($option == 0) {
			return is_null($name) ? $user->name : $name;
		} elseif ($option == 1) {
			return $user->username;
		} elseif ($option == 2) {
			if (file_exists(JPATH_SITE.'/components/com_community/libraries/core.php')) {
				include_once JPATH_SITE.'/components/com_community/libraries/core.php';
				$user  = CFactory::getUser($uid);
				return $user->getDisplayName();
			} else return $user->name;
		} elseif ($option == 3) {
			$query->clear()
				->select($db->qn('firstname'))->select($db->qn('middlename'))->select($db->qn('lastname'))
				->from($db->qn('#__comprofiler'))
				->where($db->qn('user_id').' = '.(int) $uid);
			
			$db->setQuery($query);
			$details = $db->loadObject();
			
			if ($details->firstname && $details->lastname) {
				return $details->firstname.' '.$details->middlename.' '.$details->lastname;
			}
			
			return $user->name;
		} elseif ($option == 4) {
			$query->clear()
				->select($db->qn('name'))
				->from($db->qn('#__rseventspro_user_info'))
				->where($db->qn('id').' = '.(int) $uid);
			
			$db->setQuery($query);
			$name = $db->loadResult();
			
			return !empty($name) ? $name : $user->name;
		}else return $user->name;
	}
	
	// Get event files
	public static function getEventFiles($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		static $files = null;
		if (!is_array($files)) {
			$files = array();
			
			$query->clear()
				->select('*')
				->from('#__rseventspro_files');
			
			$db->setQuery($query);
			$allfiles = $db->loadObjectList();
			
			if (!empty($allfiles)) {
				foreach ($allfiles as $f) {
					$files[$f->ide][] = $f;
				}
			}
		}
		
		if (!empty($files[$id])) {
			$array			= array();
			$permissions	= rseventsproHelper::permissions();
			$canDownload	= !empty($permissions['can_download']) || rseventsproHelper::admin() ? 1 : 0 ;
			$registered		= rseventsproHelper::registered($id);
			
			$query->clear()
				->select($db->qn('start'))->select($db->qn('end'))
				->from($db->qn('#__rseventspro_events'))
				->where($db->qn('id').' = '.(int) $id);
			
			$db->setQuery($query);
			$dates = $db->loadObject();
			
			$now	= new DateTime('now', new DateTimezone('UTC'));
			$start	= new DateTime($dates->start, new DateTimezone('UTC'));
			$end	= new DateTime($dates->end, new DateTimezone('UTC'));
			
			//Determine in which interval you are (before/during/after)
			if($now < $start) $interval = 'before';
			if($now >= $start && $now < $end) $interval = 'during';
			if($now >= $end) $interval = 'after';
			
			foreach ($files[$id] as $file) {
				if(
					(
						!$registered && $canDownload && 
						(($interval == 'before' && $file->permissions[0]==1) || ($interval == 'during' && $file->permissions[1]==1) || ($interval == 'after' && $file->permissions[2]==1))
					)
				||
					(
						$registered && $canDownload && 
						(($interval == 'before' && $file->permissions[3]==1) || ($interval == 'during' && $file->permissions[4]==1) || ($interval == 'after' && $file->permissions[5]==1))
					)
				)
					$array[] = '<li><a href="'.JURI::root().'components/com_rseventspro/assets/images/files/'.$file->location.'" target="_blank"><i class="fa fa-file-o"></i> '.$file->name.'</a></li>';
			}
		}
		
		return !empty($array) ? '<ul class="rs_files">'.implode('',$array).'</ul>' : '';
	}
	
	// Check if the current user is registered to the event
	public static function registered($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->select('COUNT('.$db->qn('id').')')
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('ide').' = '.(int) $id)
			->where($db->qn('idu').' = '.(int) JFactory::getUser()->get('id'));
		
		$db->setQuery($query);
		return $db->loadResult() ? true : false;
	}
	
	// Check to see if the event is ongoing
	public static function ongoing($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$now	= new DateTime('NOW', new DateTimezone('UTC'));
		
		$query->clear()
			->select($db->qn('start'))->select($db->qn('end'))->select($db->qn('allday'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $id);
		
		$db->setQuery($query);
		$event = $db->loadObject();
		
		if ($event->allday) {
			$start	= new DateTime($event->start, new DateTimezone('UTC'));
			$end	= new DateTime($event->start, new DateTimezone('UTC'));
			$end->modify('+1 days');
			
			if ($start <= $now && $end >= $now) {
				return true;
			}
		} else {
			$start	= new DateTime($event->start, new DateTimezone('UTC'));
			$end	= new DateTime($event->end, new DateTimezone('UTC'));
			
			if ($start <= $now && $end >= $now) {
				return true;
			}
		}
		
		return false;
	}
	
	// Check an event
	public static function check($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
		
		static $cachecount = array();
		static $cachepublish = array();
		
		// Does the event exist ?
		if (!isset($cachecount[$id])) {
			$query->clear()
				->select('COUNT('.$db->qn('id').')')
				->from($db->qn('#__rseventspro_events'))
				->where($db->qn('id').' = '.(int) $id);
			
			$db->setQuery($query);
			$count = $db->loadResult();
			$cachecount[$id] = $count;
		}
		
		if (!$count) 
			return false;
		
		// Is the event published ? 
		if (!isset($cachepublish[$id])) {
			$query->clear()
				->select($db->qn('published'))
				->from($db->qn('#__rseventspro_events'))
				->where($db->qn('id').' = '.(int) $id);
			
			$db->setQuery($query);
			$published = $db->loadResult();
			
			$cachepublish[$id] = $published;
		}
		
		$query->clear()
			->select($db->qn('owner'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $id);
		
		$db->setQuery($query);
		$owner = (int) $db->loadResult();
		
		if ($owner != $user->get('id')) {
			if (!$cachepublish[$id] && !rseventsproHelper::admin())
				return false;
		}
		
		return true;
	}
	
	// Get the number of repeats
	public static function getRepeats($id) {
		$db		  = JFactory::getDbo();
		$query	  = $db->getQuery(true);
		$params   = rseventsproHelper::getParams();
		$archived = (int) $params->get('archived',0);
		
		$query->clear()
			->select('COUNT('.$db->qn('id').')')
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('parent').' = '.(int) $id);
		
		if ($archived) {
			$query->where($db->qn('published').' IN (1,2)');
		} else {
			$query->where($db->qn('published').' = 1');
		}
		
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	// Check for RSform!Pro plugin
	public static function rsform() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('enabled'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type').' = '.$db->q('plugin'))
			->where($db->qn('folder').' = '.$db->q('system'))
			->where($db->qn('element').' = '.$db->q('rsfprseventspro'));
			
		$db->setQuery($query);
		$enabled = $db->loadResult();
		
		if ($enabled && file_exists(JPATH_SITE.'/plugins/system/rsfprseventspro/rsfprseventspro.php'))
			return true;
		
		return false;
	}
	
	// Load RSForm!Pro form
	public static function loadRSForm($fid) {
		$output = '';
		$helper = JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/rsform.php';
		
		if(file_exists($helper)) {
			JFactory::getLanguage()->load('com_rsform',JPATH_SITE);
			
			require_once($helper);
			$output = RSFormProHelper::displayForm($fid,true);
		}

		return $output;
	}
	
	// Shorten text javascript
	public static function shortenjs($string, $id, $max = 255, $type = 1) {
		$text = '';
		
		if ($type == 1) {
			$string = strip_tags($string);
			$size   = rseventsproHelper::getConfig('descr_length','int');
			
			if (!empty($size)) 
				$max = $size;
			
			$string_length = mb_strlen($string);		
			if ($max >= $string_length) 
				return $string;
			
			$intro = mb_substr($string,0,$max);
			$extra = mb_substr($string,$max,$string_length);
			$extra .= '<a href="javascript:void(0);" class="rsepro_minus" onclick="rsepro_description_off('.$id.')"><i class="fa fa-minus-square-o"></i></a>';
			
			$text .= $intro;
			$text .= '<a id="rsehref'.$id.'" href="javascript:void(0);" class="rsepro_plus" onclick="rsepro_description_on('.$id.')"><i class="fa fa-plus-square-o"></i></a>';
			$text .= '<div id="rsedescription'.$id.'" class="rsepro_extra_off">'.$extra.'</div>';
		} else {
			$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
			
			if (preg_match($pattern, $string))
			{
				list($introtext, $fulltext) = preg_split($pattern, $string, 2);			
				$text .= $introtext;
				
				if ($fulltext) {
					$extra = $fulltext;
					$extra .= '<a href="javascript:void(0);" class="rsepro_minus" onclick="rsepro_description_off('.$id.')"><i class="fa fa-minus-square-o"></i></a>';
					
					$text .= '<a id="rsehref'.$id.'" href="javascript:void(0);" class="rsepro_plus" onclick="rsepro_description_on('.$id.')"><i class="fa fa-plus-square-o"></i></a>';
					$text .= '<div id="rsedescription'.$id.'" class="rsepro_extra_off">'.$extra.'</div>';
				}
			} else {
				$text .= $string;
			}
		}
		
		return $text;
	}
	
	// Remove the readmore marker
	public static function removereadmore($text) {
		$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
		return preg_replace($pattern,'',$text);
	}
	
	// Get event options
	public static function options($id) {
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/events.php';
		$event		= RSEvent::getInstance($id);
		$defaults	= $event->getDefaultOptions();
		$event		= $event->getEvent();
		$options	= $event->get('options');
		
		try {
			$registry = new JRegistry;
			$registry->loadString($options);
			if ($options = $registry->toArray()) {
				foreach ($defaults as $name => $value) {
					if (isset($options[$name])) {
						$defaults[$name] = $options[$name];
					}
				}
			}
		} catch (Exception $e) {}
		
		return $defaults;
	}
	
	// Display the gallery images
	public static function gallery($type, $id) {
		if (rseventsproHelper::isGallery()) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$registry = new JRegistry('gallery');
			
			require_once JPATH_ADMINISTRATOR.'/components/com_rsmediagallery/helpers/integration.php';
			$gallery = RSMediaGalleryIntegration::getInstance();
			
			$query->clear()
				->select($db->qn('gallery_tags'));
			
			if ($type == 'event')
				$query->from($db->qn('#__rseventspro_events'));
			else
				$query->from($db->qn('#__rseventspro_locations'));
			
			$query->where($db->qn('id').' = '.(int) $id);
			
			$db->setQuery($query);
			$tags = $db->loadResult();
			
			if ($tags) {
				$reg = new JRegistry;
				$reg->loadString($tags);
				$tags = $reg->toArray();
			}
			
			$params = rseventsproHelper::getConfig('gallery_params');
			$registry->loadString($params);
			
			return $gallery->display($tags, $registry);
		}
	}
	
	// Get the current user group
	public static function getUserGroups() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$user = JFactory::getUser();
		$rsgroups = array();
		
		$userid = $user->get('id');
		
		static $groups;
		if (empty($groups))
			$groups = rseventsproHelper::getCachedGroupDetails();
		
		if (!empty($groups)) {
			foreach ($groups as $group) {
				if (!empty($group->jgroups)) {
					try {
						$registry = new JRegistry;
						$registry->loadString($group->jgroups);
						$joomlagroups = $registry->toArray();
					} catch (Exception $e) {
						$joomlagroups = array();
					}
					
					if (!empty($joomlagroups)) {
						$user_groups = JAccess::getGroupsByUser($user->id);
							
						if (!$user->guest) {
							foreach ($user_groups as $key => $value) {
								if ($value == 1) {
									unset($user_groups[$key]);
								}
							}
						}
							
						if (!empty($joomlagroups)) {
							foreach($user_groups as $ugroup) {
								if (in_array($ugroup,$joomlagroups)) {
									$rsgroups[] = $group->id;
								}
							}
						}
					}
				}
				
				if (!empty($group->jusers)) {
					try {
						$registry = new JRegistry;
						$registry->loadString($group->jusers);
						$joomlausers = $registry->toArray();
					} catch (Exception $e) {
						$joomlausers = array();
					}
					
					if (!empty($joomlausers)) {
						if (in_array($userid,$joomlausers)) {
							$rsgroups[] = $group->id;
						}
					}
				}
			}
		}
		
		if (!empty($rsgroups)) {
			$rsgroups = array_map('intval',$rsgroups);
			$rsgroups = array_unique($rsgroups);
		}
		
		return $rsgroups;
	}
	
	// Set event metadata
	public static function metas($event) {
		$doc		= JFactory::getDocument();
		$options	= rseventsproHelper::options($event->id);
		$config		= JFactory::getConfig();
		$root		= JUri::getInstance()->toString(array('scheme','host'));
		
		if (!empty($options['enable_fb_like']) || rseventsproHelper::getConfig('event_comment','int') == 1) {
			if ($doc->getType() == 'html') {
				
				if ($admins = rseventsproHelper::getConfig('facebook_admins')) {
					$doc->addCustomTag('<meta property="fb:admins" content="'.self::escape($admins).'" />');
				}
				if ($app_id = rseventsproHelper::getConfig('facebook_app_id')) {
					$doc->addCustomTag('<meta property="fb:app_id" content="'.self::escape($app_id).'" />');
				}
				
				$doc->addCustomTag('<meta charset="utf-8">');
				$doc->addCustomTag('<meta property="og:url" content="'.htmlentities(rseventsproHelper::shareURL($event->id,$event->name,false), ENT_COMPAT, 'UTF-8').'" />');
				
				if (!empty($event->description)) {
					$content = strip_tags($event->description);
					$content = trim(substr($content,0,255));
					$content .= ' [...]';
					$content = str_replace(array("\r","\n"),' ',$content);
					$doc->addCustomTag('<meta property="og:description" content="'.htmlentities($content,ENT_COMPAT,'UTF-8').'" />');
				}
				$doc->addCustomTag('<meta property="og:title" content="'.htmlentities($event->name,ENT_COMPAT,'UTF-8').'" />');
				$doc->addCustomTag('<meta property="og:type" content="article" />');
				
				if (!empty($event->icon)) {
					$doc->addCustomTag('<meta property="og:image" content="'.rseventsproHelper::thumb($event->id, 250).'" />');
					$doc->addCustomTag('<meta property="og:image:width" content="250" />');
					$doc->addCustomTag('<meta property="og:image:height" content="200" />');
				}
			}
		}
		
		// Set a default page title
		$title = $event->name;
		
		if ($config->get('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $config->get('sitename'), $event->name);
		} elseif ($config->get('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $event->name, $config->get('sitename'));
		}
		
		$doc->setTitle($title);
		
		$meta = array('metaname' => $event->metaname, 'metakeywords' => $event->metakeywords, 'metadescription' => $event->metadescription);
		$meta = rseventsproEmails::placeholders($meta, $event->id, $event->name);
		
		if (trim($event->metaname) != '') {
			$event->metaname = str_replace('{eventname}', $event->name, $meta['metaname']);
			$doc->setTitle($event->metaname);
		}
		
		if (trim($event->metakeywords) !='') $doc->setMetaData('keywords',$meta['metakeywords']);
		if (trim($event->metadescription) !='') $doc->setDescription($meta['metadescription']);
		
		if ($event->parent && rseventsproHelper::getConfig('canonical','int', 1)) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)->select($db->qn('id'))
				->select($db->qn('name'))
				->from($db->qn('#__rseventspro_events'))
				->where($db->qn('id').' = '.$db->q($event->parent));
			$db->setQuery($query);
			if ($parent = $db->loadObject()) {
				$canonical = $root.rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($parent->id, $parent->name), false, rseventsproHelper::itemid($parent->id));
				$doc->addHeadLink($canonical, 'canonical', 'rel');
			}
		}
		
		return true;
	}
	
	// Get the share link
	public static function shareURL($id, $name, $itemid = false) {
		$root = JURI::getInstance()->toString(array('scheme','host'));
		$itemid = $itemid ? '&Itemid=999999999' : '';
		
		return $root.rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($id,$name).$itemid, false);
	}
	
	// Set tax
	public static function setTax($total, $type, $value) {
		$tax = 0;
		
		if ($value > 0) {
			if ($type == 0) {
				$tax = $value;
			} else {
				$tax = $total * ($value / 100);
			}
		}
		return $tax > 0 ? $tax : 0;
	}
	
	// Get the user avatar
	public static function getAvatar($id, $email) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$avatar = rseventsproHelper::getConfig('user_avatar');
		$default= JUri::getInstance()->toString(array('host', 'scheme')).JHtml::image('com_rseventspro/user.png', '', array(), true, 1);
		$html	= '';
		
		if (!empty($avatar)) {
			$user = JFactory::getUser($id);
			
			switch ($avatar) {
				// Gravatar
				case 'gravatar':
					$email = ($id == 0 && !empty($email)) ? md5(strtolower(trim($email))) : md5(strtolower(trim($user->get('email'))));
					$html .= '<img src="https://www.gravatar.com/avatar/'.$email.'?d='.urlencode($default).'" alt="Gravatar" class="rs_avatar" width="64" height="64" />';
				break;
				
				// Community Builder
				case 'comprofiler':
					$query->clear()
						->select($db->qn('avatar'))
						->from($db->qn('#__comprofiler'))
						->where($db->qn('user_id').' = '.(int) $id)
						->where($db->qn('avatarapproved').' = 1');
					
					$db->setQuery($query);
					if ($cavatar = $db->loadResult())
						$html .= '<img src="'.JURI::root().'images/comprofiler/tn'.$cavatar.'" alt="Community Builder Avatar" class="rs_avatar" width="64" height="64" />';
					else
						$html .= JHtml::image('com_rseventspro/user.png', 'Community Builder Avatar', array('class' => 'rs_avatar', 'width' => '64', 'height' => '64'), true);
				break;
				
				// JomSocial
				case 'community':				
					if (file_exists(JPATH_SITE.'/components/com_community/libraries/core.php')) {
						include_once(JPATH_SITE.'/components/com_community/libraries/core.php');
						$user 		= CFactory::getUser($id);
						$avatarUrl	= $user->getThumbAvatar();
						$html .= '<img src="'.$avatarUrl.'" alt="JomSocial Avatar" class="rs_avatar" width="64" height="64" />';
					} else {
						$html .= JHtml::image('com_rseventspro/user.png', 'JomSocial Avatar', array('class' => 'rs_avatar', 'width' => '64', 'height' => '64'), true);
					}
				break;
				
				// Kunena 
				case 'kunena':
					$file = JPATH_ADMINISTRATOR.'/components/com_kunena/libraries/user/user.php';
					// 2.x
					if (file_exists($file)) {
						require_once $file;
						$user = KunenaUser::getInstance($id);
						$html .= '<img src="'.$user->getAvatarURL().'" alt="Kunena Avatar" class="rs_avatar" width="64" height="64" />';
					} elseif (file_exists(JPATH_LIBRARIES.'/kunena/factory.php') || class_exists('KunenaFactory')) {
						require_once JPATH_LIBRARIES.'/kunena/factory.php';
						if ($id) {
							$profile = KunenaFactory::getUser($id);
							$html .= $profile->getAvatarImage('rs_avatar', '66');
						}
					} else {
						$html.= JHtml::image('com_rseventspro/user.png', 'Kunena Avatar', array('class' => 'rs_avatar', 'width' => '64', 'height' => '64'), true);
					}
				break;
				
				// Fireboard
				case 'fireboard':
					$query->clear()
						->select($db->qn('avatar'))
						->from($db->qn('#__fb_users'))
						->where($db->qn('userid').' = '.(int) $id);
					
					$db->setQuery($query);
					$fireboard = $db->loadResult();
				
				if (!empty($fireboard))
					$html .= '<img src="'.JURI::root().'images/fbfiles/avatars/'.$fireboard.'" alt="Fireboard Avatar" class="rs_avatar" width="64" height="64" />';
				else
					$html .= JHtml::image('com_rseventspro/user.png', 'Fireboard Avatar', array('class' => 'rs_avatar', 'width' => '64', 'height' => '64'), true);
					
				break;
				
				// K2
				case 'k2':
					$query->clear()
						->select($db->qn('image'))
						->from($db->qn('#__k2_users'))
						->where($db->qn('userID').' = '.(int) $id);
					
					$db->setQuery($query);
					$k2 = $db->loadResult();
 
					if (!empty($k2) && file_exists(JPATH_SITE.'/media/k2/users/'.$k2)) {
						$html .= '<img src="'.JURI::root().'media/k2/users/'.$k2.'" alt="K2 Avatar" class="rs_avatar" height="64" />';
					} else {
						$html .= JHtml::image('com_rseventspro/user.png', 'K2 Avatar', array('class' => 'rs_avatar', 'width' => '64', 'height' => '64'), true);
					}
				break;
				
				// EasyDiscuss
				case 'easydiscuss':
					$file = JPATH_ADMINISTRATOR.'/components/com_easydiscuss/includes/easydiscuss.php';
					
					if (file_exists($file)) {
						require_once $file;
						
						$profile = DiscussHelper::getTable('Profile')->load($id);
						$html .= '<img src="'.$profile->getAvatar().'" alt="EasyDiscuss Avatar" class="rs_avatar" width="64" height="64" />';
						
					} else {
						$html .= JHtml::image('com_rseventspro/user.png', 'EasyDiscuss Avatar', array('class' => 'rs_avatar', 'width' => '64', 'height' => '64'), true);
					}
				break;
				
				// EasySocial
				case 'easysocial':
					$file = JPATH_ADMINISTRATOR  . '/components/com_easysocial/includes/foundry.php';
					
					if (file_exists($file)) {
						require_once $file;
						
						$avatarLink = Foundry::user($id)->getAvatar(SOCIAL_AVATAR_MEDIUM);
						$html .= '<img src="'.$avatarLink.'" alt="EasySocial Avatar" class="rs_avatar" width="64" height="64" />';
					} else {
						$html .= JHtml::image('com_rseventspro/user.png', 'EasySocial Avatar', array('class' => 'rs_avatar', 'width' => '64', 'height' => '64'), true);
					}
				break;
			}
		}
		
		return $html;
	}
	
	// Get user profile link
	public static function getProfile($type, $id) {
		$profile = $type == 'guests' ? rseventsproHelper::getConfig('user_profile','int') : rseventsproHelper::getConfig('event_owner_profile','int');
		$url	 = '';
		
		if ($id == 0) {
			return $url;
		}
		
		if (!empty($profile)) {
			// JomSocial
			if ($profile == 1) {
				if (file_exists(JPATH_SITE.'/components/com_community/libraries/core.php')) {
					include_once(JPATH_SITE.'/components/com_community/libraries/core.php');
					$url = CRoute::_('index.php?option=com_community&view=profile&userid='.$id);
				}
			}
			// Community Builder
			else if ($profile == 2) {
				if (file_exists(JPATH_ADMINISTRATOR.'/components/com_comprofiler/plugin.foundation.php')) {
					include_once(JPATH_ADMINISTRATOR.'/components/com_comprofiler/plugin.foundation.php');
					global $_CB_framework;
					cbimport('cb.database');
					$url = $_CB_framework->userProfileUrl( $id, true );
				}
			} 
			// EasySocial
			else if ($profile == 3) {
				$file = JPATH_ADMINISTRATOR  . '/components/com_easysocial/includes/foundry.php';
				
				if (file_exists($file)) {
					require_once $file;
					
					$url = Foundry::user($id)->getPermalink();
				}
			}
			// EasyDiscuss
			else if ($profile == 4) {
				$file = JPATH_ADMINISTRATOR.'/components/com_easydiscuss/includes/easydiscuss.php';
					
				if (file_exists($file)) {
					require_once $file;
					
					$profile = DiscussHelper::getTable('Profile')->load($id);
					$url = $profile->getLink();
				}
			} 
			// RSEvents!Pro
			else if ($profile == 5) {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				
				$query->clear()
					->select($db->qn('name'))
					->from($db->qn('#__rseventspro_user_info'))
					->where($db->qn('id').' = '.(int) $id);
				
				$db->setQuery($query);
				$name = $db->loadResult();
				$name = !empty($name) ? $name : rseventsproHelper::getUser($id);
				
				$url = JRoute::_('index.php?option=com_rseventspro&layout=user&id='.rseventsproHelper::sef($id, $name));
			}
		}
		
		return $url;
	}
	
	public static function escape($string) {
		return htmlentities($string, ENT_COMPAT, 'utf-8');
	}
	
	// Get comments
	public static function comments($id,$name) {
		$comment = rseventsproHelper::getConfig('event_comment','int');
		
		if (empty($comment)) return;
		
		switch ($comment) {
			// Facebook comments
			case 1:
				$color 		= rseventsproHelper::getConfig('facebook_color_scheme');
				$numposts 	= rseventsproHelper::getConfig('facebook_num_posts', 'int');
				$width 		= rseventsproHelper::getConfig('facebook_width');
				$order 		= rseventsproHelper::getConfig('facebook_order_by');
				
				return '<div class="fb-comments"'.
						' data-order-by="'.self::escape($order ? $order : 'social').'"'.
						' data-href="'.rseventsproHelper::shareURL($id,$name,true).'"'.
						' data-numposts="'.self::escape($numposts ? $numposts : 5).'"'.
						' data-width="'.self::escape($width ? $width : '650').'"'.
						' data-colorscheme="'.self::escape($color ? $color : 'light').'"'.
						'></div>';
			break;
			
			// RSComments! integration
			case 2:
				if (file_exists(JPATH_SITE.'/components/com_rscomments/helpers/rscomments.php')) {
					require_once JPATH_SITE.'/components/com_rscomments/helpers/rscomments.php';
					return RSCommentsHelper::showRSComments('com_rseventspro',$id);
				}
			break;
			
			// JComments integration
			case 3:
				if (file_exists(JPATH_SITE.'/components/com_jcomments/jcomments.php')) {
					require_once JPATH_SITE.'/components/com_jcomments/jcomments.php';
					return JComments::showComments($id, 'com_rseventspro', $name);
				}
			break;

			// JomComments integration
			case 4:
				if (file_exists(JPATH_SITE.'/plugins/content/jom_comment_bot.php')) {
					require_once JPATH_SITE.'/plugins/content/jom_comment_bot.php';
					return jomcomment($id, 'com_rseventspro');
				}
			break;
			
			// Disqus integration
			case 5:
				$doc = JFactory::getDocument();
				if ($doc->getType() == 'html') {
					$doc->addScriptDeclaration("var disqus_shortname = '".addslashes(self::escape(rseventsproHelper::getConfig('disqus_shortname')))."';");
					$doc->addScriptDeclaration("(function() { var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true; dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq); })();");
					return '<div id="disqus_thread"></div>';
				}
			break;
		}
	}
	
	// Create the rating system
	public static function rating($id, $array = false) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$ip		= md5($_SERVER['REMOTE_ADDR']);
		$html	= array();
		
		// Get the rating value
		$query->clear()
			->select('CEIL(IFNULL(SUM(value)/COUNT(id),0))')
			->from($db->qn('#__rseventspro_rating'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$rating = $db->loadResult();
		
		// Get the rating count
		$query->clear()
			->select('COUNT(id)')
			->from($db->qn('#__rseventspro_rating'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$count = $db->loadResult();
		
		if ($array) {
			return array($rating, $count);
		}
		
		// Check if the user has already voted
		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__rseventspro_rating'))
			->where($db->qn('ip').' = '.$db->q($ip))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query,0,1);
		$voted = $db->loadResult();		
		
		$html[] = '<ul id="rsepro-rating">';
		
		if ($rating) {
			for ($i=0;$i<$rating;$i++) {
				$html[] = '<li><a class="fa fa-star" href="javascript:void(0);"></a></li>';
			}
			
			for ($i=$rating;$i<5;$i++) {
				$html[] = '<li><a class="fa fa-star-o" href="javascript:void(0);"></a></li>';
			}
		} else {
			for ($i=0;$i<5;$i++) {
				$html[] = '<li><a class="fa fa-star-o" href="javascript:void(0);"></a></li>';
			}
		}
		
		if (!$voted) {
			$script[] = '<script type="text/javascript">'."\n";
			$script[] = 'jQuery(document).ready(function() {'."\n";
			$script[] = "\t".'jQuery(\'#rsepro-rating\').rsrating({'."\n";
			$script[] = "\t\t".'initial	: '.(int) $rating.",\n";
			$script[] = "\t\t".'id		: '.(int) $id.",\n";
			$script[] = "\t\t".'root	: \''.addslashes(JURI::root()).'\''."\n";
			$script[] = "\t".'});'."\n";
			$script[] = '});'."\n";
			$script[] = '</script>'."\n";
			
			JFactory::getDocument()->addCustomTag('<script src="'.JHtml::script('com_rseventspro/jquery.rating.js', array('relative' => true, 'pathOnly' => true, 'version' => 'auto')).'" type="text/javascript"></script>');
			JFactory::getDocument()->addCustomTag(implode('',$script));
		}
		
		$html[] = '</ul>';
		$html[] = '<div id="rsepro_rating_loading" style="display: none;">'.JHtml::image('com_rseventspro/loader.gif', '', array('style' => 'display: none;'), true).'<span></span></div>';
		
		return implode("\n", $html);
	}
	
	// Get Cancel link
	public static function redirect($js = false,$message = null,$url = null,$reload = false, $sticky = false) {
		$link	= rseventsproHelper::getConfig('modal','int');
		$reload = $reload ? 'window.parent.location.reload();' : '';
		
		if ($reload) {
			if (empty($url))
				$redirect = 'window.parent.location.reload();';
			else 
				$redirect = 'window.parent.location = "'.addslashes($url).'";';
		} else $redirect = '';
		
		
		if ($link == 1) {
			if ($js)
			{
				$return = '<div class="rs_message_info">'.$message.'</div>';
				if (!$sticky)
					$return .= '<script type="text/javascript">window.top.setTimeout(\''.$redirect.'window.parent.jQuery.colorbox.close();\',1200);</script>';
				return $return;
			} 
			else return '<a href="javascript:void(0)" onclick="window.parent.jQuery.colorbox.close();">'.$message.'</a>';
		} 
		elseif ($link == 2) {
			if ($js) {
				$return = '<div class="rs_message_info">'.$message.'</div>';
				
				if (!$sticky)
					$return .= '<script type="text/javascript">window.top.setTimeout(\''.$redirect.'window.parent.jQuery(".modal.in .close").click();\',1200);</script>';
				return $return;
			} 
			else return '<a href="javascript:void(0)" onclick="window.parent.jQuery(\'.modal.in .close\').click();">'.$message.'</a>';
		} else {
			if ($js) {
				JFactory::getApplication()->enqueueMessage($message);
				JFactory::getApplication()->redirect($url);
			} else return '<a href="'.$url.'">'.$message.'</a>';
		}
	}
	
	// Add payment log into database
	public static function savelog($log, $id, $update=true) {
		if (!$log || !$id)
			return false;
			
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		if (!is_array($log))
			$log = array($log);
		
		foreach ($log as $i => $item)
			$log[$i] = '<b>'.rseventsproHelper::showdate('now').'</b> '.$item;
		
		$log = implode("<br />", $log);
		
		$query->clear();
		$query->update($db->qn('#__rseventspro_users'));
		$query->where($db->qn('id').' = '.(int) $id);
		
		if ($update) {
			$query->set($db->qn('log').' = CONCAT('.$db->qn('log').','.$db->q('<br />'.$log).')');
		} else {
			$query->set($db->qn('log').' = '.$db->q($log));
		}
			
		$db->setQuery($query);
		return $db->execute();
	}
	
	// Save registration
	public static function saveRegistration($idsubmission) {
		jimport( 'joomla.application.component.model' );
		JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_rseventspro/models/rseventspro.php');
		
		$model = JModelLegacy::getInstance('RseventsproModelRseventspro');
		return $model->subscribe($idsubmission);
	}
	
	// Get the discount value
	public static function discount($id, $total) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$jinput		= JFactory::getApplication()->input;
		$form		= $jinput->get('form',array(),'array');
		$coupon 	= $jinput->getString('coupon');
		$usergroups	= rseventsproHelper::getUserGroups();
		$nowunix	= JFactory::getDate()->toUnix();
		$cids		= array();
		$discounts	= array();
		
		if (!empty($form['RSEProName']) && $jinput->get('option') == 'com_rseventspro')
			$coupon		= @$form['RSEProCoupon'];
		
		$coupon = trim($coupon);
		$thecoupon	= $coupon;
		
		// Get Coupons
		$query->clear()
			->select($db->qn('id'))->select($db->qn('groups'))
			->from($db->qn('#__rseventspro_coupons'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$coupons = $db->loadObjectList();
		
		// Get Coupon Codes
		$query->clear()
			->select($db->qn('cc.code'))->select($db->qn('cc.used'))->select($db->qn('cc.idc'))
			->from($db->qn('#__rseventspro_coupon_codes','cc'))
			->join('left', $db->qn('#__rseventspro_coupons','c').' ON '.$db->qn('c.id').' = '.$db->qn('cc.idc'))
			->where($db->qn('c.ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$codes = $db->loadObjectList();
		
		// Get coupon by code
		if (!empty($codes)) {
			foreach ($codes as $code) {
				$thecode = trim($code->code);
				
				if (empty($thecode))
					continue;
				
				if ($thecode == $coupon) {
					$cids[] = $code->idc;
				}
			}
		}
		
		// Get coupons by group
		if (!empty($coupons)) {
			foreach ($coupons as $c) {
				if (!empty($c->groups)) {
					try {
						$registry = new JRegistry;
						$registry->loadString($c->groups);
						$groups = $registry->toArray();
					} catch (Exception $e) {
						$groups = array();
					}
					
					if (!empty($groups)) {
						if (!empty($usergroups) && !empty($groups)) {
							foreach ($usergroups as $usergroup) {
								if (in_array($usergroup,$groups)) {
									$cids[] = $c->id;
								}
							}
						}
					}
				}
			}
		}
		
		// Check event coupons
		if (!empty($cids)) {
			$cids = array_map('intval',$cids);
			$cids = array_unique($cids);
			
			foreach ($cids as $cid) {
				$query->clear()
					->select($db->qn('from'))->select($db->qn('to'))->select($db->qn('usage'))
					->select($db->qn('discount'))->select($db->qn('type'))
					->from($db->qn('#__rseventspro_coupons'))
					->where($db->qn('id').' = '.(int) $cid);
				
				$db->setQuery($query);
				$coupon = $db->loadObject();
				
				// Get code details
				$query->clear()
					->select($db->qn('cc.id'))->select($db->qn('cc.used'))
					->from($db->qn('#__rseventspro_coupon_codes','cc'))
					->join('left', $db->qn('#__rseventspro_coupons','c').' ON '.$db->qn('c.id').' = '.$db->qn('cc.idc'))
					->where($db->qn('cc.code').' = '.$db->q($thecoupon))
					->where($db->qn('c.ide').' = '.(int) $id)
					->where($db->qn('c.id').' = '.(int) $cid);
				
				$db->setQuery($query);
				$code = $db->loadObject();
				
				// Have we reached the max limit for this coupon ?
				if (!empty($coupon->usage) && !empty($code))
					if ($code->used >= $coupon->usage)
						continue;
				
				if ($coupon->from == $db->getNullDate()) $coupon->from = '';
				if ($coupon->to == $db->getNullDate()) $coupon->to = '';
				
				$available = true;
				if (empty($coupon->from) && empty($coupon->to)) {
					$available = true;
				} elseif (!empty($coupon->from) && empty($coupon->to)) {
					$fromunix = JFactory::getDate($coupon->from)->toUnix();
					if ($fromunix <= $nowunix)
						$available = true;
					else $available = false;
				} elseif (empty($coupon->from) && !empty($coupon->to)) {
					$tounix = JFactory::getDate($coupon->to)->toUnix();
					if ($tounix <= $nowunix)
						$available = false;
					else $available = true;
				} else {
					$fromunix = JFactory::getDate($coupon->from)->toUnix();
					$tounix = JFactory::getDate($coupon->to)->toUnix();
					
					if (($fromunix <= $nowunix && $tounix >= $nowunix) || ($fromunix >= $nowunix && $tounix <= $nowunix))
						$available = true;
					else $available = false;
				}
				
				if (!$available) continue;
				
				$discount = 0;
				if ($coupon->type) {
					$discount = $total * ($coupon->discount / 100);
					if ($discount > $total) continue;
				} else  {
					$discount = $coupon->discount;
					if ($discount > $total) continue;
				}
				
				// Try to get the id of the code that will be used
				// If the coupon input has a value then we return the id of the found code
				// Else we try to find one code valid from the other coupons (This case is used only if one coupon has the "Apply discount instantly to" option enabled) 
				if (!empty($code))
					$codeid	  = $code->id;
				else  {
					$availableids = array();
					
					// Get all coupon codes
					$query->clear()
						->select($db->qn('id'))->select($db->qn('used'))
						->from($db->qn('#__rseventspro_coupon_codes'))
						->where($db->qn('idc').' = '.(int) $cid);
					
					$db->setQuery($query);
					if ($couponcodes = $db->loadObjectList()) {
						foreach ($couponcodes as $couponcode) {
							if (!empty($coupon->usage) && $couponcode->used >= $coupon->usage)
								continue;
							$availableids[] = $couponcode->id;
						}
					}
					
					// There are no codes available
					if (empty($availableids)) continue;
					
					// Get the first availble id
					$codeid = @$availableids[0];
				}
				
				if (!$codeid) continue;
				$discounts[$codeid] = $discount;
			}
		}
		
		if (!empty($discounts)) {
			arsort($discounts);
			foreach ($discounts as $couponid => $discount) {
				return array('id' => $couponid, 'discount' => $discount);
				break;
			}
		}
		
		return 0;
	}
	
	public static function globalDiscount($id, $total, $tickets, $payment) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$jinput		= JFactory::getApplication()->input;
		$form		= $jinput->get('form',array(),'array');
		$coupon 	= $jinput->getString('coupon');
		$usergroups	= rseventsproHelper::getUserGroups();
		$nowunix	= JFactory::getDate()->toUnix();
		$codes		= array();
		$names		= array();
		$discounts	= array();
		
		if (!empty($form['RSEProName']) && $jinput->get('option') == 'com_rseventspro')
			$coupon		= @$form['RSEProCoupon'];
		
		$coupon = trim($coupon);
		$thecoupon	= $coupon;
		
		$query->clear()
			->select('*')
			->from($db->qn('#__rseventspro_discounts'))
			->where('('.$db->qn('code').' = '.$db->q($thecoupon).' OR '.$db->qn('groups').' <> '.$db->q('').')');
		
		$db->setQuery($query);
		if ($globalCoupons = $db->loadObjectList()) {
			$cids = array();
			
			foreach ($globalCoupons as $i => $globalCoupon) {
				try {
					$registry = new JRegistry;
					$registry->loadString($globalCoupon->events);
					$events = $registry->toArray();
				} catch (Exception $e) {
					$events = array();
				}
				
				try {
					$registry = new JRegistry;
					$registry->loadString($globalCoupon->groups);
					$groups = $registry->toArray();
				} catch (Exception $e) {
					$groups = array();
				}
				
				// If we found a global discount code that coresponds to the one entered or one user group is found in the global discount groups
				if ($globalCoupon->code == $thecoupon || array_intersect($groups, $usergroups)) {
					// Event assignment is set to All events
					if ($globalCoupon->apply_to == 1) {
						$cids[$globalCoupon->id] = $globalCoupon;
					} else if ($globalCoupon->apply_to == 2) {
						// Event assignment is set to Selected events
						if (is_array($id)) {
							$cids[$globalCoupon->id] = $globalCoupon;
							
							// Unset tickets
							foreach ($tickets as $eID => $ticket) {
								if (!in_array($eID ,$events)) {
									unset($tickets[$eID]);
								}
							}
						} else {
							if (in_array($id,$events)) {
								$cids[$globalCoupon->id] = $globalCoupon;
							}
						}
					} else {
						// Event assignment is set to All except those selected
						if (is_array($id)) {
							$cids[$globalCoupon->id] = $globalCoupon;
							
							// Unset tickets
							foreach ($tickets as $eID => $ticket) {
								if (in_array($eID ,$events)) {
									unset($tickets[$eID]);
								}
							}
						} else {
							if (!in_array($id,$events)) {
								$cids[$globalCoupon->id] = $globalCoupon;
							}
						}
					}
				}
			}
			
			if ($cids) {
				foreach ($cids as $cid) {
					// Have we reached the max limit for this coupon ?
					if (!empty($cid->usage)) {
						if ($cid->used >= $cid->usage) {
							continue;
						}
					}
					
					// Check the time period
					if ($cid->from == $db->getNullDate()) $cid->from = '';
					if ($cid->to == $db->getNullDate()) $cid->to = '';
					
					$available = true;
					if (empty($cid->from) && empty($cid->to)) {
						$available = true;
					} elseif (!empty($cid->from) && empty($cid->to)) {
						$fromunix = JFactory::getDate($cid->from)->toUnix();
						if ($fromunix <= $nowunix)
							$available = true;
						else $available = false;
					} elseif (empty($cid->from) && !empty($cid->to)) {
						$tounix = JFactory::getDate($cid->to)->toUnix();
						if ($tounix <= $nowunix)
							$available = false;
						else $available = true;
					} else {
						$fromunix = JFactory::getDate($cid->from)->toUnix();
						$tounix = JFactory::getDate($cid->to)->toUnix();
						
						if (($fromunix <= $nowunix && $tounix >= $nowunix) || ($fromunix >= $nowunix && $tounix <= $nowunix))
							$available = true;
						else $available = false;
					}
					
					if (!$available) {
						continue;
					}
					
					// Check tickets quantity
					if ($cid->discounttype == 1) {
						// Different tickets
						if ($cid->different_tickets) {
							if (count($tickets) <= (int) $cid->different_tickets) {
								continue;
							}
						}
					} elseif ($cid->discounttype == 2) {
						// Cart tickets - this will only work when the cart plugin is enabled
						if (!is_array($id)) {
							continue;
						}
						
						if ($cid->cart_tickets) {
							$nr = 0;
							
							if (is_array($id)) {
								foreach ($tickets as $eventID => $thetickets) {
									foreach ($thetickets as $tid => $quantity) {
										$nr += (int) $quantity;
									}
								}
							}
							
							if ($nr <= (int) $cid->cart_tickets) {
								continue;
							}
						}
					} else {
						// Same ticket
						if ($cid->same_tickets) {
							$ok = false;
							foreach ($tickets as $ticket => $quantity) {
								if ($quantity > $cid->same_tickets) {
									$ok = true;
								}
							}
							
							if (!$ok) {
								continue;
							}
						}
					}
					
					// Check total price
					if ($cid->total && $cid->totalvalue) {
						if ((float) $total < (float) $cid->totalvalue) {
							continue;
						}
					}
					
					// Check payment type
					if ($cid->payment) {
						if ($cid->paymentvalue != $payment) {
							continue;
						}
					}
					
					// Compute discounts
					$discount = 0;
					if ($cid->type == 1) {
						$discount = $total * ($cid->value / 100);
					} else {
						$discount = $cid->value;
					}
					
					if ($discount > $total) {
						continue;
					}
					
					$discounts[$cid->id] = $discount;
					$codes[$cid->id] = $cid->code;
					$names[$cid->id] = $cid->name;
				}
			}
		}
		
		if (!empty($discounts)) {
			arsort($discounts);
			foreach ($discounts as $couponid => $discount) {
				return array('id' => $couponid, 'discount' => $discount, 'code' => $codes[$couponid], 'name' => $names[$couponid]);
				break;
			}
		}
		
		return false;
	}	
	
	// Check ticket 
	public static function checkticket($id) {
		if (!defined('RSEPRO_TICKETS_NOT_AVAILABLE')) {
			define('RSEPRO_TICKETS_NOT_AVAILABLE', -1);
			define('RSEPRO_TICKETS_UNLIMITED', 0);
		}
		
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$seats	= RSEPRO_TICKETS_NOT_AVAILABLE;
		$nowunix= JFactory::getDate()->toUnix();
		
		$query->clear()
			->select('*')
			->from($db->qn('#__rseventspro_tickets'))
			->where($db->qn('id').' = '.(int) $id);
		
		$db->setQuery($query);
		if ($ticket = $db->loadObject()) {
			
			// Check for ticket availability
			$available = true;
			if ($ticket->from == $db->getNullDate()) $ticket->from = '';
			if ($ticket->to == $db->getNullDate()) $ticket->to = '';
			
			if (!empty($ticket->from) && empty($ticket->to)) {
				$fromunix  = JFactory::getDate($ticket->from)->toUnix();
				$available = $fromunix <= $nowunix ? true : false;
			} elseif (empty($ticket->from) && !empty($ticket->to)) {
				$tounix		= JFactory::getDate($ticket->to)->toUnix();
				$available	= $tounix <= $nowunix ? false : true;
			} elseif (!empty($ticket->from) && !empty($ticket->to)) {
				$fromunix	= JFactory::getDate($ticket->from)->toUnix();
				$tounix		= JFactory::getDate($ticket->to)->toUnix();
				$available	= (($fromunix <= $nowunix && $tounix >= $nowunix) || ($fromunix >= $nowunix && $tounix <= $nowunix)) ? true : false;
			}
			
			if (!$available) {
				return $seats;
			}
			
			$query->clear()
				->select('SUM('.$db->qn('ut.quantity').')')
				->from($db->qn('#__rseventspro_user_tickets','ut'))
				->join('left', $db->qn('#__rseventspro_users','u').' ON '.$db->qn('u.id').' = '.$db->qn('ut.ids'))
				->where($db->qn('u.state').' IN (0,1)')
				->where($db->qn('ut.idt').' = '.(int) $id);
			
			$db->setQuery($query);
			$ticket->purchased = $db->loadResult();
			
			$query->clear()
				->select($db->qn('overbooking'))->select($db->qn('overbooking_amount'))
				->select($db->qn('max_tickets'))->select($db->qn('max_tickets_amount'))
				->from($db->qn('#__rseventspro_events'))
				->where($db->qn('id').' = '.(int) $ticket->ide);
			
			$db->setQuery($query);
			$event = $db->loadObject();
			
			if ($event->max_tickets) {
				$query->clear()
					->select($db->qn('id'))
					->from($db->qn('#__rseventspro_tickets'))
					->where($db->qn('ide').' = '.$db->q($ticket->ide));
				$db->setQuery($query);
				$tids = $db->loadColumn();
				$tids = array_map('intval',$tids);
				
				$query->clear()
					->select('SUM('.$db->qn('ut.quantity').')')
					->from($db->qn('#__rseventspro_user_tickets','ut'))
					->join('left', $db->qn('#__rseventspro_users','u').' ON '.$db->qn('u.id').' = '.$db->qn('ut.ids'))
					->where($db->qn('u.state').' IN (0,1)')
					->where($db->qn('u.ide').' = '.(int) $ticket->ide);
				
				if (!empty($tids)) {
					$query->where($db->qn('ut.idt').' IN ('.implode(',',$tids).')');
				}
				
				JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query, 'rule' => 'u.ide')));
				
				$db->setQuery($query);
				$all_tickets_purchased = $db->loadResult();
				
				$query->clear()
					->select('SUM('.$db->qn('ut.quantity').')')
					->from($db->qn('#__rseventspro_user_tickets','ut'))
					->join('left', $db->qn('#__rseventspro_users','u').' ON '.$db->qn('u.id').' = '.$db->qn('ut.ids'))
					->where($db->qn('u.state').' IN (0,1)')
					->where($db->qn('ut.idt').' = '.(int) $id);
				
				$db->setQuery($query);
				$all_tickets_purchased_from_ticket = $db->loadResult();
			}
			
			$seats = 1;
			
			if ($ticket->seats > 0) // fixed number of tickets
			{
				$available = $ticket->seats - $ticket->purchased; // how many tickets are available ?
				if ($event->overbooking && $event->overbooking_amount > 0) // is overbooking on
				{
					// if we have tickets available
					// and the number of available tickets is smaller than the max number of tickets a user can buy
					// we can take some tickets from the overbooking amount
					// eg. max tickets = 15, max user seats = 3, overbooking = 5, available = 1
					// the user will be presented with 3 available tickets since 1 available ticket and 2 more are added from the overbooking setting
					if ($available > 0 && $available < $ticket->user_seats && $available + $event->overbooking_amount >= $ticket->user_seats)
						$available = min($available + $event->overbooking_amount, $ticket->user_seats);
						
					// if we've purchased more than the allowed number of tickets it means that we've taken them from the overbooking amount
					// this means that $available have a negative value (representing the number of overbooked tickets)
					// if we have more overbooked tickets available, add them here
					// eg. available = -1, overbooking = 5, available = -1 + 5 = 4
					if ($available < 1)
						$available = $event->overbooking_amount + $available;
				}
				
				if ($ticket->user_seats > 0) // fixed number of tickets per user
				{
					if ($available > 0) // we have tickets available
						$seats = min($available, $ticket->user_seats); // how many tickets can this user purchase?
					else // we've reached our limit
						$seats = RSEPRO_TICKETS_NOT_AVAILABLE;
				}
				else // unlimited number of tickets per user
				{
					if ($available > 0) // we have tickets available
						$seats = $available; // the user can purchase a maximum number of the tickets available because he has no limit set
					else // we've reached our limit
						$seats = RSEPRO_TICKETS_NOT_AVAILABLE;
				}
			}
			else // unlimited number of tickets
			{
				if ($ticket->user_seats > 0) // fixed number of tickets per user
					$seats = $ticket->user_seats; // the limit is set per user since we have unlimited tickets
				else // unlimited number of tickets per user
					$seats = RSEPRO_TICKETS_UNLIMITED;
			}
			
			
			if ($event->max_tickets && $event->max_tickets_amount > 0) // do we have max attendance?
			{
				if ($all_tickets_purchased >= $event->max_tickets_amount) // if the limit is reached
					$seats = RSEPRO_TICKETS_NOT_AVAILABLE;
				else
				{
					$available = $event->max_tickets_amount - $all_tickets_purchased;
					if ($ticket->user_seats > 0) // fixed number of tickets per user
					{
						if ($ticket->user_seats > $available)
							$ticket->user_seats = $available;
						
						if ($ticket->seats) 
						{
							if ($all_tickets_purchased_from_ticket < $ticket->seats)
							{
								$seats = min($ticket->user_seats,$ticket->seats - $all_tickets_purchased_from_ticket);
							}
							else if ($all_tickets_purchased_from_ticket >= $ticket->seats)
							{
								$seats = RSEPRO_TICKETS_NOT_AVAILABLE;
							}
						}
						else
							$seats = min($available,$ticket->user_seats);
					} else {
						if ($available >= $ticket->seats) {
							$seats = $ticket->seats;
						} else {
							$seats = $available;
						}
					}
					
					if ($seats < 0)
						$seats = RSEPRO_TICKETS_NOT_AVAILABLE;
				}
			}
		}
		
		return $seats;
	}
	
	// Create a new Joomla! user
	public static function returnUser($email , $name = null) {
		$db		= JFactory::getDbo();
		$params	= JComponentHelper::getParams('com_users');
		$config = JFactory::getConfig();
		$app	= JFactory::getApplication();
		$query	= $db->getQuery(true);
		$data	= new stdClass();
		
		// Check if the email is attached to an account
		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__users'))
			->where($db->qn('email').' = '.$db->q($email));
		
		$db->setQuery($query);
		$userid = $db->loadResult();
		
		if (!empty($userid)) 
			return $userid;
		
		JFactory::getLanguage()->load('com_users',JPATH_SITE);
		
		// Construct the name and username
		$name		= trim($name);
		$username	= strtolower(str_replace(' ','.',$name));
		
		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__users'))
			->where($db->qn('username').' = '.$db->q($username));
		
		$db->setQuery($query);
		if (intval($db->loadResult()) > 0 )
			$username = $username.rand(0,99);
		
		$password = JUserHelper::genRandomPassword(6);
		
		$data->name = $name;
		$data->username = $username;
		$data->email = $email;
		$data->email1 = $email;
		$data->email2 = $email;
		$data->password = $password;
		$data->password1 = $password;
		$data->password2 = $password;
		$data->groups = array($params->get('new_usertype', 2));
		
		// Get the dispatcher and load the users plugins.
		JPluginHelper::importPlugin('user');
		
		// Trigger the data preparation event.
		$results = $app->triggerEvent('onContentPrepareData', array('com_users.registration', $data));
		
		// Check for errors encountered while preparing the data.
		if (count($results) && in_array(false, $results, true)) {
			return false;
		}
		
		$data = (array) $data;
		
		$user = new JUser;

		// Get user activation option
		$useractivation = $params->get('useractivation',1);
		
		// Check if the user needs to activate their account.
		if (($useractivation == 1) || ($useractivation == 2)) {
			$data['activation'] = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
			$data['block'] = 1;
		}
		
		// Bind the data.
		if (!$user->bind($data)) {
			$app->enqueueMessage(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()), 'warning');
			return false;
		}
		
		// Store the data.
		if (!$user->save()) {
			$app->enqueueMessage(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()), 'warning');
			return false;
		}
		
		//auto approve users if CB is installed
		if (file_exists(JPATH_SITE.'/components/com_comprofiler/comprofiler.php')) {
			$query->clear()
				->insert($db->qn('#__comprofiler'))
				->set($db->qn('approved').' = 1')
				->set($db->qn('confirmed').' = 1')
				->set($db->qn('user_id').' = '.(int) $user->get('id'))
				->set($db->qn('id').' = '.(int) $user->get('id'));
			
			$db->setQuery($query);
			$db->execute();
		}
		
		// Compile the notification mail values.
		$data = $user->getProperties();
		$data['fromname']	= $config->get('fromname');
		$data['mailfrom']	= $config->get('mailfrom');
		$data['sitename']	= $config->get('sitename');
		$data['siteurl']	= JUri::root();
		
		// Handle account activation/confirmation emails.
		if ($useractivation == 2 || $useractivation == 1) {
			// Set the link to activate the user account.
			$uri = JURI::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token='.$data['activation'], false);

			$emailSubject	= JText::sprintf(
				'COM_USERS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			$emailBody = JText::sprintf(
				'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
				$data['name'],
				$data['sitename'],
				$data['siteurl'].'index.php?option=com_users&task=registration.activate&token='.$data['activation'],
				$data['siteurl'],
				$data['username'],
				$data['password_clear']
			);
		}
		else
		{
			$emailSubject	= JText::sprintf(
				'COM_USERS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			$emailBody = JText::sprintf(
				'COM_RSEVENTSPRO_EMAIL_REGISTERED_BODY',
				$data['name'],
				$data['sitename'],
				$data['siteurl'],
				$data['username'],
				$data['password_clear']
			);
		}
		
		// Send the registration email.
		$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);
		
		if ($return == false)
			return false;
		
		return $user->get('id');
	}
	
	// Error handling
	public static function error($message, $url) {
		$error = self::getConfig('errors');
		
		// 500 error
		if ($error == 0) {
			throw new Exception($message, 500);
		} elseif ($error == 1) { // 403 error
			throw new Exception($message, 403);
		} else { // Redirect
			JFactory::getApplication()->enqueueMessage($message, 'error');
			JFactory::getApplication()->redirect($url);
		}
	}
	
	// Parse styles
	public static function parseStyle($options, $unit = 'px') {
		$string = '';
		
		if (!empty($options)) {
			foreach ($options as $property => $value) {
				if (!empty($value))
					$string .= $property.': '.$value.$unit.'; ';
			}
		}
		
		return $string;
	}
	
	// Get tickets for tickets configuration
	public static function getTickets($id, $checkGroup = null) {
		$app		= JFactory::getApplication();
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$user		= JFactory::getUser();
		$userGroups	= rseventsproHelper::getUserGroups();
		
		if (is_null($checkGroup)) {
			$checkGroup	= !$app->isClient('administrator') && $app->input->get('layout','') == 'tickets';
		}
		
		$query->clear()
			->select('*')
			->from($db->qn('#__rseventspro_tickets'))
			->where($db->qn('ide').' = '.$db->q($id))
			->order($db->qn('order').' ASC');
		
		$db->setQuery($query);
		if ($tickets = $db->loadObjectList()) {
			foreach ($tickets as $i => $ticket) {
				try {
					$registry = new JRegistry;
					$registry->loadString($ticket->position);
					$tickets[$i]->position = $registry->toArray();
				} catch (Exception $e) {
					$tickets[$i]->position = array();
				}
				
				if ($checkGroup) {
					$hasAccess = true;
					
					// Do we have groups set?
					if (!empty($ticket->groups)) {
						$registry = new JRegistry($ticket->groups);
						
						if ($ticketGroups = $registry->toArray()) {
							// This ticket is restricted to certain groups - default to false
							// Below we'll identify if he has access or not
							$hasAccess = false;
							
							// First, check in the RSEvents!Pro groups
							if (!empty($userGroups)) {
								if (array_intersect($ticketGroups, $userGroups)) {
									$hasAccess = true;
								}
							}
						}
					}
					
					if (!$hasAccess) {
						unset($tickets[$i]);
					}
				}
			}
			
			return !empty($tickets) ? $tickets : array();
		}
		
		return array();
	}
	
	// Get selected seats
	public static function getSelectedSeats($tid, $ids = null) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('us.seat'))
			->from($db->qn('#__rseventspro_user_seats','us'))
			->join('LEFT', $db->qn('#__rseventspro_users','u').' ON '.$db->qn('u.id').' = '.$db->qn('us.ids'))
			->where($db->qn('u.state').' IN (0,1)')
			->where($db->qn('us.idt').' = '.(int) $tid);
			
		if ($ids) {
			$query->where($db->qn('us.ids').' = '.(int) $ids);
		}
		
		$db->setQuery($query);
		return $db->loadColumn();
	}
	
	// Get total number of used tickets
	public static function getUsedTickets($id) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__rseventspro_tickets'))
			->where($db->qn('ide').' = '.$db->q($id));
		$db->setQuery($query);
		$tids = $db->loadColumn();
		$tids = array_map('intval',$tids);
		
		$query->clear()
			->select('SUM('.$db->qn('ut.quantity').')')
			->from($db->qn('#__rseventspro_users','u'))
			->join('LEFT', $db->qn('#__rseventspro_user_tickets','ut').' ON '.$db->qn('ut.ids').' = '.$db->qn('u.id'))
			->where($db->qn('u.state').' IN (0,1)')
			->where($db->qn('u.ide').' = '.(int) $id);
		
		if (!empty($tids)) {
			$query->where($db->qn('ut.idt').' IN ('.implode(',',$tids).')');
		}
		
		JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query)));
		
		$db->setQuery($query);
		return (int) $db->loadResult();
	}
	
	// Get total number of unlimited seats purchased
	public static function getTotalUnlimited($tid, $ids) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('quantity'))
			->from($db->qn('#__rseventspro_user_tickets'))
			->where($db->qn('idt').' = '.(int) $tid)
			->where($db->qn('ids').' = '.(int) $ids);
		
		$db->setQuery($query);
		return (int) $db->loadResult();
	}
	
	// Get seats
	public static function getSeats($ids, $idt) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('seat'))
			->from($db->qn('#__rseventspro_user_seats'))
			->where($db->qn('idt').' = '.(int) $idt)
			->where($db->qn('ids').' = '.(int) $ids);
		
		$db->setQuery($query);
		if ($seats = $db->loadColumn()) {
			return JText::sprintf('COM_RSEVENTSPRO_SEATS',implode(',',$seats));
		}
	}
	
	public static function report($id) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$html		= '';
		
		$query->clear()
			->select('COUNT('.$db->qn('id').')')
			->from($db->qn('#__rseventspro_reports'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		$count = (int) $db->loadResult();
		
		if ($count) {
			$html .= '<a href="'.JRoute::_('index.php?option=com_rseventspro&view=events&layout=report&id='.$id).'" class="'.rseventsproHelper::tooltipClass().'" title="'.rseventsproHelper::tooltipText(JText::plural('COM_RSEVENTSPRO_NO_REPORTS',$count)).'">';
			$html .= '<img src="'.JURI::root().'administrator/components/com_rseventspro/assets/images/flag.png" alt="" />';
			$html .= '</a>';
		}
		
		return $html;
	}
	
	public static function getReports($id) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$data		= array(); 
		
		$query->clear()
			->select($db->qn('name'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $id);
		$db->setQuery($query);
		$name = $db->loadResult();
		
		$query->clear()
			->select('r.*')->select($db->qn('u.name'))
			->from($db->qn('#__rseventspro_reports','r'))
			->join('left',$db->qn('#__users','u').' ON '.$db->qn('u.id').' = '.$db->qn('r.idu'))
			->where($db->qn('r.ide').' = '.(int) $id);
		$db->setQuery($query);
		$reports = $db->loadObjectList();
		
		$data['name'] = $name;
		$data['data'] = $reports;
		
		return $data;
	}
	
	public static function getDefaults() {
		return array('enable_rating' => 1,
			'enable_fb_like' => 1,
			'enable_twitter' => 1,
			'enable_gplus' => 1,
			'enable_linkedin' => 1,
			'start_date' => 1,
			'start_time' => 1,
			'end_date' => 1,
			'end_time' => 1,
			'show_description' =>1,
			'show_location' => 1,
			'show_categories' => 1,
			'show_tags' => 1,
			'show_files' => 1,
			'show_contact' => 1,
			'show_map' => 1,
			'show_export' => 1,
			'show_invite' => 1,
			'show_postedby' => 1,
			'show_repeats' => 1,
			'show_hits' => 1,
			'show_print' => 1,
			'show_counter' => 0,
			'counter_utc' => 0,
			'start_date_list' => 1,
			'start_time_list' => 1,
			'end_date_list' => 1,
			'end_time_list' => 1,
			'show_location_list' => 1,
			'show_categories_list' => 1,
			'show_tags_list' => 1,
			'show_icon_list' => 1
		);
	}
	
	public static function getOptions() {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$groups 	= self::getUserGroups();
		$default	= rseventsproHelper::getDefaults();
		
		if ($groups) {
			$groups = array_map('intval',$groups);
			
			$query->clear()
				->select($db->qn('event'))
				->from($db->qn('#__rseventspro_groups'))
				->where($db->qn('id').' IN ('.implode(',',$groups).')');
			
			$db->setQuery($query);
			if ($options = $db->loadColumn()) {
				foreach ($options as $option) {
					try {
						$registry = new JRegistry;
						$registry->loadString($option);
						if ($groupOptions = $registry->toArray()) {
							foreach ($groupOptions as $property => $value) {
								if (isset($default[$property])) {
									$default[$property] = $value;
								}
							}
						}
					} catch (Exception $e) {}
				}
			}
		}
		
		return $default;
	}
	
	// Get the mask for dates
	public static function showMask($type, $options) {
		$config = rseventsproHelper::getConfig();
		
		// Set options
		$start_date = isset($options['start_date']) ? $options['start_date'] : 1;
		$start_time = isset($options['start_time']) ? $options['start_time'] : 1;
		$end_date	= isset($options['end_date']) ? $options['end_date'] : 1;
		$end_time	= isset($options['end_time']) ? $options['end_time'] : 1;
		
		$start_date_list = isset($options['start_date_list']) ? $options['start_date_list'] : 1;
		$start_time_list = isset($options['start_time_list']) ? $options['start_time_list'] : 1;
		$end_date_list	 = isset($options['end_date_list']) ? $options['end_date_list'] : 1;
		$end_time_list	 = isset($options['end_time_list']) ? $options['end_time_list'] : 1;
		
		if ($type == 'list_start') {
			if ($start_date_list && $start_time_list) {
				return $config->global_date. ' '.$config->global_time;
			} elseif ($start_date_list && !$start_time_list) {
				return $config->global_date;
			} elseif ($start_time_list && !$start_date_list) {
				return $config->global_time;
			}
		}
		
		if ($type == 'list_end') {
			if ($end_date_list && $end_time_list) {
				return $config->global_date. ' '.$config->global_time;
			} elseif ($end_date_list && !$end_time_list) {
				return $config->global_date;
			} elseif ($end_time_list && !$end_date_list) {
				return $config->global_time;
			}
		}
		
		if ($type == 'start') {
			if ($start_date && $start_time) {
				return $config->global_date. ' '.$config->global_time;
			} elseif ($start_date && !$start_time) {
				return $config->global_date;
			} elseif ($start_time && !$start_date) {
				return $config->global_time;
			}
		}
		
		if ($type == 'end') {
			if ($end_date && $end_time) {
				return $config->global_date. ' '.$config->global_time;
			} elseif ($end_date && !$end_time) {
				return $config->global_date;
			} elseif ($end_time && !$end_date) {
				return $config->global_time;
			}
		}
		
		return $config->global_date. ' '.$config->global_time;
	}
	
	// Replace content event
	public static function event($id,$itemid) {
		$app		= JFactory::getApplication();
		$doc		= JFactory::getDocument();
		$template	= $app->getTemplate();
		
		$view = new JViewLegacy(array(
			'name' => 'rseventspro',
			'layout' => 'plugin',
			'base_path' => JPATH_SITE.'/components/com_rseventspro'
		));
		
		$view->addTemplatePath(JPATH_THEMES.'/'.$template.'/html/com_rseventspro/rseventspro');
		
		// Load custom css file
		JHtml::stylesheet('com_rseventspro/plugin.css', array('relative' => true, 'version' => 'auto'));
		
		$view->id		= $id;
		$view->details	= rseventsproHelper::details($id, $itemid);
		$view->config	= rseventsproHelper::getConfig();
		$view->itemid	= $itemid ? '&Itemid='.$itemid : '';
		$event			= $view->details['event'];
		
		if (rseventsproHelper::canview($id) && $event->published && $event->completed) {
			return $view->loadTemplate();
		}
		
		return;
	}
	
	// Get the available seats for a ticket
	public static function getAvailable($ide, $idt) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('seats'))->select($db->qn('user_seats'))
			->from($db->qn('#__rseventspro_tickets'))
			->where($db->qn('id').' = '.(int) $idt);
		$db->setQuery($query);
		$ticket = $db->loadObject();
		
		if (!$ticket->seats) {
			if ($ticket->user_seats)
				return $ticket->user_seats;
			else
				return 999999999;
		} else {
			$query->clear()
				->select('SUM('.$db->qn('ut.quantity').')')
				->from($db->qn('#__rseventspro_users','u'))
				->join('left',$db->qn('#__rseventspro_user_tickets','ut').' ON '.$db->qn('ut.ids').' = '.$db->qn('u.id'))
				->where($db->qn('u.state').' IN (0,1)')
				->where($db->qn('u.ide').' = '.(int) $ide)
				->where($db->qn('ut.idt').' = '.(int) $idt);
			$db->setQuery($query);
			$purchased = (int) $db->loadResult();
			
			if ($ticket->user_seats) {
				$available = $ticket->seats - $purchased;
				return min($available,$ticket->user_seats);
			} else {
				return $ticket->seats - $purchased;
			}
		}
	}
	
	// Get subscriber details
	public static function getScan() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$app	= JFactory::getApplication();
		$input	= $app->input;
		$ide	= $input->getInt('id',0);
		$code	= str_replace(rseventsproHelper::getBarcodeOptions('barcode_prefix', 'RST-'), '', $input->getString('ticket',''));
		
		// Get subscription ID and ticket code
		list($ids, $code) = explode('-',$code,2);
		
		if (empty($ids) || empty($code)) {
			return JText::_('COM_RSEVENTSPRO_SUBSCRIBER_NOT_FOUND');
		}
		
		$query->clear()
			->select($db->qn('name'))->select($db->qn('email'))->select($db->qn('date'))
			->select($db->qn('state'))->select($db->qn('gateway'))->select($db->qn('ip'))
			->select($db->qn('discount'))->select($db->qn('early_fee'))->select($db->qn('late_fee'))
			->select($db->qn('idu'))->select($db->qn('ide'))
			->select($db->qn('tax'))->select($db->qn('coupon'))->select($db->qn('id'))
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('id').' = '.(int) $ids);
		$db->setQuery($query);
		if ($subscriber = $db->loadObject()) {
			$details = array();
			$details['subscriber'] = $subscriber;
			
			$total = 0;
			$found = false;
			
			$query->clear()
				->select($db->qn('t.id'))->select($db->qn('t.ide'))->select($db->qn('t.name'))
				->select($db->qn('t.price'))->select($db->qn('ut.quantity'))
				->from($db->qn('#__rseventspro_tickets','t'))
				->join('left',$db->qn('#__rseventspro_user_tickets','ut').' ON '.$db->qn('t.id').' = '.$db->qn('ut.idt'))
				->where($db->qn('ut.ids').' = '.(int) $ids);
			$db->setQuery($query);
			if ($tickets = $db->loadObjectList()) {
				foreach ($tickets as $ticket) {
					$total += $ticket->quantity * $ticket->price;
					
					for ($i=1;$i<=$ticket->quantity;$i++) {
						$tcode	= md5($ids.$ticket->id.$i);
						$tcode	= substr($tcode,0,4).substr($tcode,-4);
						
						if (strtolower($tcode) == strtolower($code)) {
							$found = true;
							
							$query->clear()
								->select($db->qn('name'))->select($db->qn('start'))
								->select($db->qn('end'))->select($db->qn('allday'))
								->from($db->qn('#__rseventspro_events'))
								->where($db->qn('id').' = '.$db->q($ticket->ide));
							$db->setQuery($query);
							$details['event'] = $db->loadObject();
							$details['ticket'] = $ticket;
							continue 2;
						}
					}
				}
			}
			
			if ($subscriber->discount && $total) $total = $total - $subscriber->discount;
			if ($subscriber->early_fee) $total = $total - $subscriber->early_fee;
			if ($subscriber->late_fee) $total = $total + $subscriber->late_fee;
			if ($subscriber->tax) $total = $total + $subscriber->tax;
			
			$app->triggerEvent('rsepro_scanTotal', array(array('ids' => $ids,'total' => &$total)));
			
			$details['total'] = $total;
			$details['code'] = $input->getString('ticket','');
			$details['confirmed'] = rseventsproHelper::confirmed($ids, $input->getString('ticket',''));
			
			if ($found) {
				return $details;
			} else {
				return JText::_('COM_RSEVENTSPRO_SUBSCRIBER_NOT_FOUND');
			}
		}
		
		return false;
	}
	
	// Check if a subscriber has seats assigned
	public static function hasSeats($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->select('COUNT('.$db->qn('id').')')
			->from($db->qn('#__rseventspro_user_seats'))
			->where($db->qn('ids').' = '.(int) $id);
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	// Set hits counter
	public static function hits($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->update($db->qn('#__rseventspro_events'))
			->set($db->qn('hits').' = '.$db->qn('hits').' + 1')
			->where($db->qn('id').' = '.(int) $id);
		$db->setQuery($query);
		$db->execute();
	}
	
	// Get the tooltip class
	public static function tooltipClass() {
		return 'hasTooltip';
	}
	
	// Prepare the tooltip text
	public static function tooltipText($title, $content = '') {
		static $version;
		if (!$version) {
			$version = new JVersion();
		}
		
		if ($version->isCompatible('3.1.2')) {
			return JHtml::tooltipText($title, $content, 0, 0);
		} else {
			return $title.'::'.$content;
		}
	}
	
	// Load tooltip
	public static function tooltipLoad() {
		$jversion = new JVersion();
		
		if ($jversion->isCompatible('3.3')) {
			JHtml::_('behavior.core');
		}
		
		JHtml::_('bootstrap.tooltip');
	}
	
	// Register tasks
	public static function task() {
		$input	= JFactory::getApplication()->input;
		$task	= $input->get('task');
		$type	= $input->get('type');
		
		if ($task == 'event.removeticket') {
			$input->set('task', 'rseventspro.removeticket');
		} elseif ($task == 'event.removecoupon') {
			$input->set('task', 'rseventspro.removecoupon');
		} elseif ($task == 'event.savefile') {
			$input->set('task', 'rseventspro.savefile');
		} elseif ($task == 'event.removefile') {
			$input->set('task', 'rseventspro.removefile');
		} elseif ($task == 'savedata') {
			if ($type == 'category') {
				$input->set('task', 'rseventspro.savecategory');
			} elseif ($type == 'location') {
				$input->set('task', 'rseventspro.savelocation');
			}
		} elseif ($task == 'event.apply') {
			$input->set('task', 'rseventspro.save');
		} elseif ($task == 'event.save') {
			$input->set('task', 'rseventspro.save');
			$input->set('show', 1);
		} elseif ($task == 'event.cancel') {
			$input->set('task', 'rseventspro.cancel');
		} elseif ($task == 'event.ticketsorder') {
			$input->set('task', 'rseventspro.ticketsorder');
		} elseif ($task == 'subscription.confirm') {
			$input->set('task', 'rseventspro.confirm');
		}
		
		if ($input->get('view') == 'event') {
			$input->set('view','rseventspro');
		}
	}
	
	// Create content for the info window 
	public static function locationContent($event, $single, $itemid = null, $escaped = true) {
		$view = new JViewLegacy(array(
			'name' => 'rseventspro',
			'layout' => 'mapinfo',
			'base_path' => JPATH_SITE.'/components/com_rseventspro'
		));
		
		$view->addTemplatePath(JPATH_THEMES.'/'.JFactory::getApplication()->getTemplate().'/html/com_rseventspro/rseventspro');
		
		$view->details	= rseventsproHelper::details($event->id);
		$view->single	= $single;
		$view->itemid	= is_null($itemid) ? rseventsproHelper::itemid($event->id) : $itemid;
		$view->escaped	= $escaped;
		
		$layout = $view->loadTemplate();
		return trim(str_replace(array("\r","\n"),'',$layout));
	}
	
	// Create Month Year structure
	public static function showMonthYear($date, $prefix, $type = 'default') {
		$session	= JFactory::getSession();
		$params		= rseventsproHelper::getParams();
		$order		= $params->get('ordering','start');
		$order		= $order == 'lft' ? 'start' : $order;
		
		if ($order != 'start' || !$params->get('show_monthyear',0)) {
			return false;
		}
		
		if ($type == 'default') {
			$session->clear('rsepro_last_'.$prefix);
		}
		
		$monthyear	= rseventsproHelper::showdate($date,'mY');
		$dates		= $session->get('rsepro'.$prefix, array());
		
		if ($type == 'items') {
			if ($session->get('rsepro_last_'.$prefix,'') == $monthyear) {
				return false;
			}
		}
		
		if (isset($dates[$monthyear])) {
			return false;
		} else {
			$dates[$monthyear] = 1;
			$session->set('rsepro'.$prefix, $dates);
			
			if ($type == 'items') {
				$session->set('rsepro_last_'.$prefix,$monthyear);
			}
			
			return rseventsproHelper::showdate($date,'F Y');
		}
	}
	
	public static function clearMonthYear($prefix, $monthyear = null, $type = 'default') {
		$session	= JFactory::getSession();
		$params		= rseventsproHelper::getParams();
		
		if (!$params->get('show_monthyear',0)) {
			return false;
		}
		
		$session->clear('rsepro'.$prefix);
		if ($type == 'default' && isset($monthyear)) {
			$session->set('rsepro_last_'.$prefix,$monthyear);
		}
	}
	
	// Show date
	public static function showdate($date = 'now', $format = null, $replace = false, $timezone = null) {
		$app	= JFactory::getApplication();
		$config	= rseventsproHelper::getConfig();
		$tz		= is_null($timezone) ? rseventsproHelper::getTimezone() : $timezone;
		$format	= is_null($format) ? $config->global_date. ' '.$config->global_time : $format;
		$date	= is_null($date) || $date == 'now' ? gmdate('c') : $date;
		
		if ($config->hideyear && !$app->isClient('administrator') && $replace) {
			if ((is_int($date) && date('Y') == date('Y',$date)) || date('Y') == date('Y',strtotime($date))) {
				$format = str_replace(array('Y','y','o'),'',$format);
			}
		}
		
		$date = new DateTime($date, new DateTimezone('UTC'));
		$date->setTimezone(new DateTimezone($tz));
		
		return rseventsproHelper::translatedate($date->format($format));
	}
	
	// Create the repeat scenario
	public static function createRepeatScenario($order, $type) {
		$string = '';
		
		if ($order == 1) {
			$string .= 'First';
		} elseif ($order == 2) {
			$string .= 'Second';
		} elseif ($order == 3) {
			$string .= 'Third';
		} elseif ($order == 4) {
			$string .= 'Fourth';
		} else {
			$string .= 'Last';
		}
		
		if ($type == 0) {
			$string .= ' Sunday';
		} elseif ($type == 1) {
			$string .= ' Monday';
		} elseif ($type == 2) {
			$string .= ' Tuesday';
		} elseif ($type == 3) {
			$string .= ' Wednesday';
		} elseif ($type == 4) {
			$string .= ' Thursday';
		} elseif ($type == 5) {
			$string .= ' Friday';
		} elseif ($type == 6) {
			$string .= ' Saturday';
		}
		
		return $string;
	}
	
	// Check if the timezone has changed
	public static function checkTimezone() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$offset	= rseventsproHelper::getTimezone();
		
		$query->select('COUNT('.$db->qn('id').')')
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('allday').' = 1')
			->where($db->qn('timezone').' <> '.$db->q($offset));
		$db->setQuery($query);
		return (int) $db->loadResult();
	}
	
	// Filter allowed categories
	public static function allowedCategories(&$categories) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$groups		= rseventsproHelper::getUserGroups();
		$disabled	= array();
		
		if ($groups) {
			$query->select($db->qn('restricted_categories'))
				->from($db->qn('#__rseventspro_groups'))
				->where($db->qn('id').' IN ('.implode(',',$groups).')');
			$db->setQuery($query);
			if ($restrictions = $db->loadColumn()) {
				foreach ($restrictions as $restriction) {
					try {
						$registry = new JRegistry;
						$registry->loadString($restriction);
						if ($restriction = $registry->toArray()) {
							$disabled = array_merge($disabled, $restriction);
						}
					} catch (Exception $e) {}
				}
			}
		}
		
		if ($disabled) {
			foreach ($categories as $i => $category) {
				if (in_array($category, $disabled)) {
					unset($categories[$i]);
				}
			}
		}
	}
	
	// Create the image thumbnails
	public static function createImage($event, $width, $height = 0) {
		jimport('joomla.filesystem.file');
		
		$return		= array();
		$image		= !empty($event->icon) && file_exists(JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$event->icon) ? JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$event->icon : JPATH_SITE.'/components/com_rseventspro/assets/images/blank.png';
		$extension	= JFile::getExt($image);
		
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/phpthumb/phpthumb.class.php';
		
		$thumb									= new phpThumb();
		$thumb->src 							= $image;
		$thumb->w								= $width;
		$thumb->q								= 90;
		$thumb->iar								= 1;
		$thumb->config_output_format			= $extension;
		$thumb->config_error_die_on_error		= false;
		$thumb->config_cache_disable_warning	= true;
		$thumb->config_allow_src_above_docroot	= true;
		
		if ($height > 0) {
			$thumb->h = (int) $height;
		}
		
		if (!empty($event->properties)) {
			$registry = new JRegistry;
			$registry->loadString($event->properties);
			$properties = $registry->toArray();
			
			$thumb->sx = round($properties['left']);
			$thumb->sy = round($properties['top']);
			$thumb->sw = round($properties['width']);
			$thumb->sh = round($properties['height']);
			$thumb->zc = 0;
		}
		
		if ($thumb->GenerateThumbnail()) {
			$thumb->RenderOutput();
			return array('ext' => $extension, 'content' => $thumb->outputImageData);
		}
		
		return array('ext' => $extension, 'content' => file_get_contents($image));
	}
	
	// Calculate the total of a subscription
	public static function total($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$total	= 0;
		
		if ($tickets = rseventsproHelper::getUserTickets($id)) {
			foreach ($tickets as $ticket) {
				if ($ticket->price > 0) {
					$total += (int) $ticket->quantity * $ticket->price;
				}
			}
			
			if ($total > 0) {
				$query->select($db->qn('discount'))->select($db->qn('early_fee'))
					->select($db->qn('late_fee'))->select($db->qn('tax'))
					->from($db->qn('#__rseventspro_users'))
					->where($db->qn('id').' = '.$db->q($id));
				$db->setQuery($query);
				if ($subscription = $db->loadObject()) {
					if ($subscription->discount) {
						$total = $total - $subscription->discount;
					}
					if ($subscription->early_fee) {
						$total = $total - $subscription->early_fee;
					}
					if ($subscription->late_fee) {
						$total = $total + $subscription->late_fee;
					}
					if ($subscription->tax) {
						$total = $total + $subscription->tax;
					}
				}
			}
		}
		
		return $total;
	}
	
	// Check if the current user can subscribe
	public static function getCanSubscribe($id, $skip = false) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
		$jinput	= JFactory::getApplication()->input;
		$multi	= rseventsproHelper::getConfig('multi_registration','int');
		
		$permissions = rseventsproHelper::permissions();
		
		// Get the event details
		$query->clear()
			->select($db->qn('end'))->select($db->qn('registration'))->select($db->qn('start_registration'))
			->select($db->qn('end_registration'))->select($db->qn('max_tickets'))->select($db->qn('max_tickets_amount'))
			->select($db->qn('form'))->select($db->qn('allday'))->select($db->qn('start'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.$db->q($id));
		
		$db->setQuery($query);
		$event = $db->loadObject();
		
		// Get the total number of tickets
		$query->clear()
			->select('COUNT('.$db->qn('id').')')
			->from($db->qn('#__rseventspro_tickets'))
			->where($db->qn('ide').' = '.$db->q($id));
		
		$db->setQuery($query);
		$tickets = $db->loadResult();
		
		// If we are using RSForm!Pro and we have multiple registration off we return true;
		if ($event->form != 0 && $jinput->get('layout') == 'subscribe' && !$multi && !$skip) {
			return array('status' => true);
		}
		
		// If the event does't have registration
		if (empty($event->registration)) {
			return array('status' => false, 'err' => JText::_('COM_RSEVENTSPRO_REGISTRATION_ERROR1'));
		}
		
		$nowunix = JFactory::getDate()->toUnix();
		$endunix = JFactory::getDate($event->end)->toUnix();
		
		// If the event has ended
		if ($event->allday) {
			$date = JFactory::getDate($event->start);
			$date->modify('+1 days');
			$endunix = $date->toUnix();
			
			if ($nowunix > $endunix) {
				return array('status' => false, 'err' => JText::_('COM_RSEVENTSPRO_REGISTRATION_ERROR2'));
			}
		} else {
			if ($nowunix > $endunix) {
				return array('status' => false, 'err' => JText::_('COM_RSEVENTSPRO_REGISTRATION_ERROR2'));
			}
		}
		
		// There are no tickets
		$eventtickets = array();
		if ($etickets = rseventsproHelper::getTickets($id, true)) {
			foreach ($etickets as $eticket) {
				$checkticket = rseventsproHelper::checkticket($eticket->id);				
				if ($checkticket == -1) 
					continue;
				
				$eventtickets[] = $eticket;
			}
		}
		
		if (!empty($tickets) && empty($eventtickets)) {
			if (rseventsproHelper::isThankYou($event->form)) {
				return array('status' => true);
			} else {
				return array('status' => false, 'err' => JText::_('COM_RSEVENTSPRO_REGISTRATION_ERROR6'));
			}
		}
		
		if ($event->max_tickets && $event->max_tickets_amount > 0) {
			$query->clear()
				->select('COUNT('.$db->qn('id').')')
				->from($db->qn('#__rseventspro_users'))
				->where($db->qn('ide').' = '.$id)
				->where($db->qn('state').' IN (0,1)');
			
			$db->setQuery($query);
			$all_tickets_purchased = $db->loadResult();
			
			if ($all_tickets_purchased >= (int) $event->max_tickets_amount) {
				return array('status' => false, 'err' => JText::_('COM_RSEVENTSPRO_REGISTRATION_ERROR6'));
			}
		}
		
		// Check the registration time
		$show = true;
		if ($event->start_registration == $db->getNullDate()) $event->start_registration = '';
		if ($event->end_registration == $db->getNullDate()) $event->end_registration = '';
		
		if (empty($event->start_registration)) {
			$start_registration = false;
		} else {
			$start_registration = JFactory::getDate($event->start_registration)->toUnix();
		}
		
		if (empty($event->end_registration)) {
			$end_registration = false;
		} else {
			$end_registration = JFactory::getDate($event->end_registration)->toUnix();
		}
		
		if (!empty($start_registration) && !empty($end_registration)) {
			if ($start_registration <= $nowunix && $end_registration >= $nowunix || $start_registration >= $nowunix && $end_registration <= $nowunix) {
				$show = true;
			} else {
				$show = false;
			}
		} elseif (empty($start_registration) && !empty($end_registration)) {
			if ($end_registration >= $nowunix) {
				$show = true;
			} else {
				$show = false;
			}
		} elseif (!empty($start_registration) && empty($end_registration)) {
			if ($start_registration <= $nowunix) {
				$show = true;
			} else { 
				$show = false;
			}
		} elseif (empty($start_registration) && empty($end_registration)) {
			$show = true;
		}
		
		if (!$show) {
			return array('status' => false, 'err' => JText::_('COM_RSEVENTSPRO_REGISTRATION_ERROR3'));
		}
		
		// Check for permission
		if (empty($permissions['can_register']) && !rseventsproHelper::admin()) {
			return array('status' => false, 'err' => JText::_('COM_RSEVENTSPRO_GLOBAL_PERMISSION_DENIED'));
		}
		
		// If the Multiple registration option is off we check to see if the user already registered
		if (!$multi) {
			$form	= $jinput->get('form',array(),'array');
			$email	= isset($form['RSEProEmail']) ? $form['RSEProEmail'] : $jinput->getString('email');
			$email	= trim($email);
			
			$query->clear()
				->select($db->qn('u.id'))
				->from($db->qn('#__rseventspro_users','u'))
				->where($db->qn('u.ide').' = '.$id);
				
			if ($user->get('id') > 0) {
				$query->where($db->qn('u.idu').' = '.$db->q($user->get('id')));
			} else {
				$query->where($db->qn('u.email').' = '.$db->q($email));
			}
			
			JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query, 'rule' => 'u.ide')));
			
			$db->setQuery($query);
			if ($db->loadResult()) {
				return array('status' => false, 'err' => JText::_('COM_RSEVENTSPRO_REGISTRATION_ERROR5'));
			}
		}
		
		return array('status' => true);
	}
	
	// Check for thankyou message
	public static function isThankYou($form) {
		$thankyou	= false;
		$formparams = JFactory::getSession()->get('com_rsform.formparams.'.$form);		
		
		if (isset($formparams->formProcessed)) 
			$thankyou = true;
		
		return $thankyou;
	}
	
	// Get custom event Itemid
	public static function itemid($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		// Get the event details
		$query->clear()
			->select($db->qn('itemid'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.$db->q($id));
		
		$db->setQuery($query);
		if ($itemid = (int) $db->loadResult()) {
			return $itemid;
		}
		
		return '';
	}
	
	public static function getLanguageCode() {
		$langs		= JLanguageHelper::getLanguages('lang_code');
		$tag		= JFactory::getLanguage()->getTag();
		$current	= isset($langs[$tag]) ? $langs[$tag] : $langs['en-GB'];
		
		if (JLanguageMultilang::isEnabled()) {
			return $current->sef;
		}
	}
	
	public static function showprice($price) {
		list($digits, $decimals) = explode('.',$price);
		return strlen(rtrim($decimals,0)) > 2 ? number_format($price, 3, '.', '') : number_format($price, 2, '.', '');
	}
	
	// Check if ticket is confirmed
	public static function confirmed($id, $code) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select($db->qn('id'))
			->from($db->qn('#__rseventspro_confirmed'))
			->where($db->qn('ids').' = '.$db->q($id))
			->where($db->qn('code').' = '.$db->q($code));
		
		$db->setQuery($query);
		return (int) $db->loadResult();
	}
	
	// Create event thumb image
	public static function thumb($id, $width, $height = null) {
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/image.php';
		
		$image = RSEventsProImage::getInstance($id, $width, $height);
		
		return $image->output();
	}
	
	// Get event default image
	public static function defaultImage() {
		if ($default = rseventsproHelper::getConfig('default_image')) {
			if (file_exists(JPATH_SITE.'/components/com_rseventspro/assets/images/default/'.$default)) {
				return JUri::root().'components/com_rseventspro/assets/images/default/'.$default;
			}
		}
		
		return JUri::root().'components/com_rseventspro/assets/images/blank.png';
	}
	
	// Create event details for calendar tooltip
	public static function calendarTooltip($id) {
		$template	= JFactory::getApplication()->getTemplate();
		
		$view = new JViewLegacy(array(
			'name' => 'calendar',
			'layout' => 'tooltip',
			'base_path' => JPATH_SITE.'/components/com_rseventspro'
		));
		
		$view->addTemplatePath(JPATH_THEMES.'/'.$template.'/html/com_rseventspro/calendar');
		
		$view->id		= $id;
		$view->details	= rseventsproHelper::details($id);
		$view->config	= rseventsproHelper::getConfig();
		
		return htmlentities($view->loadTemplate(), ENT_QUOTES, 'UTF-8');
	}
	
	// Sort discounts
	public static function sort_discounts($a, $b) {
		if ($a->discount == $b->discount) return 0;
		return ($a->discount < $b->discount) ? 1 : -1;
	}
	
	// Check for PDF layout
	public static function hasPDFLayout($layout, $SubmissionId) {
		if (rseventsproHelper::pdf()) {
			// Search for a RSForm!Pro form
			if ($SubmissionId) {
				try {
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					
					$query->clear()
						->select($db->qn('rr.ticketpdf'))->select($db->qn('rr.ticketpdf_layout'))
						->from($db->qn('#__rsform_rseventspro','rr'))
						->join('LEFT', $db->qn('#__rsform_submissions','rs').' ON '.$db->qn('rs.FormId').' = '.$db->qn('rr.form_id'))
						->where($db->qn('rs.SubmissionId').' = '.$db->q($SubmissionId))
						->where($db->qn('rr.published').' = '.$db->q(1));
					$db->setQuery($query);
					if ($object = $db->loadObject()) {
						if ($object->ticketpdf) {
							return $object->ticketpdf_layout;
						}
						
						return $layout;
					}
				} catch (Exception $e) { }
			}
			
			return $layout;
		}
		
		return false;
	}
	
	// Check for coordinates
	public static function checkCoordinates($coordinates) {
		// Lets check if there are any coordinates entered
		if (empty($coordinates)) return '';

		// Check if the coordinates are properly delimited
		if (strpos($coordinates, ',') === false) {
			throw new Exception(JText::_('COM_RSEVENTSPRO_LOCATION_COORDINATES_ERROR_DELIMITER'));
		}
		
		list($lat, $lng) = explode(',', $coordinates, 2);
		
		// Get rid of unwanted spaces
		$lat = (float) trim($lat);
		$lng = (float) trim($lng);
		
		// Make sure range is correct
		if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
			throw new Exception(JText::_('COM_RSEVENTSPRO_LOCATION_COORDINATES_ERROR_RANGE'));
		}
		
		return "{$lat},{$lng}";
	}
	
	public static function facebookEvents($jform = null) {
		$db				= JFactory::getDbo();
		$query			= $db->getQuery(true);
		$config 		= rseventsproHelper::getConfig();
		$allowed		= $config->facebook_pages;
		$allowed		= !empty($allowed) ? explode(',',$allowed) : '';
		$checkOwnerPage	= isset($jform['facebook_check_owner']) ? $jform['facebook_check_owner'] : (isset($config->facebook_check_owner) ? $config->facebook_check_owner : 1);
		$checkOwnerUser	= isset($jform['facebook_check_owner_profile']) ? $jform['facebook_check_owner_profile'] : (isset($config->facebook_check_owner_profile) ? $config->facebook_check_owner_profile : 1);
		$expired		= isset($jform['facebook_expired']) ? $jform['facebook_expired'] : (isset($config->facebook_expired) ? $config->facebook_expired : 1);
		$profile		= isset($jform['facebook_profile']) ? $jform['facebook_profile'] : (isset($config->facebook_profile) ? $config->facebook_profile : 1);
		$recurring		= isset($jform['facebook_recurring']) ? $jform['facebook_recurring'] : (isset($config->facebook_recurring) ? $config->facebook_recurring : 1);
		$owners			= array();
		$container		= array();
		$log			= array();
		$i				= 0;
		
		$now		= new DateTime();
		$now->setTimezone(new DateTimeZone('UTC'));
		
		try {
			require_once JPATH_SITE.'/components/com_rseventspro/helpers/facebook/autoload.php';
			
			$facebook = new Facebook\Facebook(array(
				'app_id' => $config->facebook_appid,
				'app_secret' => $config->facebook_secret,
				'default_graph_version' => 'v2.10',
				'default_access_token' => $config->facebook_token
			));
			
			$fbRequest	= $facebook->get('me');
			$user		= $fbRequest->getDecodedBody();
			$uid 		= $user['id'];
			$fbRequest	= $facebook->get('me/accounts?fields=id');
			$pages		= $fbRequest->getDecodedBody();
			$fbpages	= array();
			$allevents	= array();
			$owners[]	= $uid;
			
			if (!empty($pages) && !empty($pages['data'])) {
				foreach($pages['data'] as $page) {
					if (!empty($allowed)) {
						foreach ($allowed as $pid) {
							$pid = trim($pid);
							if ($pid == $page['id']) {
								$fbpages[] = $page['id'];
								$owners[] = $page['id'];
							}
						}
					} else {
						$fbpages[] = $page['id'];
						$owners[] = $page['id'];
					}
				}
			}
			
			// Get user events
			if ($profile) {
				$fbRequest	= $facebook->get('me/events?fields=id,name,start_time,end_time,timezone,description,owner,cover,place&limit=300');
				$events		= $fbRequest->getDecodedBody();
				
				if (!empty($events) && !empty($events['data'])) {
					foreach ($events['data'] as $event) {
						$log[$event['id']] = array('name' => $event['name'], 'date' => JFactory::getDate()->toSql(), 'imported' => false, 'message' => '', 'page' => 'FBUSER', 'from' => @$event['owner']['name'], 'eventID' => 0);
						
						$owner	 = isset($event['owner']) ? $event['owner'] : array();
						$ownerID = !empty($owner) && !empty($owner['id']) ? $owner['id'] : 0;
						
						if ($checkOwnerUser) {
							if (!in_array($ownerID, $owners)) {
								$log[$event['id']]['message'] = JText::_('COM_RSEVENTSPRO_SYNC_LOG_ERROR_OWNER');
								continue;
							} else {
								$allevents[$event['id']] = $event;
							}
						} else {
							$allevents[$event['id']] = $event;
						}
						
						// Check for recurring events
						if (isset($event['event_times']) && is_array($event['event_times']) && $recurring) {
							foreach($event['event_times'] as $recurringEvent) {
								$log[$recurringEvent['id']] = array('name' => '[REC] '.$event['name'], 'date' => JFactory::getDate()->toSql(), 'imported' => false, 'message' => '', 'page' => 'FBUSER', 'from' => @$event['owner']['name'], 'eventID' => 0);
								
								$clone = $event;
								$clone['id'] = $recurringEvent['id'];
								$clone['start_time'] = $recurringEvent['start_time'];
								$clone['end_time'] = $recurringEvent['end_time'];
								$clone['parent'] = $event['id'];
								unset($clone['event_times']);
								
								$allevents[$recurringEvent['id']] = $clone;
							}
						}
					}
				}
			}
			
			// Get page events
			if (!empty($fbpages)) {
				foreach ($fbpages as $pageid) {
					$fbpRequest = $facebook->get('/'.$pageid.'?fields=name');
					$page = $fbpRequest->getDecodedBody();
					$pageName = isset($page['name']) ? $page['name'] : '-';
					
					$fbRequest	= $facebook->get('/'.$pageid.'/events?fields=id,name,start_time,end_time,timezone,description,owner,cover,place,event_times&limit=300');
					$pageEvents = $fbRequest->getDecodedBody();
					
					if (!empty($pageEvents) && !empty($pageEvents['data'])) {
						foreach ($pageEvents['data'] as $pageEvent) {
							$log[$pageEvent['id']] = array('name' => $pageEvent['name'], 'date' => JFactory::getDate()->toSql(), 'imported' => false, 'message' => '', 'page' => $pageName, 'from' => @$pageEvent['owner']['name'], 'eventID' => 0);
							
							$owner	 = isset($pageEvent['owner']) ? $pageEvent['owner'] : array();
							$ownerID = !empty($owner) && !empty($owner['id']) ? $owner['id'] : 0;
							
							if ($checkOwnerPage) {
								if (!in_array($ownerID, $owners)) {
									$log[$pageEvent['id']]['message'] = JText::_('COM_RSEVENTSPRO_SYNC_LOG_ERROR_OWNER');
									continue;
								} else {
									$allevents[$pageEvent['id']] = $pageEvent;
								}
							} else {
								$allevents[$pageEvent['id']] = $pageEvent;
							}
							
							// Check for recurring events
							if (isset($pageEvent['event_times']) && is_array($pageEvent['event_times']) && $recurring) {
								foreach($pageEvent['event_times'] as $recurringEvent) {
									$log[$recurringEvent['id']] = array('name' => '[REC] '.$pageEvent['name'], 'date' => JFactory::getDate()->toSql(), 'imported' => false, 'message' => '', 'page' => $pageName, 'from' => @$pageEvent['owner']['name'], 'eventID' => 0);
									
									$clone = $pageEvent;
									$clone['id'] = $recurringEvent['id'];
									$clone['start_time'] = $recurringEvent['start_time'];
									$clone['end_time'] = $recurringEvent['end_time'];
									$clone['parent'] = $pageEvent['id'];
									unset($clone['event_times']);
									
									$allevents[$recurringEvent['id']] = $clone;
								}
							}
						}
					}
				}
			}
			
			// Check for already imported events
			if ($eventIDs = array_keys($allevents)) {
				$query->clear()
					->select('id')
					->from($db->qn('#__rseventspro_sync'))
					->where($db->qn('id').' IN ('.rseventsproHelper::quoteImplode($eventIDs).')')
					->where($db->qn('from').' = '.$db->q('facebook'));
				$db->setQuery($query);
				if ($dbEvents = $db->loadColumn()) {
					foreach ($dbEvents as $dbEvent) {
						unset($allevents[$dbEvent]);
						$log[$dbEvent]['message'] = JText::_('COM_RSEVENTSPRO_SYNC_LOG_ERROR_DB');
					}
				}
			}
			
			// Parse events
			if (!empty($allevents)) {
				foreach ($allevents as $event) {
					$cover		= isset($event['cover']) ? $event['cover'] : array();
					$timezone	= isset($event['timezone']) ? $event['timezone'] : null;
					$image		= '';
					
					if (!empty($cover) && !empty($cover['source'])) {
						$image = isset($cover['source']) ? $cover['source'] : '';
					}
					
					$ev					= new stdClass();
					$ev->id				= @$event['id'];
					$ev->name			= @$event['name'];
					$ev->description	= @$event['description'];
					
					if (isset($event['start_time'])) {
						$startDate = new DateTime($event['start_time']);
					} else {
						$startDate = new DateTime();
					}
					
					$startDate->setTimezone(new DateTimeZone('UTC'));
					$start = $startDate->format('Y-m-d H:i:s');
					
					if (isset($event['end_time'])) {
						$endDate = new DateTime($event['end_time']);
						$endDate->setTimezone(new DateTimeZone('UTC'));
						$end = $endDate->format('Y-m-d H:i:s');
					} else {
						$endDate = clone $startDate;
						$endDate->modify('+ 2 hour');
						$end = $endDate->format('Y-m-d H:i:s');
					}
					
					if (!$expired) {
						if ($now > $endDate) {
							$log[$ev->id]['message'] = JText::_('COM_RSEVENTSPRO_SYNC_LOG_EXPIRED');
							continue;
						}
					}
					
					$ev->start			= $start;
					$ev->end			= $end;
					$ev->allday			= 0;
					$ev->timezone		= $timezone;
					$ev->parent			= isset($event['parent']) ? $event['parent'] : 0;
					$ev->location		= isset($event['place']['name']) ? $event['place']['name'] : 'Facebook Location';
					$ev->street			= isset($event['place']['location']['street']) ? $event['place']['location']['street'] : '';
					$ev->city			= isset($event['place']['location']['city']) ? $event['place']['location']['city'] : '';
					$ev->state			= isset($event['place']['location']['state']) ? $event['place']['location']['state'] : '';
					$ev->country		= isset($event['place']['location']['country']) ? $event['place']['location']['country'] : '';
					$ev->lat			= isset($event['place']['location']['latitude']) ? $event['place']['location']['latitude'] : '';
					$ev->lon			= isset($event['place']['location']['longitude']) ? $event['place']['location']['longitude'] : '';
					$ev->image			= $image;
					$ev->fbid			= $image;
					
					$container[] = $ev; 
				}
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			return false;
		}
		
		if (!empty($container)) {
			$idcategory = isset($jform['facebook_category']) ? $jform['facebook_category'] : $config->facebook_category;
			
			if (empty($idcategory)) {
				$query->clear()
					->insert($db->qn('#__rseventspro_categories'))
					->set($db->qn('name').' = '.$db->q('Facebook events'));
				
				$db->setQuery($query);
				$db->execute();
				$idcategory = $db->insertid();
			}
			
			foreach ($container as $event) {
				$idlocation = isset($jform['facebook_location']) ? $jform['facebook_location'] : $config->facebook_location;
				
				if (empty($idlocation)) {
					$address = $event->street;
					if (!empty($event->city))		$address .= ' , '.$event->city;
					if (!empty($event->state))		$address .= ' , '.$event->state;
					if (!empty($event->country))	$address .= ' , '.$event->country;
					
					// Check if we already have this location
					$query->clear()->select($db->qn('id'))
						->from($db->qn('#__rseventspro_locations'))
						->where($db->qn('name').' = '.$db->q($event->location))
						->where($db->qn('address').' = '.$db->q($address))
						->where($db->qn('coordinates').' = '.$db->q($event->lat.','.$event->lon));
					$db->setQuery($query);
					if (!$idlocation = (int) $db->loadResult()) {
						$query->clear()
							->insert($db->qn('#__rseventspro_locations'))
							->set($db->qn('name').' = '.$db->q($event->location))
							->set($db->qn('address').' = '.$db->q($address))
							->set($db->qn('coordinates').' = '.$db->q($event->lat.','.$event->lon))
							->set($db->qn('published').' = '.$db->q(1));
						
						$db->setQuery($query);
						$db->execute();
						$idlocation = $db->insertid();
					}
				}
				
				$query->clear()
					->insert($db->qn('#__rseventspro_events'))
					->set($db->qn('location').' = '.$db->q($idlocation))
					->set($db->qn('owner').' = '.$db->q(JFactory::getUser()->get('id')))
					->set($db->qn('name').' = '.$db->q($event->name))
					->set($db->qn('description').' = '.$db->q($event->description))
					->set($db->qn('start').' = '.$db->q($event->start))
					->set($db->qn('end').' = '.$db->q($event->end))
					->set($db->qn('allday').' = '.$db->q($event->allday))
					->set($db->qn('options').' = '.$db->q(rseventsproHelper::getDefaultOptions()))
					->set($db->qn('completed').' = '.$db->q(1))
					->set($db->qn('published').' = '.$db->q(1));
				
				if ($event->timezone) {
					$query->set($db->qn('timezone').' = '.$db->q($event->timezone));
				}
				
				$db->setQuery($query);
				$db->execute();
				$idevent = $db->insertid();
				
				$log[$event->id]['imported'] = true;
				$log[$event->id]['eventID'] = $idevent;
				
				$query->clear()
					->insert($db->qn('#__rseventspro_taxonomy'))
					->set($db->qn('ide').' = '.$db->q($idevent))
					->set($db->qn('id').' = '.$db->q($idcategory))
					->set($db->qn('type').' = '.$db->q('category'));
				
				$db->setQuery($query);
				$db->execute();
				
				$query->clear()
					->insert($db->qn('#__rseventspro_sync'))
					->set($db->qn('id').' = '.$db->q($event->id))
					->set($db->qn('ide').' = '.$db->q($idevent))
					->set($db->qn('from').' = '.$db->q('facebook'));
				
				$db->setQuery($query);
				$db->execute();
				
				//create the thumb
				if (!empty($event->image)) {
					jimport('joomla.filesystem.file');
					$path = JPATH_SITE.'/components/com_rseventspro/assets/images/events/';
					
					// Try to create a tmp filename and write the content of the image in it
					$tmp = tempnam(JPATH_SITE.'/components/com_rseventspro/assets/images', 'temp');
					if ($tmp) {
						file_put_contents($tmp, file_get_contents($event->image));
					
						$ext		= 'jpg';
						$filename	= $event->id;
					
						while (file_exists($path.$filename.'.'.$ext)) {
							$filename .= rand(1,999);
						}
					
						rseventsproHelper::resize($tmp, 0, $path.$filename.'.'.$ext);
					
						$query->clear()
							->update($db->qn('#__rseventspro_events'))
							->set($db->qn('icon').' = '.$db->q($filename.'.'.$ext))
							->where($db->qn('id').' = '.$db->q($idevent));
						
						$db->setQuery($query);
						$db->execute();
						
						@unlink($tmp);
					}
				}
				$i++;
			}
		}
		
		if ($log) {
			rseventsproHelper::saveSyncLog($log, 'facebook');
		}
		
		return array($i, count($container));
	}
	
	public static function quoteImplode($array) {
		$db = JFactory::getDbo();
		
		foreach ($array as $i => $val) {
			$array[$i] = $db->q($val);
		}
		
		return implode(',', $array);
	}
	
	public static function richSnippet($details) {
		$json	=  array();
		$event	= $details['event'];
		$root	= JUri::getInstance()->toString(array('scheme','host','port'));
		$end	= $event->allday ? $event->start : $event->end;
		
		$startReg	 = !empty($event->start_registration) && $event->start_registration != '0000-00-00 00:00:00' ? $event->start_registration : $event->start;
		$description = empty($event->description) ? $event->small_description : $event->description;
		
		$json['@context'] = 'https://schema.org';
		$json['@type'] = 'Event';
		$json['name'] = $event->name;
		$json['startDate'] = rseventsproHelper::showdate($event->start,'c');
		$json['endDate'] = rseventsproHelper::showdate($end,'c');
		$json['url'] = $root.rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),false,rseventsproHelper::itemid($event->id));
		$json['image'] = $details['image_b'];
		$json['description'] = strip_tags($description);
		$json['eventStatus'] = 'http://schema.org/EventScheduled';
		$json['location']['@type'] = 'Place';
		$json['location']['name'] = $event->location;
		$json['location']['address']['@type'] = 'PostalAddress';
		$json['location']['address']['name'] = $event->address;
		
		if ($event->coordinates) {
			list($lat, $lon) = explode(',',$event->coordinates,2);
			
			$json['location']['geo']['@type'] = 'GeoCoordinates';
			$json['location']['geo']['latitude'] = $lat;
			$json['location']['geo']['longitude'] = $lon;
		}
		
		if ($rating = rseventsproHelper::rating($event->id, true)) {
			list($ratingNr, $ratingCnt) = $rating;
			
			$json['aggregateRating']['@type'] = 'AggregateRating';
			$json['aggregateRating']['ratingValue'] = $ratingNr;
			$json['aggregateRating']['reviewCount'] = $ratingCnt;
		}
		
		if ($tickets = $details['tickets_data']) {
			$offers = array();
			foreach ($tickets as $ticket) {
				$offer = array();
				$offer['@type'] = 'AggregateOffer';
				$offer['priceCurrency'] = 'EUR';
				$offer['price'] = $ticket->price;
				$offer['availability'] = 'http://schema.org/InStock';
				$offer['availabilityStarts'] = rseventsproHelper::showdate($startReg,'c');
				$offer['validFrom'] = rseventsproHelper::showdate($startReg,'c');
				$offer['url'] = $root.rseventsproHelper::route('index.php?option=com_rseventspro&layout=subscribe&id='.rseventsproHelper::sef($event->id,$event->name),false,rseventsproHelper::itemid($event->id));
				$offer['inventoryLevel'] = '-';
				
				if (isset($ticket->available)) {
					$offer['offerCount'] = $ticket->available;
				}
				
				$offers[] = $offer;
			}
			
			if ($offers) {
				$json['offers'] = $offers;
			}
		}
		
		if ($speakers = $details['speakers']) {
			$performers = array();
			foreach ($speakers as $speaker) {
				$performer = array();
				$performer['@type'] = 'Person';
				$performer['name'] = $speaker->name;
				
				if ($speaker->image) {
					$performer['image'] = JUri::root().'components/com_rseventspro/assets/images/speakers/'.$speaker->image;
				}
				
				$performers[] = $performer;
			}
			
			if ($performers) {
				$json['performer'] = $performers;
			}
		}
		
		$script = '<script type="application/ld+json">'."\n";
		$script .= json_encode($json, rseventsproHelper::json_options())."\n";
		$script .= '</script>';
		
		if (JFactory::getDocument()->getType() == 'html') {
			JFactory::getDocument()->addCustomTag($script);
		}
	}
	
	public static function json_options() {
		if (version_compare(phpversion(), '5.4.0', '<')) {
			return 0;
		}
		
		return JSON_PRETTY_PRINT;
	}
	
	public static function showMarker($marker) {
		if (substr($marker,0,4) == 'http') {
			return $marker;
		}
		
		return JUri::root().$marker;
	}
	
	public static function getUserProfile($id) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		
		$query->clear()->select('*')
			->from($db->qn('#__rseventspro_user_info'))
			->where($db->qn('id').' = '.$db->q($id));
		$db->setQuery($query);
		if ($data = $db->loadObject()) {
			return $data;
		}
		
		$data = new stdClass;
		$data->id = $id;
		$data->name = rseventsproHelper::getUser($id);
		$data->image = '';
		$data->description = '';
		
		return $data;
	}
	
	public static function getUserEvents($id, $type = 'created') {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$excluded	= rseventsproHelper::excludeEvents();
		$cart		= false;
		
		JFactory::getApplication()->triggerEvent('rsepro_isCart', array(array('cart' => &$cart)));
		
		$query->select('DISTINCT '.$db->qn('e.id'))->select($db->qn('e.name'))
			->select($db->qn('e.start'))->select($db->qn('e.end'))
			->select($db->qn('e.allday'))->select($db->qn('e.itemid'))
			->select($db->qn('e.published'))
			->from($db->qn('#__rseventspro_events','e'))
			->where($db->qn('e.published').' IN (1,2)')
			->where($db->qn('e.completed').' = '.$db->q(1));
		
		if ($type == 'created') {
			$query->where($db->qn('e.owner').' = '.$db->q($id));
		} else {
			$query->join('LEFT',$db->qn('#__rseventspro_users','u').' ON '.$db->qn('u.ide').' = '.$db->qn('e.id'))
				->where($db->qn('u.idu').' = '.$db->q($id));
		}
		
		$db->setQuery($query);
		$events = $db->loadObjectList();
		
		if ($cart && $type == 'join') {
			$newquery = $db->getQuery(true);
			$subquery = $db->getQuery(true);
			
			$subquery->select('GROUP_CONCAT('.$db->qn('c.events').' SEPARATOR ",")');
			$subquery->from($db->qn('#__rseventspro_users','u'));
			$subquery->join('left',$db->qn('#__rseventspro_cart','c').' ON '.$db->qn('c.ids').' = '.$db->qn('u.id'));
			$subquery->where($db->qn('u.ide').' = '.$db->q(0));
			$subquery->where($db->qn('u.idu').' = '.$db->q($id));
			
			$db->setQuery($subquery);
			if ($eventIDs = $db->loadResult()) {
				$newquery->select('DISTINCT '.$db->qn('e.id'))->select($db->qn('e.name'))
					->select($db->qn('e.start'))->select($db->qn('e.end'))
					->select($db->qn('e.allday'))->select($db->qn('e.itemid'))
					->select($db->qn('e.published'))
					->from($db->qn('#__rseventspro_events','e'))
					->where($db->qn('e.completed').' = '.$db->q(1))
					->where($db->qn('e.published').' IN (1,2)')
					->where($db->qn('e.id').' IN ('.$eventIDs.')');
				
				$db->setQuery($newquery);
				if ($cartEvents = $db->loadObjectList()) {
					$events = array_merge($events, $cartEvents);
					$events = array_unique($events, SORT_REGULAR);
				}
			}
		}
		
		if ($excluded) {
			foreach ($events as $i => $event) {
				if (in_array($event->id, $excluded)) {
					unset($events[$i]);
				}
			}
		}
		
		return $events;
	}
	
	public static function saveSyncLog($logs, $type) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		if ($logs) {
			foreach ($logs as $eventID => $log) {
				if (empty($log['date'])) continue;
				
				$query->clear()
					->insert($db->qn('#__rseventspro_sync_log'))
					->set($db->qn('type').' = '.$db->q($type))
					->set($db->qn('date').' = '.$db->q($log['date']))
					->set($db->qn('name').' = '.$db->q($log['name']))
					->set($db->qn('imported').' = '.$db->q((int) $log['imported']))
					->set($db->qn('message').' = '.$db->q($log['message']))
					->set($db->qn('page').' = '.$db->q($log['page']))
					->set($db->qn('from').' = '.$db->q($log['from']))
					->set($db->qn('eid').' = '.$db->q($log['eventID']))
					->set($db->qn('importid').' = '.$db->q($eventID));
				
				$db->setQuery($query);
				$db->execute();
			}
		}
	}
	
	public static function getIP() {
		if (rseventsproHelper::getConfig('store_ip', 1)) {
			return JFactory::getApplication()->input->server->getString('REMOTE_ADDR');
		} else {
			return '0.0.0.0';
		}
	}
	
	public static function isCart($version = null) {
		$cart = false;
		
		JFactory::getApplication()->triggerEvent('rsepro_isCart', array(array('cart' => &$cart)));
		
		if (!is_null($version) && $cart) {
			if (file_exists(JPATH_SITE.'/plugins/system/rseprocart/rseprocart.xml')) {
				$xml = file_get_contents(JPATH_SITE.'/plugins/system/rseprocart/rseprocart.xml');
				preg_match('#<version>(.*?)<\/version>#is', $xml, $match);
				$xmlversion = isset($match) && isset($match[1]) ? $match[1] : false;
				
				if ($xmlversion) {
					if (!version_compare($xmlversion, $version, '>=')) {
						return false;
					}
				}
			}
		}
		
		return $cart;
	}
	
	public static function getBarcodeOptions($param, $default = null) {
		$plugin = JPluginHelper::getPlugin('system', 'rsepropdf');
		$params = isset($plugin->params) ? $plugin->params : '';
		$reg	= new JRegistry;
		
		$reg->loadString($params);
		
		return $reg->get($param, $default);
	}
	
	public static function getRSVPOptions($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$uid	= JFactory::getUser()->get('id');
		$data	= new stdClass();
		
		$data->offClass = $uid ? '' : ' hasTooltip disabled';
		$data->offTitle = $uid ? '' : JText::_('COM_RSEVENTSPRO_RSVP_PLEASE_LOGIN');
		$data->message	= JText::_('COM_RSEVENTSPRO_RSVP_PLEASE_LOGIN');
		
		$query->clear()
			->select($db->qn('rsvp'))->select($db->qn('rsvp_quota'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.$db->q($id));
		$db->setQuery($query);
		$eventOptions = $db->loadObject();
		
		if (!$eventOptions->rsvp) {
			$data->canRSVP	= false;
		} else {
			if ($eventOptions->rsvp_quota) {
				$query->clear()
					->select('COUNT('.$db->qn('id').')')
					->from($db->qn('#__rseventspro_rsvp_users'))
					->where($db->qn('ide').' = '.$db->q($id))
					->where($db->qn('rsvp').' = '.$db->q('going'))
					->where($db->qn('uid').' <> '.$db->q($uid));
				$db->setQuery($query);
				$count = (int) $db->loadResult();
				
				if ($count >= $eventOptions->rsvp_quota) {
					$data->canRSVP = false;
					$data->offClass = ' hasTooltip disabled';
					$data->offTitle = JText::_('COM_RSEVENTSPRO_RSVP_OFF');
					$data->message	= JText::_('COM_RSEVENTSPRO_RSVP_OFF');
				} else {
					$data->canRSVP = $uid > 0;
				}
			} else {
				$data->canRSVP	= $uid > 0;
			}
		}
		
		$query->clear()
			->select($db->qn('rsvp'))
			->from($db->qn('#__rseventspro_rsvp_users'))
			->where($db->qn('ide').' = '.$db->q($id))
			->where($db->qn('uid').' = '.$db->q($uid));
		$db->setQuery($query);
		$data->rsvp = $db->loadResult();
		
		return $data;
	}
	
	public static function RSVPStatus($status) {
		if ($status == 'going') return JText::_('COM_RSEVENTSPRO_RSVP_GOING');
		if ($status == 'interested') return JText::_('COM_RSEVENTSPRO_RSVP_INTERESTED');
		if ($status == 'notgoing') return JText::_('COM_RSEVENTSPRO_RSVP_NOT_GOING');
		
		return '';
	}
	
	public static function exportRSVPCSV($query) {
		$db		= JFactory::getDbo();
		$id		= JFactory::getApplication()->input->getInt('id', 0);
		$csv	= '';
		
		if (!$id || !$query)
			return;
		
		$db->setQuery($query);
		$guests = $db->loadObjectList();
		
		if (rseventsproHelper::getConfig('export_headers')) {
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_RSVP_ID').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_RSVP_NAME').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_RSVP_EMAIL').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_RSVP_EXPORT_HEADER_DATE').'",';
			$csv .= '"'.JText::_('COM_RSEVENTSPRO_SUBSCRIBER_STATE').'"';
			$csv .= "\n";
		}
		
		if (!empty($guests)) {
			foreach ($guests as $guest) {				 
				$csv .= '"'.$db->escape($guest->id).'",';
				$csv .= '"'.$db->escape($guest->name).'",';
				$csv .= '"'.$db->escape($guest->email).'",';
				$csv .= '"'.$db->escape(rseventsproHelper::showdate($guest->date,'Y-m-d H:i:s')).'",';
				$csv .= '"'.$db->escape(rseventsproHelper::RSVPStatus($guest->rsvp)).'"';
				$csv .= "\n";
			}
		}
		
		$file = 'Event'.$id.'.csv';
		header("Content-type: text/csv; charset=UTF-8");
		header("Content-Disposition: attachment; filename=$file");
		echo rtrim($csv,"\n");
		JFactory::getApplication()->close();
	}
	
	public static function canRSVP($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$nowunix= JFactory::getDate()->toUnix();
		
		// Get the event
		$query->clear()
			->select($db->qn('rsvp'))->select($db->qn('rsvp_start'))->select($db->qn('rsvp_end'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.$db->q($id));
		
		$db->setQuery($query);
		$event = $db->loadObject();
		
		// Check for RSVP event
		if ($event->rsvp) {
			$show = true;
			if ($event->rsvp_start == $db->getNullDate()) $event->rsvp_start = '';
			if ($event->rsvp_end == $db->getNullDate()) $event->rsvp_end = '';
			
			if (empty($event->rsvp_start)) {
				$rsvp_start = false;
			} else {
				$rsvp_start = JFactory::getDate($event->rsvp_start)->toUnix();
			}
			
			if (empty($event->rsvp_end)) {
				$rsvp_end = false;
			} else {
				$rsvp_end = JFactory::getDate($event->rsvp_end)->toUnix();
			}
			
			if (!empty($rsvp_start) && !empty($rsvp_end)) {
				if ($rsvp_start <= $nowunix && $rsvp_end >= $nowunix || $rsvp_start >= $nowunix && $rsvp_end <= $nowunix) {
					$show = true;
				} else {
					$show = false;
				}
			} elseif (empty($rsvp_start) && !empty($rsvp_end)) {
				if ($rsvp_end >= $nowunix) {
					$show = true;
				} else {
					$show = false;
				}
			} elseif (!empty($rsvp_start) && empty($rsvp_end)) {
				if ($rsvp_start <= $nowunix) {
					$show = true;
				} else { 
					$show = false;
				}
			} elseif (empty($rsvp_start) && empty($rsvp_end)) {
				$show = true;
			}
			
			if (!$show) {
				return false;
			}
			
			return true;
		}
		
		return false;
	}
	
	public static function getEventInfo($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$data	= array();
		$cart	= false;
		$total	= 0;
		
		// Get event details
		$query->clear()
			->select($db->qn('registration'))->select($db->qn('rsvp'))->select($db->qn('hits'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.$db->q($id));
		$db->setQuery($query);
		$event = $db->loadObject();
		
		$data['event_views'] = $event->hits;
		
		if ($event->registration) {
			// Get ticket IDs for this specific event
			$query->clear()
				->select($db->qn('id'))
				->from($db->qn('#__rseventspro_tickets'))
				->where($db->qn('ide').' = '.$db->q($id));
			$db->setQuery($query);
			if ($tids = $db->loadColumn()) {
				$tids = array_map('intval', $tids);
				
				// Get most used payment method
				$query->clear()
					->select($db->qn('u.gateway'))
					->select('COUNT(*) AS num')
					->from($db->qn('#__rseventspro_users','u'))
					->where($db->qn('u.ide').' = '.(int) $id)
					->group($db->qn('u.gateway'))
					->order('num DESC')
					->order($db->qn('u.date').' DESC');
					
				JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query, 'rule' => 'u.ide')));				
				
				$db->setQuery($query);
				$data['gateway'] = rseventsproHelper::getPayment($db->loadResult());
				
				// Get total net sales
				JFactory::getApplication()->triggerEvent('rsepro_isCart', array(array('cart' => &$cart)));
				
				$query->clear()
					->select($db->qn('u.id'))
					->from($db->qn('#__rseventspro_users','u'))
					->where($db->qn('u.ide').' = '.(int) $id)
					->where($db->qn('u.state').' = '.$db->q(1));
				
				if ($cart) {
					$query->select($db->qn('c.total'));
				}
				
				JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query, 'rule' => 'u.ide')));
				
				$db->setQuery($query);
				if ($subscriptions = $db->loadObjectList()) {
					foreach ($subscriptions as $subscription) {
						if (isset($subscription->total)) {
							$total += $subscription->total;
						} else {
							$total += rseventsproHelper::total($subscription->id);
						}
					}
					
					$data['net_sales'] = rseventsproHelper::currency($total);
				}
				
				// Get total number of tickets purchased
				$query->clear()
					->select('SUM('.$db->qn('quantity').')')
					->from($db->qn('#__rseventspro_user_tickets'))
					->where($db->qn('idt').' IN ('.implode(',',$tids).')');
				$db->setQuery($query);
				$data['tickets_purchased'] = (int) $db->loadResult();
				
			} else {
				$query->clear()
					->select('SUM('.$db->qn('quantity').')')
					->from($db->qn('#__rseventspro_user_tickets'))
					->where($db->qn('idt').' = 0');
				$db->setQuery($query);
				$data['tickets_purchased'] = (int) $db->loadResult();
			}
			
			// Get total number of subscribers
			$query->clear()
				->select('COUNT('.$db->qn('u.id').')')
				->from($db->qn('#__rseventspro_users','u'))
				->where($db->qn('u.ide').' = '.(int) $id);
			
			JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query, 'rule' => 'u.ide')));
			
			$db->setQuery($query);
			$data['total_subscribers'] = (int) $db->loadResult();
			
			// Get total number of accepted subscribers
			$query->clear()
				->select('COUNT('.$db->qn('u.id').')')
				->from($db->qn('#__rseventspro_users','u'))
				->where($db->qn('u.state').' = 1')
				->where($db->qn('u.ide').' = '.(int) $id);
			
			JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query, 'rule' => 'u.ide')));
			
			$db->setQuery($query);
			$data['total_accepted'] = (int) $db->loadResult();
			
			// Get total number of pending subscribers
			$query->clear()
				->select('COUNT('.$db->qn('u.id').')')
				->from($db->qn('#__rseventspro_users','u'))
				->where($db->qn('u.state').' = 0')
				->where($db->qn('u.ide').' = '.(int) $id);
			
			JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query, 'rule' => 'u.ide')));
			
			$db->setQuery($query);
			$data['total_pending'] = (int) $db->loadResult();
			
			// Get total number of denied subscribers
			$query->clear()
				->select('COUNT('.$db->qn('u.id').')')
				->from($db->qn('#__rseventspro_users','u'))
				->where($db->qn('u.state').' = 2')
				->where($db->qn('u.ide').' = '.(int) $id);
			
			JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query, 'rule' => 'u.ide')));
			
			$db->setQuery($query);
			$data['total_denied'] = (int) $db->loadResult();
			
		} else if ($event->rsvp) {
			// Get total number of RSVP subscribers
			$query->clear()
				->select('COUNT('.$db->qn('id').')')
				->from($db->qn('#__rseventspro_rsvp_users'))
				->where($db->qn('ide').' = '.(int) $id);
			$db->setQuery($query);
			$data['total_rsvp'] = (int) $db->loadResult();
			
			// Get total number of Going subscribers
			$query->clear()
				->select('COUNT('.$db->qn('id').')')
				->from($db->qn('#__rseventspro_rsvp_users'))
				->where($db->qn('rsvp').' = '.$db->q('going'))
				->where($db->qn('ide').' = '.(int) $id);
			$db->setQuery($query);
			$data['total_rsvp_going'] = (int) $db->loadResult();
			
			// Get total number of Interested subscribers
			$query->clear()
				->select('COUNT('.$db->qn('id').')')
				->from($db->qn('#__rseventspro_rsvp_users'))
				->where($db->qn('rsvp').' = '.$db->q('interested'))
				->where($db->qn('ide').' = '.(int) $id);
			$db->setQuery($query);
			$data['total_rsvp_interested'] = (int) $db->loadResult();
			
			// Get total number of Not going subscribers
			$query->clear()
				->select('COUNT('.$db->qn('id').')')
				->from($db->qn('#__rseventspro_rsvp_users'))
				->where($db->qn('rsvp').' = '.$db->q('notgoing'))
				->where($db->qn('ide').' = '.(int) $id);
			$db->setQuery($query);
			$data['total_rsvp_notgoing'] = (int) $db->loadResult();
		}
		
		return $data;
	}
	
	public static function getTicketCount($ticket) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select('SUM('.$db->qn('quantity').')')
			->from($db->qn('#__rseventspro_user_tickets'))
			->where($db->qn('idt').' = '.$db->q($ticket->id));
		$db->setQuery($query);
		$quantity = (int) $db->loadResult();
		
		return $quantity.' / '.($ticket->seats ? $ticket->seats : JText::_('COM_RSEVENTSPRO_GLOBAL_UNLIMITED'));
	}
	
	public static function getTicketCountNoEntrance($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select('SUM('.$db->qn('ut.quantity').')')
			->from($db->qn('#__rseventspro_user_tickets', 'ut'))
			->join('LEFT',$db->qn('#__rseventspro_users', 'u').' ON '.$db->qn('ut.ids').' = '.$db->qn('u.id'))
			->where($db->qn('u.ide').' = '.(int) $id);
		
		JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query, 'rule' => 'u.ide')));
		
		$db->setQuery($query);
		return (int) $db->loadResult();
	}
	
	public static function getSubscribers($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select($db->qn('u.id'))->select($db->qn('u.name'))->select($db->qn('u.email'));
		$query->select($db->qn('u.gateway'))->select($db->qn('u.date'))->select($db->qn('u.state'));
		$query->from($db->qn('#__rseventspro_users','u'));
		$query->where($db->qn('u.ide').' = '.(int) $id);
		$query->order($db->qn('u.date').' DESC');
		
		JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query, 'rule' => 'u.ide')));
		
		$db->setQuery($query, 0, 5);
		return $db->loadObjectList();
	}
	
	public static function getRSVP($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select($db->qn('u.name'))->select($db->qn('u.email'));
		$query->select($db->qn('r.rsvp'))->select($db->qn('r.date'));
		$query->from($db->qn('#__rseventspro_rsvp_users','r'));
		$query->join('LEFT',$db->qn('#__users', 'u').' ON '.$db->qn('u.id').' = '.$db->qn('r.uid'));
		$query->where($db->qn('r.ide').' = '.(int) $id);
		$query->order($db->qn('r.date').' DESC');
		
		$db->setQuery($query, 0, 5);
		return $db->loadObjectList();
	}
	
	public static function getFilterText($value) {
		if ($value == 'events') return JText::_('COM_RSEVENTSPRO_FILTER_NAME');
		elseif ($value == 'description') return JText::_('COM_RSEVENTSPRO_FILTER_DESCRIPTION');
		elseif ($value == 'locations') return JText::_('COM_RSEVENTSPRO_FILTER_LOCATION');
		elseif ($value == 'categories') return JText::_('COM_RSEVENTSPRO_FILTER_CATEGORY');
		elseif ($value == 'tags') return JText::_('COM_RSEVENTSPRO_FILTER_TAG');
		elseif ($value == 'is') return JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_IS');
		elseif ($value == 'isnot') return JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_ISNOT');
		elseif ($value == 'contains') return JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_CONTAINS');
		elseif ($value == 'notcontain') return JText::_('COM_RSEVENTSPRO_FILTER_CONDITION_NOTCONTAINS');
		
		return '';
	}
	
	public static function getSpeakers($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->clear()
			->select('s.*')
			->from($db->qn('#__rseventspro_speakers','s'))
			->join('left',$db->qn('#__rseventspro_taxonomy','tx').' ON '.$db->qn('tx.id').' = '.$db->qn('s.id'))
			->where($db->qn('tx.type').' = '.$db->q('speaker'))
			->where($db->qn('tx.ide').' = '.$db->q($id))
			->where($db->qn('s.published').' = 1')
			->order($db->qn('s.name').' ASC');
		
		$db->setQuery($query);
		if ($speakers = $db->loadObjectList()) {
			foreach ($speakers as $speaker) {
				if ($speaker->url && substr($speaker->url,0,4) != 'http') {
					$speaker->url = 'http://'.$speaker->url;
				}
				
				if ($speaker->facebook && substr($speaker->facebook,0,4) != 'http') {
					$speaker->facebook = 'https://www.facebook.com/'.$speaker->facebook;
				}
				
				if ($speaker->twitter && substr($speaker->twitter,0,4) != 'http') {
					$speaker->twitter = 'https://www.twitter.com/'.$speaker->twitter;
				}
				
				if ($speaker->linkedin && substr($speaker->linkedin,0,4) != 'http') {
					$speaker->linkedin = 'https://www.linkedin.com/in/'.$speaker->linkedin;
				}
			}
			
			return $speakers;
		}
		
		return false;
	}
	
	public static function getCoupons($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select('COUNT('.$db->qn('u.id').') AS nr')->select($db->qn('u.coupon'))
			->from($db->qn('#__rseventspro_users','u'))
			->where($db->qn('u.coupon').' <> '.$db->q(''))
			->where($db->qn('u.ide').' = '.(int) $id)
			->group($db->qn('u.coupon'));
		
		JFactory::getApplication()->triggerEvent('rsepro_subscriptionsQuery', array(array('query' => &$query, 'rule' => 'u.ide')));
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	public static function timezoneModal() {
		$html		= array();
		$footer 	= array();
		$return		= base64_encode(JUri::getInstance());
		$timezone	= JFactory::getConfig()->get('offset');
		
		$footer[] = '<button class="btn btn-primary" type="button" onclick="document.timezoneForm.submit();">'.JText::_('COM_RSEVENTSPRO_GLOBAL_SAVE').'</button>';
		$footer[] = '<button class="btn" data-dismiss="modal" aria-hidden="true">'.JText::_('COM_RSEVENTSPRO_GLOBAL_CANCEL').'</button>';
		
		$html[] = '<form method="post" action="'.htmlentities(JUri::getInstance(), ENT_COMPAT, 'UTF-8').'" id="timezoneForm" name="timezoneForm" class="form-horizontal">';
		$html[] = '<div class="control-group">';
		$html[] = '<div class="control-label">';
		$html[] = '<label>'.JText::_('COM_RSEVENTSPRO_DEFAULT_TIMEZONE').'</label>';
		$html[] = '</div>';
		$html[] = '<div class="controls">';
		$html[] = '<span class="btn disabled">'.$timezone.'</span>';
		$html[] = '</div>';
		$html[] = '</div>';
		$html[] = '<div class="control-group">';
		$html[] = '<div class="control-label">';
		$html[] = '<label for="timezone">'.JText::_('COM_RSEVENTSPRO_SELECT_TIMEZONE').'</label>';
		$html[] = '</div>';
		$html[] = '<div class="controls">';
		$html[] = JHtml::_('rseventspro.timezones','timezone');
		$html[] = '</div>';
		$html[] = '</div>';
		$html[] = '<input type="hidden" name="task" value="timezone" />';
		$html[] = '<input type="hidden" name="return" value="'.$return.'" />';
		$html[] = '</form>';
		
		return JHtml::_('bootstrap.renderModal', 'timezoneModal', array('title' => JText::_('COM_RSEVENTSPRO_CHANGE_TIMEZONE'), 'bodyHeight' => 30, 'modalWidth' => 30, 'footer' => implode("\n", $footer)), implode("\n", $html));
	}
}