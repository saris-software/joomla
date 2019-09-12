<?php
/**
* @package RSForm!Pro
* @copyright (C) 2007-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class plgSystemRSFPPDFInstallerScript
{
	public function preflight($type, $parent)
    {
		if ($type == 'uninstall')
		{
			return true;
		}

		try
        {
            if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/rsform.php'))
            {
                throw new Exception('Please install the RSForm! Pro component before continuing.');
            }

            if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/assets.php'))
            {
                throw new Exception('Please upgrade RSForm! Pro to at least version 1.51.0 before continuing!');
            }

            $jversion = new JVersion();
            if (!$jversion->isCompatible('3.7.0'))
            {
                throw new Exception('Please upgrade to at least Joomla! 3.7.0 before continuing!');
            }

            // Check version matches, we need 2.0.13 due to changes in the component.
            require_once JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/version.php';
            $version = new RSFormProVersion;
            if (version_compare((string) $version, '2.0.13', '<'))
            {
                throw new Exception('Please upgrade RSForm! Pro to at least version 2.0.13 before continuing!');
            }
        }
        catch (Exception $e)
        {
            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            return false;
        }

        return true;
	}
	
	public function update($parent) {
		$this->copyFiles($parent);
		
		$db = JFactory::getDbo();
		$columns = $db->getTableColumns('#__rsform_pdfs');
		
		if (!isset($columns['useremail_userpass'])) {
			$db->setQuery("ALTER TABLE `#__rsform_pdfs` ADD `useremail_userpass` VARCHAR( 255 ) NOT NULL AFTER `useremail_layout`,".
						  "ADD `useremail_ownerpass` VARCHAR( 255 ) NOT NULL AFTER `useremail_userpass`,".
						  "ADD `adminemail_userpass` VARCHAR( 255 ) NOT NULL AFTER `adminemail_layout`,".
						  "ADD `adminemail_ownerpass` VARCHAR( 255 ) NOT NULL AFTER `adminemail_userpass`,".
						  "ADD `useremail_options` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'print,modify,copy,add' AFTER `useremail_ownerpass`,".
						  "ADD `adminemail_options` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'print,modify,copy,add' AFTER `adminemail_ownerpass`");
			$db->execute();
		}
		
		// Run our SQL file
		$source = $parent->getParent()->getPath('source');
		$this->runSQL($source, 'install');
	}
	
	public function install($parent) {
		$this->copyFiles($parent);
	}
	
	protected function copyFiles($parent) {
		$app = JFactory::getApplication();
		$installer = $parent->getParent();
		$src = $installer->getPath('source').'/admin';
		$dest = JPATH_ADMINISTRATOR.'/components/com_rsform';
		
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		
		if (!JFolder::copy($src, $dest, '', true)) {
			$app->enqueueMessage('Could not copy to '.str_replace(JPATH_SITE, '', $dest).', please make sure destination is writable!', 'error');
		}
		
		$files = array(
			'fireflysung.ttf',
			'fireflysung.ufm',
			'fireflysung.ufm.php'
		);
		
		$old = JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/pdf/dompdf6/lib/fonts/';
		$new = JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/pdf/dompdf8/lib/fonts/';
		
		foreach ($files as $file)
		{
			if (file_exists($old . $file))
			{
				if (!JFile::copy($old . $file, $new . $file))
				{
					$app->enqueueMessage('Could not copy ' . $file . ' to '.str_replace(JPATH_SITE, '', $new).', please make sure destination is writable!', 'error');
				}
			}
		}
	}
	
	protected function runSQL($source, $file) {
		$db 	= JFactory::getDbo();
		$driver = strtolower($db->name);
		if (strpos($driver, 'mysql') !== false) {
			$driver = 'mysql';
		}
		
		$sqlfile = $source.'/sql/'.$driver.'/'.$file.'.sql';
		
		if (file_exists($sqlfile)) {
			$buffer = file_get_contents($sqlfile);
			if ($buffer !== false) {
				$queries = JInstallerHelper::splitSql($buffer);
				foreach ($queries as $query) {
					$query = trim($query);
					if ($query != '' && $query{0} != '#') {
						$db->setQuery($query);
						if (!$db->execute()) {
							throw new Exception(JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
						}
					}
				}
			}
		}
	}
	
	public function postflight($type, $parent) {
		if ($type == 'uninstall') {
			return true;
		}

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

		<h3>RSForm! Pro PDF Plugin v2.0.1 Changelog</h3>
		<ul class="version-history">
			<li><span class="version-upgraded">Upg</span> Compatibility with RSForm! Pro 2.0.13</li>
		</ul>
		<a class="btn btn-primary btn-large" href="<?php echo JRoute::_('index.php?option=com_rsform&view=forms'); ?>">Manage Forms</a>
		<a class="btn" href="https://www.rsjoomla.com/support/documentation/rsform-pro/plugins-and-modules/rsform-pro-pdf-plugin.html" target="_blank">Read the documentation</a>
		<a class="btn" href="https://www.rsjoomla.com/support.html" target="_blank">Get Support!</a>
		<div style="clear: both;"></div>
		<?php
	}
}