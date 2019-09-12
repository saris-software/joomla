<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!JFactory::getUser()->authorise('core.manage', 'com_phocafavicon')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocafaviconrenderadmin.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocafavicon.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocafaviconico.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocafaviconcp.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocafaviconfileupload.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocafaviconutils.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'renderadminview.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'renderadminviews.php' );
jimport('joomla.application.component.controller');
$controller	= JControllerLegacy::getInstance('PhocaFaviconCp');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
?>