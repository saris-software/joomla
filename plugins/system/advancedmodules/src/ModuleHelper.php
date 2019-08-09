<?php
/**
 * @package         Advanced Module Manager
 * @version         7.1.4
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2017 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\AdvancedModules;

defined('_JEXEC') or die;

use JFactory;
use JPluginHelper;
use PlgSystemAdvancedModuleHelper;
use RegularLabs\Library\Version as RL_Version;

class ModuleHelper
{
	static $use_legacy = false;

	public static function load()
	{
		if (!self::needsLegacy())
		{
			return;
		}

		self::$use_legacy = true;

		// No need to load the JModuleHelper again
		if (self::isLoaded())
		{
			return;
		}

		require_once __DIR__ . '/Helpers/modulehelper_legacy.php';
	}

	public static function registerEvents()
	{
		if (self::$use_legacy)
		{
			self::registerEventsLegacy();

			return;
		}

		require_once __DIR__ . '/Helpers/advancedmodulehelper.php';
		$class = new PlgSystemAdvancedModuleHelper;

		JFactory::getApplication()->registerEvent('onRenderModule', [$class, 'onRenderModule']);
		JFactory::getApplication()->registerEvent('onPrepareModuleList', [$class, 'onPrepareModuleList']);
	}

	public static function registerEventsLegacy()
	{
		require_once __DIR__ . '/Helpers/advancedmodulehelper_legacy.php';
		$class = new PlgSystemAdvancedModuleHelper;

		JFactory::getApplication()->registerEvent('onRenderModule', [$class, 'onRenderModule']);
		JFactory::getApplication()->registerEvent('onCreateModuleQuery', [$class, 'onCreateModuleQuery']);
		JFactory::getApplication()->registerEvent('onPrepareModuleList', [$class, 'onPrepareModuleList']);

		return;
	}

	private static function needsLegacy()
	{
		// Return true if old JModuleHelper will be loaded by one of the following extensions
		if (
			(JPluginHelper::isEnabled('system', 't3') && version_compare(RL_Version::getPluginVersion('t3'), '2.4.6', '<'))
			|| (JPluginHelper::isEnabled('system', 'helix') && version_compare(RL_Version::getPluginVersion('helix'), '2.1.9', '<'))
			|| (JPluginHelper::isEnabled('system', 'jsntplframework') && version_compare(RL_Version::getPluginVersion('jsntplframework'), '2.3.4', '<'))
			|| (JPluginHelper::isEnabled('system', 'magebridge') && version_compare(RL_Version::getPluginVersion('magebridge'), '1.9.5295', '<'))
			|| (JPluginHelper::isEnabled('system', 'metamod'))
		)
		{
			return true;
		}

		return false;
	}

	private static function isLoaded()
	{
		$classes = get_declared_classes();
		if (!in_array('JModuleHelper', $classes) && !in_array('jmodulehelper', $classes))
		{
			return false;
		}

		return true;
	}
}
