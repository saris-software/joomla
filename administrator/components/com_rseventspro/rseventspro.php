<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' ); 

// Check for access
if (!JFactory::getUser()->authorise('core.manage', 'com_rseventspro'))
	 throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
	
// Load files
require_once JPATH_SITE. '/components/com_rseventspro/helpers/adapter/adapter.php';
require_once JPATH_SITE. '/components/com_rseventspro/helpers/rseventspro.php';
require_once JPATH_COMPONENT.'/controller.php';

// Initialize main helper
rseventsproHelper::loadHelper();

$controller	= JControllerLegacy::getInstance('Rseventspro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();