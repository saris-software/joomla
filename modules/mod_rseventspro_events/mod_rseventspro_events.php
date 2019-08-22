<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if (!file_exists(JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php') && !file_exists(JPATH_SITE.'/components/com_rseventspro/helpers/query.php')) return;

require_once JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php';
require_once JPATH_SITE.'/components/com_rseventspro/helpers/route.php';
require_once JPATH_SITE.'/modules/mod_rseventspro_events/helper.php';

$events = modRseventsProEvents::getEvents($params);
$itemid = $params->get('itemid');
$itemid = !empty($itemid) ? $itemid : RseventsproHelperRoute::getEventsItemid();
$suffix	= $params->get('moduleclass_sfx');
$links	= (int) $params->get('links',0);

JHtml::stylesheet('mod_rseventspro_events/style.css', array('relative' => true, 'version' => 'auto'));

require JModuleHelper::getLayoutPath('mod_rseventspro_events', $params->get('layout', 'default'));