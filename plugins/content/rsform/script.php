<?php
/**
* @package RSForm! Pro
* @copyright (C) 2007-2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

class plgContentRSFormInstallerScript
{
	public function preflight($type, $parent) {
		if ($type == 'uninstall') {
			return true;
		}
		
		$app = JFactory::getApplication();
		
		if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/rsform.php')) {
			$app->enqueueMessage('Please install the RSForm! Pro component before continuing.', 'error');
			return false;
		}
		
		if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/assets.php')) {
			$app->enqueueMessage('Please upgrade RSForm! Pro to at least version 1.51.0 before continuing!', 'error');
			return false;
		}
		
		return true;
	}
	
	public function postflight($type, $parent) {
		if ($type == 'uninstall') {
			return true;
		}
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('extension_id')
			  ->from($db->qn('#__extensions'))
			  ->where($db->qn('type').' = '.$db->q('plugin'))
			  ->where($db->qn('folder').' = '.$db->q('content'))
			  ->where($db->qn('element').' = '.$db->q('rsform'));
		$pluginId = $db->setQuery($query)->loadResult();
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

		<h3>RSForm! Pro Content Plugin v1.51.1 Changelog</h3>
		<ul class="version-history">
			<li><span class="version-fixed">Fix</span> Fields were not populated automatically if they contained a hyphen.</li>
		</ul>
		<?php if ($pluginId) { ?>
		<a class="btn btn-primary btn-large" href="<?php echo JRoute::_('index.php?option=com_plugins&task=plugin.edit&extension_id='.$pluginId); ?>">Start using the RSForm! Pro Content Plugin.</a>
		<?php } ?>
		<a class="btn" href="https://www.rsjoomla.com/support/documentation/rsform-pro/plugins-and-modules/content-plugin-plgcontent-display-the-form-in-an-article.html" target="_blank">Read the documentation</a>
		<a class="btn" href="https://www.rsjoomla.com/support.html" target="_blank">Get Support!</a>
		<div style="clear: both;"></div>
		<?php
	}
}