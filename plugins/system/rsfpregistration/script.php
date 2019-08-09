<?php
/**
* @package RSForm!Pro
* @copyright (C) 2007-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class plgSystemRSFPRegistrationInstallerScript
{
	public function preflight($type, $parent) {
		if ($type == 'uninstall') {
			return true;
		}
		
		$app = JFactory::getApplication();
		
		$jversion = new JVersion();
		if (!$jversion->isCompatible('2.5.28')) {
			$app->enqueueMessage('Please upgrade to at least Joomla! 2.5.28 before continuing!', 'error');
			return false;
		}
		
		if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/rsform.php')) {
			$app->enqueueMessage('Please install the RSForm! Pro component before continuing.', 'error');
			return false;
		}
		
		if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/assets.php')) {
			$app->enqueueMessage('Please upgrade RSForm! Pro to at least version 1.51.0 before continuing!', 'error');
			return false;
		}
		
		require_once JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/version.php';
		$version = new RSFormProVersion();
		
		if (!isset($version->version)) {
			$app->enqueueMessage('Please upgrade RSForm! Pro to the latest version before continuing!', 'error');
			return false;
		}
		
		if (version_compare((string) $version->version, '1.50.12', '<')) {
			$app->enqueueMessage('You are using version '.(string) $version.'. Please upgrade RSForm! Pro to a newer version than 1.50.11 before continuing!', 'error');
			return false;
		}
		
		$source 	= $parent->getParent()->getPath('source');
		$db			= JFactory::getDbo();
		try {
			$columns = $db->getTableColumns('#__rsform_registration');
			
			if (!empty($columns)) {
				if (!isset($columns['groups'])) {
					$db->setQuery("ALTER TABLE `#__rsform_registration` ADD `groups` VARCHAR( 255 ) NOT NULL AFTER `reg_merge_vars`,".
								  "ADD `action` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `form_id`,".
								  "ADD `action_field` VARCHAR( 255 ) NOT NULL AFTER `action`,".
								  "ADD `defer_admin_email` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `cbactivation`,".
								  "ADD `user_activation_action` TINYINT( 1 ) NOT NULL AFTER `defer_admin_email` ,".
								  "ADD `admin_activation_action` TINYINT( 1 ) NOT NULL AFTER `user_activation_action` ,".
								  "ADD `user_activation_url` TEXT NOT NULL AFTER `admin_activation_action` ,".
								  "ADD `admin_activation_url` TEXT NOT NULL AFTER `user_activation_url` ,".
								  "ADD `user_activation_text` MEDIUMTEXT NOT NULL AFTER `admin_activation_url` ,".
								  "ADD `admin_activation_text` MEDIUMTEXT NOT NULL AFTER `user_activation_text`");
					$db->execute();
					
					$db->setQuery('ALTER TABLE `#__rsform_registration` CHANGE `reg_merge_vars` `vars` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
					$db->execute();
				}
				
				if (!isset($columns['itemid'])) {
					$db->setQuery('ALTER TABLE `#__rsform_registration` ADD `itemid` INT( 11 ) NOT NULL AFTER `form_id`');
					$db->execute();
				}
				
				if (!isset($columns['password_strength'])) {
					$db->setQuery('ALTER TABLE `#__rsform_registration` ADD `password_strength` TINYINT( 1 ) NOT NULL AFTER `admin_activation_text`');
					$db->execute();
				}
			}
		} catch (Exception $e) {
			// Table does not exist, it will get created afterwards
		}
		
		return true;
	}
	
	public function update($parent) {
		$this->copyFiles($parent);
		$this->runSQL($parent->getParent()->getPath('source'), 'install.sql');
	}
	
	protected function runSQL($source, $file) {
		$db 	= JFactory::getDbo();
		$driver = strtolower($db->name);
		if (strpos($driver, 'mysql') !== false) {
			$driver = 'mysql';
		} elseif ($driver == 'sqlsrv') {
			$driver = 'sqlazure';
		}
		
		$sqlfile = $source.'/sql/'.$driver.'/'.$file;
		
		if (file_exists($sqlfile)) {
			$buffer = file_get_contents($sqlfile);
			if ($buffer !== false) {
				$queries = JInstallerHelper::splitSql($buffer);
				foreach ($queries as $query) {
					$query = trim($query);
					if ($query != '' && $query{0} != '#') {
						$db->setQuery($query);
						if (!$db->execute()) {
							JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
						}
					}
				}
			}
		}
	}
	
	public function install($parent) {
		$this->copyFiles($parent);
	}
	
	protected function copyFiles($parent) {
		$app 		= JFactory::getApplication();
		$installer 	= $parent->getParent();
		
		// Copy admin files
		$src  = $installer->getPath('source').'/admin';
		$dest = JPATH_ADMINISTRATOR.'/components/com_rsform';
		if (!JFolder::copy($src, $dest, '', true)) {
			$app->enqueueMessage('Could not copy to '.str_replace(JPATH_SITE, '', $dest).', please make sure destination is writable!', 'error');
		}
		
		// Copy site files
		$src  = $installer->getPath('source').'/site';
		$dest = JPATH_SITE.'/components/com_rsform';
		if (!JFolder::copy($src, $dest, '', true)) {
			$app->enqueueMessage('Could not copy to '.str_replace(JPATH_SITE, '', $dest).', please make sure destination is writable!', 'error');
		}
	}
	
	public function postflight($type, $parent) {
		if ($type == 'uninstall') {
			return true;
		}
		
		if ($type == 'install') {
			// Enable plugin
			$db  = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->update($db->qn('#__extensions'))
				  ->set($db->qn('enabled') . ' = 1')
				  ->where($db->qn('element') . ' = ' . $db->q('rsfpregistration'))
				  ->where($db->qn('type') . ' = ' . $db->q('plugin'));
			$db->setQuery($query);
			$db->execute();
		}
		
		$jversion = new JVersion;
		?>
		<style type="text/css">
		.version-history {
			margin: 0 0 2em 0;
			padding: 0;
			list-style-type: none;
		}
		.version-history > li {
			margin: 0 0 0.5em 0;
			padding: 0 0 0 4em;
			text-align:left;
			font-weight:normal;
		}
		.version-new,
		.version-fixed,
		.version-upgraded {
			float: left;
			font-size: 0.8em;
			margin-left: -4.9em;
			width: 4.5em;
			color: white;
			text-align: center;
			font-weight: bold;
			text-transform: uppercase;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 4px;
		}

		.version-new {
			background: #7dc35b;
		}
		.version-fixed {
			background: #e9a130;
		}
		.version-upgraded {
			background: #61b3de;
		}
		</style>

		<h3>RSForm! Pro Joomla! User Registration Plugin v1.52.1 Changelog</h3>
		<ul class="version-history">
			<li><span class="version-fixed">Fix</span> Resolved an issue that could cause unwanted redirects.</li>
		</ul>
		<a class="btn btn-primary btn-large" href="<?php echo JRoute::_('index.php?option=com_rsform&view=forms'); ?>">Manage Forms</a>
		<a class="btn" href="https://www.rsjoomla.com/support/documentation/rsform-pro/plugins-and-modules/rsformpro-joomla-user-registration-plugin.html" target="_blank">Read the documentation</a>
		<a class="btn" href="https://www.rsjoomla.com/support.html" target="_blank">Get Support!</a>
		<div style="clear: both;"></div>
		<?php
	}
}