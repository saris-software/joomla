<?php
/**
 * @author Joomla! Extensions Store
 * @package JMAP::modules::mod_jmap
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Module for sitemap footer navigation
 *
 * @author Joomla! Extensions Store
 * @package JMAP::modules::mod_jmap
 * @since 3.0
 */
jimport('joomla.filesystem.file');

// Include the syndicate functions only once
if($params->get('height_auto', 1)) {
	require_once __DIR__ . '/helper.php';
	ModJMapHelper::jmapInjectAutoHeightScript();
}

$scroll = htmlspecialchars($params->get('scrolling'));
$width	= htmlspecialchars($params->get('width'));
if(stripos($width, 'px') === false && stripos($width, '%') === false) {
	$width .= 'px';
}
$height = htmlspecialchars($params->get('height'));
$height = preg_replace('/(%|px)/i', '', $height);

$onLoad = $params->get('height_auto', 1) ? 'onload="jmapIFrameAutoHeight(\'jmap_sitemap_nav_' . $module->id . '\')"' : '';
$dataset = (int)$params->get('dataset', null);
$dataset = $dataset ? '&amp;dataset=' . $dataset : ''; 
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

// Check for multilanguage
$app = JFactory::getApplication();
$currentLanguageQueryString = null;
$currentSefLanguage = null;
if ($app->isSite()) {
	$multilangEnabled = $app->getLanguageFilter();
	$currentSefLanguage = $multilangEnabled ?  JFactory::getLanguage()->getLocale() : null;
	if(is_array($currentSefLanguage)) {
		$partialSef = explode('_', $currentSefLanguage[2]);
		$sefLang = array_shift($partialSef);
		$currentLanguageQueryString = '&amp;lang=' . $sefLang;
		$currentSefLanguage = $sefLang . '/';
	}
}

// Standard routing, full raw query string
$targetIFrameUrl = JUri::base() . 'index.php?option=com_jmap&amp;view=sitemap&amp;tmpl=component&amp;jmap_module=' . $module->id . $dataset . $currentLanguageQueryString;

// Legacy routing /en, /de, etc
if($params->get('legacy_routing', 0)) {
	// Try to check for an active htaccess file
	$index = null;
	// Joomla 3.2+ support
	if(method_exists($app, 'get')) {
		if(!$app->get ( 'sef_rewrite' )) {
			$index = 'index.php/';
		}
	}
	$targetIFrameUrl = JUri::base() . $index . $currentSefLanguage . '?option=com_jmap&amp;view=sitemap&amp;tmpl=component&amp;jmap_module=' . $module->id . $dataset;
}

require JModuleHelper::getLayoutPath('mod_jmap', $params->get('layout', 'default'));