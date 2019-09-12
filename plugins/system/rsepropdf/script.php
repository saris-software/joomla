<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

class plgSystemRSEproPDFInstallerScript
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
		if (!$jversion->isCompatible('2.5.6')) {
			$app->enqueueMessage('Please upgrade to at least Joomla! 2.5.6 before continuing!', 'error');
			return false;
		}
		
		return true;
	}
	
	public function update($parent) {
		$this->copyFiles($parent);
	}
	
	public function install($parent) {
		$this->copyFiles($parent);
	}
	
	protected function copyFiles($parent) {
		$app = JFactory::getApplication();
		$installer = $parent->getParent();
		$src = $installer->getPath('source').'/site';
		$dest = JPATH_SITE.'/components/com_rseventspro';
		
		if (!JFolder::copy($src, $dest, '', true)) {
			$app->enqueueMessage('Could not copy to '.str_replace(JPATH_SITE, '', $dest).', please make sure destination is writable!', 'error');
		}
	}
}