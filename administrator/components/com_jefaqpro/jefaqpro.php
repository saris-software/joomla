<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_jefaqpro')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include css files for style
$document	= JFactory::getDocument();
$path		= JURI::base()."components/com_jefaqpro/assets/css/style.css";
$document->addStyleSheet($path);

// For themes
JHtml::_('script','administrator/components/com_jefaqpro/assets/js/validate.js', false);

// Include dependancies
jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('jefaqpro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
?>