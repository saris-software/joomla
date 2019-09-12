<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if (file_exists(JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php')) { 
	require_once JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php';
	require_once JPATH_SITE.'/components/com_rseventspro/helpers/route.php';
	require_once JPATH_SITE.'/modules/mod_rseventspro_map/helper.php';
	
	// Load jQuery
	rseventsproHelper::loadjQuery();
	
	$config = rseventsproHelper::getConfig();
	
	// Load scripts
	$document = JFactory::getDocument();
	$document->addScript('https://maps.google.com/maps/api/js?libraries=geometry&language='.JFactory::getLanguage()->getTag().($config->google_map_api ? '&key='.$config->google_map_api : ''));
	JHtml::script('com_rseventspro/jquery.map.js', array('relative' => true, 'version' => 'auto'));
	JHtml::stylesheet('mod_rseventspro_map/style.css', array('relative' => true, 'version' => 'auto'));
	JFactory::getLanguage()->load('com_rseventspro');

	$events	= modRseventsProMap::getEvents($params);
	$width 	= $params->get('width', '100%');
	$height = $params->get('height', '250px');
	
	if (strpos($width, '%') !== false) {
		$width = $width;
	} elseif (strpos($width, 'px') !== false) {
		$width = $width;
	} else {
		$width = (int) $width.'px';
	}
	
	if (strpos($height, '%') !== false) {
		$height = $height;
	} elseif (strpos($height, 'px') !== false) {
		$height = $height;
	} else {
		$height = (int) $height.'px';
	}
	
	// Get the Itemid
	$itemid = $params->get('itemid');
	$itemid = !empty($itemid) ? $itemid : RseventsproHelperRoute::getEventsItemid();
	
	require(JModuleHelper::getLayoutPath('mod_rseventspro_map'));	
}