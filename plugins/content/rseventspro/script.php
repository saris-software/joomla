<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

class plgContentRSEventsproInstallerScript
{
	public function preflight($type, $parent) {
		if ($type == 'uninstall') {
			return true;
		}
		
		$app = JFactory::getApplication();
		
		if (!file_exists(JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php')) {
			$app->enqueueMessage('Please install the RSEvents!Pro component before continuing.', 'error');
			return false;
		}
		
		$jversion = new JVersion();
		if (!$jversion->isCompatible('3.6')) {
			$app->enqueueMessage('Please upgrade to at least Joomla! 3.6.x before continuing!', 'error');
			return false;
		}
		
		return true;
	}
}