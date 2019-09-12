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

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

use RegularLabs\Library\Conditions as RL_Conditions;
use RegularLabs\Library\Parameters as RL_Parameters;
use RegularLabs\Library\RegEx as RL_RegEx;

/*
 * ModuleHelper methods
 */

class PlgSystemAdvancedModuleHelper
{
	var $advanced_params = [];
	var $mirror_ids      = [];

	public function onRenderModule(&$module)
	{
		// Module already nulled
		if (is_null($module))
		{
			return false;
		}

		// Do nothing if is not frontend
		if (!JFactory::getApplication()->isSite())
		{
			return false;
		}

		// return true if module is empty (this will empty the content)
		if ($this->isEmpty($module))
		{
			return true;
		}


		return false;
	}

	public function isEmpty(&$module)
	{
		if (!isset($module->content))
		{
			return true;
		}

		$this->setAdvancedParams($module);

		// return false if module params are not found
		if (empty($module->advancedparams))
		{
			return false;
		}

		$params = $module->advancedparams;

		// return false if hideempty is off in module params
		if (empty($params) || !isset($params->hideempty) || !$params->hideempty)
		{
			return false;
		}

		$config = $this->getConfig();

		// return false if show_hideempty is off in main config
		if (!$config->show_hideempty)
		{
			return false;
		}

		$content = trim($module->content);

		// return true if module is empty
		if ($content == '')
		{
			return true;
		}

		// remove html and hidden whitespace
		$content = str_replace(chr(194) . chr(160), ' ', $content);
		$content = str_replace(['&nbsp;', '&#160;'], ' ', $content);
		// remove comment tags
		$content = RL_RegEx::replace('<\!--.*?-->', '', $content);
		// remove all closing tags
		$content = RL_RegEx::replace('</[^>]+>', '', $content);
		// remove tags to be ignored
		$tags   = 'p|div|span|strong|b|em|i|ul|font|br|h[0-9]|fieldset|label|ul|ol|li|table|thead|tbody|tfoot|tr|th|td|form';
		$search = '<(?:' . $tags . ')(?:\s[^>]*)?>';

		if (RL_RegEx::match($search, $content))
		{
			$content = RL_RegEx::replace($search, '', $content);
		}

		// return whether content is empty
		return (trim($content) == '');
	}


	public function onPrepareModuleList(&$modules)
	{
		// return if is not frontend
		if (!JFactory::getApplication()->isSite() || empty($modules))
		{
			return;
		}

		jimport('joomla.filesystem.file');

		// set params for all loaded modules first
		// and make it an associated array (array id = module id)
		$new_modules = [];

		foreach ($modules as $id => $module)
		{
			$this->setAdvancedParams($module);
			$new_modules[$module->id] = $module;
		}

		$modules = $new_modules;
		unset($new_modules);

		foreach ($modules as $id => $module)
		{
			if (empty($module->id))
			{
				continue;
			}

			$this->setAdvancedParams($module);

			if ($module->advancedparams === 0)
			{
				continue;
			}


			$module->reverse = 0;

			if (!isset($module->published))
			{
				$module->published = 0;
			}

			if ($module->published)
			{
				$this->setMirrorParams($module);
				$this->removeDisabledAssignments($module->advancedparams);

				$assignments = RL_Conditions::getConditionsFromParams($module->advancedparams);
				$pass        = RL_Conditions::pass($assignments, $module->advancedparams->match_method);

				if (!$pass)
				{
					$module->published = 0;
				}

				if ($module->reverse)
				{
					$module->published = $module->published ? 0 : 1;
				}
			}

			$modules[$id] = $module;
		}
	}

	private function setAdvancedParams(&$module)
	{
		if (empty($module->id))
		{
			return;
		}

		if (isset($module->advancedparams) && is_object($module->advancedparams))
		{
			return;
		}

		if (!isset($module->advancedparams))
		{
			$module->advancedparams = $this->getAdvancedParams($module);
		}

		$module->advancedparams = json_decode($module->advancedparams);

		if (is_null($module->advancedparams))
		{
			$module->advancedparams = (object) [];
		}

		if (
			!isset($module->advancedparams->assignto_menuitems)
			|| isset($module->advancedparams->assignto_urls_selection_sef)
			|| (
				!is_array($module->advancedparams->assignto_menuitems)
				&& strpos($module->advancedparams->assignto_menuitems, '|') !== false
			)
		)
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_advancedmodules/models/module.php';
			$model = new AdvancedModulesModelModule;

			$module->advancedparams = (object) $model->initAssignments($module->id, $module);
		}

		$xmlfile_assignments = JPATH_ADMINISTRATOR . '/components/com_advancedmodules/assignments.xml';

