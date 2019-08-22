<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

// App
$app = JFactory::getApplication();

// ACL Check
$user = JFactory::getUser();
if (!$user->authorise('core.manage', 'com_rsfirewall')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once JPATH_COMPONENT.'/helpers/adapter.php';
require_once JPATH_COMPONENT.'/helpers/version.php';
require_once JPATH_COMPONENT.'/helpers/config.php';
require_once JPATH_COMPONENT.'/controller.php';

require_once JPATH_COMPONENT.'/helpers/html.php';

RSFirewallHtml::registerFunctions();
	
$controller	= JControllerLegacy::getInstance('RSFirewall');

$task = $app->input->get('task');

$controller->execute($task);
$controller->redirect();