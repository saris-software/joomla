<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// no direct access
defined('_JEXEC') or die('Restricted Access');

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// Include dependancies
jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT.'/helpers/route.php';

// Include css files for style
$document	= JFactory::getDocument();
$path		= JURI::root()."components/com_jefaqpro/assets/css/style.css";
$document->addStyleSheet($path);

// For themes
JHtml::_('script','components/com_jefaqpro/assets/js/utilities.js');
JHtml::_('script','components/com_jefaqpro/assets/js/accordionview.js');
JHtml::_('script','components/com_jefaqpro/assets/js/validate.js');
JHtml::_('stylesheet','components/com_jefaqpro/assets/css/accordionview.css');

$controller	= JControllerLegacy::getInstance('jefaqpro');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();

?>