		$module->advancedparams = RL_Parameters::getInstance()->getParams($module->advancedparams, $xmlfile_assignments);
	}

	private function setMirrorParams(&$module)
	{
		$module->mirror_id = $this->getMirrorModuleId($module);

		if (empty($module->mirror_id))
		{
			return;
		}

		$parameters = RL_Parameters::getInstance();

		$mirror_id = $module->mirror_id < 0 ? $module->mirror_id * -1 : $module->mirror_id;

		$count = 0;
		while ($count++ < 10)
		{
			if (!$test_mirrorid = $this->getMirrorModuleIdById($mirror_id))
			{
				break;
			}

			$mirror_id = $test_mirrorid;
		}

		if (empty($mirror_id))
		{
			return;
		}

		$xmlfile_assignments = JPATH_ADMINISTRATOR . '/components/com_advancedmodules/assignments.xml';

		$module->reverse = $mirror_id < 0;

		if ($mirror_id == $module->id)
		{
			$empty         = (object) [];
			$mirror_params = $parameters->getParams($empty, $xmlfile_assignments);
		}
		else
		{
			if (isset($modules[$mirror_id]))
			{
				if (!isset($modules[$mirror_id]->advancedparams))
				{
					$modules[$mirror_id]->advancedparams = $this->getAdvancedParamsById($mirror_id);
					$modules[$mirror_id]->advancedparams = $parameters->getParams($modules[$mirror_id]->advancedparams, $xmlfile_assignments);
				}

				$mirror_params = $modules[$mirror_id]->advancedparams;
			}
			else
			{
				$mirror_params = $this->getAdvancedParamsById($mirror_id);
				$mirror_params = $parameters->getParams($mirror_params, $xmlfile_assignments);
			}
		}

		// Keep the advanced settings that shouldn't be mirrored
		$settings_to_keep = [
			'hideempty', 'color',
		];

		foreach ($settings_to_keep as $key)
		{
			if (!isset($module->advancedparams->{$key}))
			{
				continue;
			}

			$mirror_params->{$key} = $module->advancedparams->{$key};
		}

		$module->advancedparams = $mirror_params;
	}

	private function removeDisabledAssignments(&$params)
	{
		$config = $this->getConfig();

		if (!$config->show_assignto_homepage)
		{
			$params->assignto_homepage = 0;
		}
		if (!$config->show_assignto_usergrouplevels)
		{
			$params->assignto_usergrouplevels = 0;
		}
		if (!$config->show_assignto_date)
		{
			$params->assignto_date = 0;
		}
		if (!$config->show_assignto_languages)
		{
			$params->assignto_languages = 0;
		}
		if (!$config->show_assignto_templates)
		{
			$params->assignto_templates = 0;
		}
		if (!$config->show_assignto_urls)
		{
			$params->assignto_urls = 0;
		}
		if (!$config->show_assignto_devices)
		{
			$params->assignto_devices = 0;
		}
		if (!$config->show_assignto_os)
		{
			$params->assignto_os = 0;
		}
		if (!$config->show_assignto_browsers)
		{
			$params->assignto_browsers = 0;
		}
		if (!$config->show_assignto_components)
		{
			$params->assignto_components = 0;
		}
		if (!$config->show_assignto_tags)
		{
			$params->show_assignto_tags = 0;
		}
		if (!$config->show_assignto_content)
		{
			$params->assignto_contentpagetypes = 0;
			$params->assignto_cats             = 0;
			$params->assignto_articles         = 0;
		}
	}


	public function onCreateModuleQuery(&$query)
	{
		// return if is not frontend
		if (!JFactory::getApplication()->isSite())
		{
			return;
		}

		foreach ($query as $type => $strings)
		{
			foreach ($strings as $i => $string)
			{
				if ($type == 'select')
				{
					$query->{$type}[$i] = str_replace(', mm.menuid', '', $string);
				}
				else if (strpos($string, 'mm.') !== false || strpos($string, 'm.publish_') !== false)
				{
					unset($query->{$type}[$i]);
				}
			}
		}
		$query->select[] = 'am.params as advancedparams, 0 as menuid, m.publish_up, m.publish_down';
		$query->join[]   = '#__advancedmodules as am ON am.moduleid = m.id';
		$query->order    = ['m.ordering, m.id'];
	}

	private function getConfig()
	{
		static $instance;

		if (is_object($instance))
		{
			return $instance;
		}

		$instance = RL_Parameters::getInstance()->getComponentParams('advancedmodules');

		return $instance;
	}

	private function getMirrorModuleId($module)
	{
		if (isset($module->mirror_id))
		{
			return $module->mirror_id;
		}

		return $this->getMirrorModuleIdById($module->id);
	}

	private function getMirrorModuleIdById($id)
	{
		if (isset($this->mirror_ids[$id]))
		{
			return $this->mirror_ids[$id];
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.mirror_id')
			->from('#__advancedmodules AS a')
			->where('a.moduleid = ' . (int) $id);
		$db->setQuery($query);

		$this->mirror_ids[$id] = $db->loadResult();

		return $this->mirror_ids[$id];
	}

	private function getAdvancedParams($module)
	{
		if (empty($module->id))
		{
			return '{}';
		}

		if (isset($this->advanced_params[$module->id]))
		{
			return $this->advanced_params[$module->id];
		}

		if (isset($module->adv_params))
		{

			$this->advanced_params[$module->id] = $module->adv_params;

			return $this->advanced_params[$module->id];
		}

		return $this->getAdvancedParamsById($module->id);
	}

	private function getAdvancedParamsById($id = 0)
	{
		if (!$id)
		{
			return '{}';
		}

		if (isset($this->advanced_params[$id]))
		{
			return $this->advanced_params[$id];
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.params')
			->from('#__advancedmodules AS a')
			->where('a.moduleid = ' . (int) $id);
		$db->setQuery($query);

		$params = $db->loadResult();
		if (empty($params))
		{
			$params = '{}';
		}

		$this->advanced_params[$id] = $params;

		return $this->advanced_params[$id];
	}
}
