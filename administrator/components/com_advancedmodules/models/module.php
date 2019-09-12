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

/**
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use RegularLabs\Library\Date as RL_Date;
use RegularLabs\Library\Parameters as RL_Parameters;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Library\StringHelper as RL_String;

/**
 * Module model.
 */
class AdvancedModulesModelModule extends JModelAdmin
{
	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.4
	 */
	public $typeAlias = 'com_modules.module';

	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_MODULES';

	/**
	 * @var    string  The help screen key for the module.
	 * @since  1.6
	 */
	protected $helpKey = 'JHELP_EXTENSIONS_MODULE_MANAGER_EDIT';

	/**
	 * @var    string  The help screen base URL for the module.
	 * @since  1.6
	 */
	protected $helpURL;

	/**
	 * Batch copy/move command. If set to false,
	 * the batch copy/move command is not supported
	 *
	 * @var string
	 */
	protected $batch_copymove = 'position_id';

	/**
	 * Allowed batch commands
	 *
	 * @var array
	 */
	protected $batch_commands = [
		'assetgroup_id' => 'batchAccess',
		'language_id'   => 'batchLanguage',
	];

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 */
	public function __construct($config = [])
	{
		$config = array_merge(
			array(
				'event_after_delete'  => 'onExtensionAfterDelete',
				'event_after_save'    => 'onExtensionAfterSave',
				'event_before_delete' => 'onExtensionBeforeDelete',
				'event_before_save'   => 'onExtensionBeforeSave',
				'events_map'          => array(
					'save'   => 'extension',
					'delete' => 'extension',
				),
			), $config
		);

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$pk = $app->input->getInt('id');

		if (!$pk)
		{
			if ($extensionId = (int) $app->getUserState('com_advancedmodules.add.module.extension_id'))
			{
				$this->setState('extension.id', $extensionId);
			}
		}

		$this->setState('module.id', $pk);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_advancedmodules');
		$this->setState('params', $params);

		$this->getConfig();
	}

	/**
	 * Method to perform batch operations on a set of modules.
	 *
	 * @param   array $commands An array of commands to perform.
	 * @param   array $pks      An array of item ids.
	 * @param   array $contexts An array of item contexts.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 */
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize user ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));

			return false;
		}

		$done = false;

		if (!empty($commands['position_id']))
		{
			$cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

			if ($cmd == 'm' && !$this->batchMove($commands['position_id'], $pks, $contexts))
			{
				return false;
			}

			if ($cmd == 'c')
			{
				$result = $this->batchCopy($commands['position_id'], $pks, $contexts);
				if (!is_array($result))
				{
					return false;
				}

				$pks = $result;
			}

			$done = true;
		}

		if (!empty($commands['assetgroup_id']))
		{
			if (!$this->batchAccess($commands['assetgroup_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!empty($commands['language_id']))
		{
			if (!$this->batchLanguage($commands['language_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!$done)
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));

			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch language changes for a group of rows.
	 *
	 * @param   string $value    The new value matching a language.
	 * @param   array  $pks      An array of row IDs.
	 * @param   array  $contexts An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 */
	protected function batchLanguage($value, $pks, $contexts)
	{
		// Set the variables
		$user      = JFactory::getUser();
		$db        = $this->getDbo();
		$table     = $this->getTable();
		$table_adv = JTable::getInstance('AdvancedModules', 'AdvancedModulesTable');

		foreach ($pks as $pk)
		{
			if (!$user->authorise('core.edit', $contexts[$pk]))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}

			$table->reset();
			$table->load($pk);
			$table->language = $value;

			if (!$table->store())
			{
				$this->setError($table->getError());

				return false;
			}

			if ($table->id && !$table_adv->load($table->id))
			{
				$table_adv->moduleid = $table->id;
				$db->insertObject($table_adv->getTableName(), $table_adv, $table_adv->getKeyName());
			}

			if ($table_adv->load($pk, true))
			{
				$table_adv->moduleid = $table->id;

				$params = json_decode($table_adv->params);
				if (is_null($params))
				{
					$params = (object) [];
				}

				$params->assignto_languages           = 0;
				$params->assignto_languages_selection = [];
				if ($value != '*')
				{
					$params->assignto_languages           = 1;
					$params->assignto_languages_selection = [$value];
				}

				$table_adv->params = json_encode($params);

				if (!$table_adv->check() || !$table_adv->store())
				{
					$this->setError($table_adv->getError());

					return false;
				}
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch copy modules to a new position or current.
	 *
	 * @param   integer $value    The new value matching a module position.
	 * @param   array   $pks      An array of row IDs.
	 * @param   array   $contexts An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 */
	protected function batchCopy($value, $pks, $contexts)
	{
		// Set the variables
		$user      = JFactory::getUser();
		$db        = $this->getDbo();
		$query     = $db->getQuery(true);
		$table     = $this->getTable();
		$table_adv = JTable::getInstance('AdvancedModules', 'AdvancedModulesTable');
		$newIds    = [];

		foreach ($pks as $pk)
		{
			if (!$user->authorise('core.create', 'com_modules'))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));

				return false;
			}

			$table->reset();
			$table->load($pk);

			// Set the new position
			switch ($value)
			{
				case 'noposition':
					$position = '';
					break;

				case 'nochange':
					$position = $table->position;
					break;

				default:
					$position = $value;
					break;
			}

			$table->position = $position;

			// Alter the title if necessary
			$data         = $this->generateNewTitle(0, $table->title, $table->position);
			$table->title = $data['0'];

			// Reset the ID because we are making a copy
			$table->id = 0;

			// Unpublish the new module
			$table->published = 0;

			if (!$table->store())
			{
				$this->setError($table->getError());

				return false;
			}

			// Get the new item ID
			$newId = (int) $table->get('id');

			// Add the new ID to the array
			$newIds[$pk] = $newId;

			// Now we need to handle the module assignments
			$query->clear()
				->select('m.menuid')
				->from('#__modules_menu as m')
				->where('m.moduleid = ' . (int) $pk);
			$db->setQuery($query);
			$menus = $db->loadColumn();

			// Insert the new records into the table
			foreach ($menus as $menu)
			{
				$query->clear()
					->insert('#__modules_menu')
					->columns(array($db->quoteName('moduleid'), $db->quoteName('menuid')))
					->values($newId . ', ' . $menu);
				$db->setQuery($query);
				try
				{
					$db->execute();
				}
				catch (RuntimeException $e)
				{
					return JError::raiseWarning(500, $e->getMessage());
				}
			}

			if ($table->id && !$table_adv->load($table->id))
			{
				$table_adv->moduleid = $table->id;
				$db->insertObject($table_adv->getTableName(), $table_adv, $table_adv->getKeyName());
			}

			if (!$table_adv->load($pk, true))
			{
				continue;
			}

			$table_adv->moduleid = $table->id;

			$rules = JAccess::getAssetRules('com_modules.module.' . $pk);
			$table_adv->setRules($rules);

			if (!$table_adv->check() || !$table_adv->store())
			{
				$this->setError($table_adv->getError());

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return $newIds;
	}

	/**
	 * Batch move modules to a new position or current.
	 *
	 * @param   integer $value    The new value matching a module position.
	 * @param   array   $pks      An array of row IDs.
	 * @param   array   $contexts An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 */
	protected function batchMove($value, $pks, $contexts)
	{
		// Set the variables
		$user  = JFactory::getUser();
		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			if (!$user->authorise('core.edit', 'com_modules'))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}

			$table->reset();
			$table->load($pk);

			// Set the new position
			switch ($value)
			{
				case 'noposition':
					$position = '';
					break;

				case 'nochange':
					$position = $table->position;
					break;

				default:
					$position = $value;
					break;
			}

			$table->position = $position;

			if (!$table->store())
			{
				$this->setError($table->getError());

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object $record A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing module.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_modules.module.' . (int) $record->id);
		}

		// Default to component settings if module not known.
		return parent::canEditState('com_modules');
	}

	/**
	 * Method to delete rows.
	 *
	 * @param   array &$pks An array of item ids.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 * @throws  Exception
	 */
	public function delete(&$pks)
	{
		$dispatcher = JEventDispatcher::getInstance();
		$pks        = (array) $pks;
		$user       = JFactory::getUser();
		$table      = $this->getTable();
		$db         = $this->getDbo();
		$query      = $db->getQuery(true);
		$context    = $this->option . '.' . $this->name;

		// Include the plugins for the on delete events.
		JPluginHelper::importPlugin($this->events_map['delete']);

		// Iterate the items to delete each one.
		foreach ($pks as $pk)
		{
			if (!$table->load($pk))
			{
				throw new Exception($table->getError());
			}

			// Access checks.
			if (!$user->authorise('core.delete', 'com_modules.module.' . (int) $pk) || $table->published != -2)
			{
				JError::raiseWarning(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));

				return;
			}

			// Trigger the before delete event.
			$result = $dispatcher->trigger($this->event_before_delete, [$context, $table]);

			if (in_array(false, $result, true) || !$table->delete($pk))
			{
				throw new Exception($table->getError());
			}

			// Delete the menu assignments
			$query->clear()
				->delete('#__modules_menu')
				->where('moduleid=' . (int) $pk);
			$db->setQuery($query);
			$db->execute();

			$query->clear()
				->delete('#__advancedmodules')
				->where('moduleid=' . (int) $pk);
			$db->setQuery($query);
			$db->execute();

			// delete asset
			$query->clear()
				->delete('#__assets')
				->where('name = ' . $db->quote('com_modules.module.' . (int) $pk));
			$db->setQuery($query);
			$db->execute();

			$query->clear()
				->delete('#__assets')
				->where('name = ' . $db->quote('com_advancedmodules.module.' . (int) $pk));
			$db->setQuery($query);
			$db->execute();

			// Trigger the after delete event.
			$dispatcher->trigger($this->event_after_delete, [$context, $table]);

			// Clear module cache
			parent::cleanCache($table->module, $table->client_id);
		}

		// Clear modules cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to duplicate modules.
	 *
	 * @param   array &$pks An array of primary key IDs.
	 *
	 * @return  boolean|JException  Boolean true on success, JException instance on error
	 *
	 * @throws  Exception
	 */
	public function duplicate(&$pks)
	{
		$user = JFactory::getUser();

		// Access checks.
		if (!$user->authorise('core.create', 'com_modules'))
		{
			throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
		}

		$db        = $this->getDbo();
		$query     = $db->getQuery(true);
		$inserts   = [];
		$table     = $this->getTable();
		$table_adv = JTable::getInstance('AdvancedModules', 'AdvancedModulesTable');

		foreach ($pks as $pk)
		{
			if ($table->load($pk, true))
			{
				// Reset the id to create a new record.
				$table->id = 0;

				// Alter the title.
				$m = null;

				if (RL_RegEx::match('\((\d+)\)$', $table->title, $m))
				{
					$table->title = RL_RegEx::replace('\(\d+\)$', '(' . ($m[1] + 1) . ')', $table->title);
				}

				$data         = $this->generateNewTitle(0, $table->title, $table->position);
				$table->title = $data[0];

				// Unpublish duplicate module
				$table->published = 0;

				if (!$table->check() || !$table->store())
				{
					throw new Exception($table->getError());
				}

				$query->clear()
					->select($db->quoteName('menuid'))
					->from($db->quoteName('#__modules_menu'))
					->where($db->quoteName('moduleid') . ' = ' . (int) $pk);

				$db->setQuery($query);
				$rows = $db->loadColumn();

				foreach ($rows as $menuid)
				{
					$inserts[(int) $table->id . '-' . (int) $menuid] = (int) $table->id . ',' . (int) $menuid;
				}

				if ($table->id && !$table_adv->load($table->id))
				{
					$table_adv->moduleid = $table->id;
					$db->insertObject($table_adv->getTableName(), $table_adv, $table_adv->getKeyName());
				}

				if ($table_adv->load($pk, true))
				{
					$table_adv->moduleid = $table->id;

					$rules = JAccess::getAssetRules('com_modules.module.' . $pk);
					$table_adv->setRules($rules);

					if (!$table_adv->check() || !$table_adv->store())
					{
						throw new Exception($table_adv->getError());
					}
				}
			}
			else
			{
				throw new Exception($table->getError());
			}
		}

		if (!empty($inserts))
		{
			// Module-Menu Mapping: Do it in one query
			$query->clear()
				->insert('#__modules_menu')
				->columns(array($db->quoteName('moduleid'), $db->quoteName('menuid')));
			foreach ($inserts as $insert)
			{
				$query->values($insert);
			}
			$db->setQuery($query);
			try
			{
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				return JError::raiseWarning(500, $e->getMessage());
			}
		}

		// Clear modules cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to set color of modules.
	 *
	 * @param   array  &$pks  An array of primary key IDs.
	 * @param   string $color RGB color
	 *
	 * @return  boolean  True if successful.
	 * @throws  Exception
	 */
	public function setcolor(&$pks, $color)
	{
		// Set the variables
		$db        = $this->getDbo();
		$user      = JFactory::getUser();
		$table_adv = JTable::getInstance('AdvancedModules', 'AdvancedModulesTable');

		foreach ($pks as $pk)
		{
			if (!$user->authorise('core.edit', 'com_modules'))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}

			if (!$table_adv->load($pk))
			{
				$table_adv->moduleid = $pk;
				$db->insertObject($table_adv->getTableName(), $table_adv, $table_adv->getKeyName());
			}

			if (!$table_adv->load($pk, true))
			{
				continue;
			}

			$params = json_decode($table_adv->params);
			if (is_null($params))
			{
				$params = (object) [];
			}

			$params->color = strtolower($color);

			$table_adv->params = json_encode($params);

			if (!$table_adv->check() || !$table_adv->store())
			{
				$this->setError($table_adv->getError());

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to change the title.
	 *
	 * @param   integer $category_id The id of the category. Not used here.
	 * @param   string  $title       The title.
	 * @param   string  $position    The position.
	 *
	 * @return  array  Contains the modified title.
	 */
	protected function generateNewTitle($category_id, $title, $position)
	{
		// Alter the title & alias
		$table = $this->getTable();

		while ($table->load(['position' => $position, 'title' => $title]))
		{
			$title = RL_String::increment($title);
		}

		return [$title];
	}

	/**
	 * Method to get the client object
	 *
	 * @return  void
	 */
	public function &getClient()
	{
		return $this->_client;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array   $data     Data for the form.
	 * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 */
	public function getForm($data = [], $loadData = true)
	{
		// The folder and element vars are passed when saving the form.
		if (empty($data))
		{
			$item     = $this->getItem();
			$clientId = $item->client_id;
			$module   = $item->module;
			$id       = $item->id;
		}
		else
		{
			$clientId = JArrayHelper::getValue($data, 'client_id');
			$module   = JArrayHelper::getValue($data, 'module');
			$id       = JArrayHelper::getValue($data, 'id');
		}

		// Add the default fields directory
		$baseFolder = ($clientId) ? JPATH_ADMINISTRATOR : JPATH_SITE;
		JForm::addFieldPath($baseFolder . '/modules' . '/' . $module . '/field');

		// These variables are used to add data from the plugin XML files.
		$this->setState('item.client_id', $clientId);
		$this->setState('item.module', $module);

		// Get the form.
		$form = $this->loadForm('com_advancedmodules.module', 'module', ['control' => 'jform', 'load_data' => $loadData]);

		if (empty($form))
		{
			return false;
		}

		$form->setFieldAttribute('position', 'client', $this->getState('item.client_id') == 0 ? 'site' : 'administrator');

		$user = JFactory::getUser();

		/**
		 * Check for existing module
		 * Modify the form based on Edit State access controls.
		 */
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_modules.module.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_modules'))
		)
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 */
	protected function loadFormData()
	{
		$app = JFactory::getApplication();

		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_advancedmodules.edit.module.data', []);

		if (empty($data))
		{
			$data = $this->getItem();

			// Pre-select some filters (Status, Module Position, Language, Access Level) in edit form if those have been selected in Module Manager
			if (!$data->id)
			{
				$filters = (array) $app->getUserState('com_advancedmodules.modules.filter');
				$data->set('published', $app->input->getInt('published', ((isset($filters['state']) && $filters['state'] !== '') ? $filters['state'] : null)));
				$data->set('position', $app->input->getInt('position', (!empty($filters['position']) ? $filters['position'] : null)));
				$data->set('language', $app->input->getString('language', (!empty($filters['language']) ? $filters['language'] : null)));
				$data->set('access', $app->input->getInt('access', (!empty($filters['access']) ? $filters['access'] : JFactory::getConfig()->get('access'))));
			}

			// Avoid to delete params of a second module opened in a new browser tab while new one is not saved yet.
			if (empty($data->params))
			{
				// This allows us to inject parameter settings into a new module.
				$params = $app->getUserState('com_advancedmodules.add.module.params');

				if (is_array($params))
				{
					$data->set('params', $params);
				}
			}
		}

		if (!empty($data->mirror_id))
		{
			$data->advancedparams['mirror_module']   = ($data->mirror_id < 0) ? 2 : 1;
			$data->advancedparams['mirror_moduleid'] = ($data->mirror_id < 0) ? $data->mirror_id * -1 : $data->mirror_id;
		}

		$this->preprocessData('com_advancedmodules.module', $data);

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer $pk The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$pk    = (!empty($pk)) ? (int) $pk : (int) $this->getState('module.id');
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		if (isset($this->_cache[$pk]))
		{
			return $this->_cache[$pk];
		}

		// Get a row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		$return = $table->load($pk);

		// Check for a table object error.
		if ($return === false && $error = $table->getError())
		{
			$this->setError($error);

			return false;
		}

		// Check if we are creating a new extension.
		if (empty($pk))
		{
			$extensionId = (int) $this->getState('extension.id');
			if (!$extensionId)
			{
				$app = JFactory::getApplication();
				$app->redirect(JRoute::_('index.php?option=com_advancedmodules&view=modules', false));

				return false;
			}

			$query->clear()
				->select('e.element, e.client_id')
				->from('#__extensions as e')
				->where('e.extension_id = ' . $extensionId)
				->where('e.type = ' . $db->quote('module'));
			$db->setQuery($query);

			try
			{
				$extension = $db->loadObject();
			}
			catch (RuntimeException $e)
			{
				$this->setError($e->getMessage());

				return false;
			}

			if (empty($extension))
			{
				$this->setError('COM_MODULES_ERROR_CANNOT_FIND_MODULE');

				return false;
			}

			// Extension found, prime some module values.
			$table->module    = $extension->element;
			$table->client_id = $extension->client_id;
		}

		// Convert to the JObject before adding other data.
		$properties        = $table->getProperties(1);
		$this->_cache[$pk] = JArrayHelper::toObject($properties, 'JObject');

		// Convert the params field to an array.
		$this->_cache[$pk]->params = json_decode($table->params, true);
		if (is_null($this->_cache[$pk]->params))
		{
			$this->_cache[$pk]->params = [];
		}

		// Advanced parameters
		// Get a row instance.
		$table_adv = $this->getTable('AdvancedModules', 'AdvancedModulesTable');

		// Attempt to load the row.
		$table_adv->load($pk);

		$this->_cache[$pk]->asset_id  = $table_adv->asset_id;
		$this->_cache[$pk]->mirror_id = $table_adv->mirror_id;

		// Convert the params field to an array.
		$this->_cache[$pk]->advancedparams = json_decode($table_adv->params, true);
		if (is_null($this->_cache[$pk]->params))
		{
			$this->_cache[$pk]->advancedparams = [];
		}

		$this->_cache[$pk]->advancedparams = $this->initAssignments($pk, $this->_cache[$pk]);

		$assigned   = [];
		$assignment = 0;
		if (isset($this->_cache[$pk]->advancedparams['assignto_menuitems']) && isset($this->_cache[$pk]->advancedparams['assignto_menuitems_selection']))
		{
			$assigned = $this->_cache[$pk]->advancedparams['assignto_menuitems_selection'];
			if ($this->_cache[$pk]->advancedparams['assignto_menuitems'] == 1 && empty($this->_cache[$pk]->advancedparams['assignto_menuitems_selection']))
			{
				$assignment = '-';
			}
			else if ($this->_cache[$pk]->advancedparams['assignto_menuitems'] == 1)
			{
				$assignment = '1';
			}
			else if ($this->_cache[$pk]->advancedparams['assignto_menuitems'] == 2)
			{
				$assignment = '-1';
			}
		}

		$this->_cache[$pk]->assigned   = $assigned;
		$this->_cache[$pk]->assignment = $assignment;

		// Get the module XML.
		$client = JApplicationHelper::getClientInfo($table->client_id);
		$path   = JPath::clean($client->path . '/modules/' . $table->module . '/' . $table->module . '.xml');

		$this->_cache[$pk]->xml = null;
		if (file_exists($path))
		{
			$this->_cache[$pk]->xml = simplexml_load_file($path);
		}

		return $this->_cache[$pk];
	}

	/**
	 * Get the necessary data to load an item help screen.
	 *
	 * @return  object  An object with key, url, and local properties for loading the item help screen.
	 */
	public function getHelp()
	{
		return (object) ['key' => $this->helpKey, 'url' => $this->helpURL];
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string $type   The table type to instantiate
	 * @param   string $prefix A prefix for the table class name. Optional.
	 * @param   array  $config Configuration array for model. Optional.
	 *
	 * @return  JTable  A database object
	 */
	public function getTable($type = 'Module', $prefix = 'JTable', $config = [])
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   JTable $table The database object
	 *
	 * @return  void
	 */
	protected function prepareTable($table)
	{
		$table->title    = htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->position = trim($table->position);
	}

	/**
	 * Method to preprocess the form
	 *
	 * @param   JForm  $form  A form object.
	 * @param   mixed  $data  The data expected for the form.
	 * @param   string $group The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 * @throws  Exception if there is an error loading the form.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		jimport('joomla.filesystem.path');

		$lang     = JFactory::getLanguage();
		$clientId = $this->getState('item.client_id');
		$module   = $this->getState('item.module');

		$client   = JApplicationHelper::getClientInfo($clientId);
		$formFile = JPath::clean($client->path . '/modules/' . $module . '/' . $module . '.xml');

		// Load the core and/or local language file(s).
		$lang->load($module, $client->path, null, false, true)
		|| $lang->load($module, $client->path . '/modules/' . $module, null, false, true);

		if (file_exists($formFile))
		{
			// Get the module form.
			if (!$form->loadFile($formFile, false, '//config'))
			{
				throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));
			}

			// Attempt to load the xml file.
			if (!$xml = simplexml_load_file($formFile))
			{
				throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));
			}

			// Get the help data from the XML file if present.
			$help = $xml->xpath('/extension/help');

			if (!empty($help))
			{
				$helpKey = trim((string) $help[0]['key']);
				$helpURL = trim((string) $help[0]['url']);

				$this->helpKey = $helpKey ? $helpKey : $this->helpKey;
				$this->helpURL = $helpURL ? $helpURL : $this->helpURL;
			}
		}

		// Load the default advanced params
		JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/com_advancedmodules/models/forms');
		$form->loadFile('advanced', false);

		// Trigger the default form events.
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Loads ContentHelper for filters before validating data.
	 *
	 * @param   object $form  The form to validate against.
	 * @param   array  $data  The data to validate.
	 * @param   string $group The name of the group(defaults to null).
	 *
	 * @return  mixed  Array of filtered data if valid, false otherwise.
	 */
	public function validate($form, $data, $group = null)
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_content/helpers/content.php';

		return parent::validate($form, $data, $group);
	}

	/**
	 * Applies the text filters to arbitrary text as per settings for current user groups
	 *
	 * @param   text $text The string to filter
	 *
	 * @return  string  The filtered string
	 */
	public static function filterText($text)
	{
		return JComponentHelper::filterText($text);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array $data The form data.
	 *
	 * @return  boolean  True on success.
	 */
	public function save($data)
	{
		$advancedparams = JFactory::getApplication()->input->get('advancedparams', [], 'array');

		$dispatcher = JEventDispatcher::getInstance();
		$input      = JFactory::getApplication()->input;
		$table      = $this->getTable();
		$pk         = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('module.id');
		$isNew      = true;
		$context    = $this->option . '.' . $this->name;

		// Include the plugins for the save event.
		JPluginHelper::importPlugin($this->events_map['save']);

		// Load the row if saving an existing record.
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}

		// Alter the title and published state for Save as Copy
		if ($input->get('task') == 'save2copy')
		{
			$orig_table = clone $this->getTable();
			$orig_table->load((int) $input->getInt('id'));
			$data['published'] = 0;

			if ($data['title'] == $orig_table->title)
			{
				$data['title'] .= ' ' . JText::_('JGLOBAL_COPY');
			}
		}

		// correct the publish date details
		if (isset($advancedparams['assignto_date_publish_up']))
		{
			RL_Date::applyTimezone($advancedparams['assignto_date_publish_up']);
		}

		if (isset($advancedparams['assignto_date_publish_down']))
		{
			RL_Date::applyTimezone($advancedparams['assignto_date_publish_down']);
		}

		if (isset($advancedparams['assignto_date']))
		{
			$publish_up   = 0;
			$publish_down = 0;
			if ($advancedparams['assignto_date'] == 2)
			{
				$publish_up = $advancedparams['assignto_date_publish_down'];
			}
			else if ($advancedparams['assignto_date'] == 1)
			{
				$publish_up   = $advancedparams['assignto_date_publish_up'];
				$publish_down = $advancedparams['assignto_date_publish_down'];
			}

			$data['publish_up']   = $publish_up;
			$data['publish_down'] = $publish_down;
		}

		$lang = '*';
		if (isset($advancedparams['assignto_languages'])
			&& $advancedparams['assignto_languages'] == 1
			&& count($advancedparams['assignto_languages_selection']) === 1
		)
		{
			$lang = (string) $advancedparams['assignto_languages_selection']['0'];
		}
		$data['language'] = $lang;

		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());

			return false;
		}

		// Prepare the row for saving
		$this->prepareTable($table);

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());

			return false;
		}

		// Trigger the before save event.
		$result = $dispatcher->trigger($this->event_before_save, [$context, &$table, $isNew]);

		if (in_array(false, $result, true))
		{
			$this->setError($table->getError());

			return false;
		}

		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());

			return false;
		}

		$table_adv = JTable::getInstance('AdvancedModules', 'AdvancedModulesTable');

		$table_adv->moduleid = $table->id;
		if ($table_adv->moduleid && !$table_adv->load($table_adv->moduleid))
		{
			$db = $table_adv->getDbo();
			$db->insertObject($table_adv->getTableName(), $table_adv, $table_adv->getKeyName());
		}

		if (isset($data['rules']))
		{
			$table_adv->_title = $data['title'];
			$table_adv->setRules($data['rules']);
		}

		$table_adv->mirror_id = 0;

		if (!empty($advancedparams['mirror_module']))
		{
			$table_adv->mirror_id = $advancedparams['mirror_module'] == 2 ? $advancedparams['mirror_moduleid'] * -1 : $advancedparams['mirror_moduleid'];
			unset($advancedparams['mirror_module']);
			unset($advancedparams['mirror_moduleid']);
		}

		$table_adv->params = json_encode($advancedparams);

		// Check the row
		$table_adv->check();

		// Store the row
		if (!$table_adv->store())
		{
			$this->setError($table_adv->getError());
		}

		//
		// Process the menu link mappings.
		//
		if (!$this->saveMenuAssignments($table->id, $advancedparams))
		{
			return false;
		}

		$db = $this->getDbo();

		// Remove unused assets entry (uses core com_modules rules)
		$query = $db->getQuery(true)
			->delete('#__assets')
			->where('name = ' . $db->quote('com_advancedmodules.module.' . (int) $table->id));
		$db->setQuery($query);
		$db->execute();

		// Trigger the after save event.
		$dispatcher->trigger($this->event_after_save, [$context, &$table, $isNew]);

		// Compute the extension id of this module in case the controller wants it.
		$query = $db->getQuery(true)
			->select('extension_id')
			->from('#__extensions AS e')
			->join('LEFT', '#__modules AS m ON e.element = m.module')
			->where('m.id = ' . (int) $table->id);
		$db->setQuery($query);

		try
		{
			$extensionId = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());

			return false;
		}

		$this->setState('module.extension_id', $extensionId);
		$this->setState('module.id', $table->id);

		// Clear modules cache
		$this->cleanCache();

		// Clean module cache
		parent::cleanCache($table->module, $table->client_id);

		return true;
	}

	public function saveMenuAssignments($id, $advancedparams)
	{
		$assignment = isset($advancedparams['assignto_menuitems']) ? $advancedparams['assignto_menuitems'] : 0;
		$items      = isset($advancedparams['assignto_menuitems_selection']) ? $advancedparams['assignto_menuitems_selection'] : [];

		$empty = empty($items);

		switch ($assignment)
		{
			case 2:
				$assignment = $empty ? 'all' : 'inverted';
				break;

			case 1:
				$assignment = $empty ? 'none' : 'selection';
				break;

			case 0:
			default:
				$assignment = 'all';
				break;
		}

		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->delete($db->quoteName('#__modules_menu'))
			->where($db->quoteName('moduleid') . ' = ' . (int) $id);
		$db->setQuery($query);
		$db->execute();

		if ($assignment == 'none')
		{
			return true;
		}

		// Check needed to stop a module being assigned to `All`
		// and other menu items resulting in a module being displayed twice.
		if ($assignment === 'all')
		{
			// Assign new module to `all` menu item associations.
			$query->clear()
				->insert($db->quoteName('#__modules_menu'))
				->columns(array($db->quoteName('moduleid'), $db->quoteName('menuid')))
				->values((int) $id . ', 0');
			$db->setQuery($query);

			try
			{
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				$this->setError($e->getMessage());

				return false;
			}

			return true;
		}

		// Get the sign of the number.
		$sign = $assignment == 'inverted' ? -1 : 1;

		// Preprocess the assigned array.
		if (!is_array($items))
		{
			$items = explode(',', $items);
		}
		$items = array_unique($items);

		$inserts = [];
		foreach ($items as &$item)
		{
			if (!is_numeric($item))
			{
				continue;
			}

			$menuid = (int) $item * $sign;

			$inserts[] = (int) $id . ',' . $menuid;
		}

		if (empty($inserts))
		{
			return true;
		}

		$query->clear()
			->insert($db->quoteName('#__modules_menu'))
			->columns(array($db->quoteName('moduleid'), $db->quoteName('menuid')));

		foreach ($inserts as $insert)
		{
			$query->values($insert);
		}

		$db->setQuery($query);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		return true;
	}

	/**
	 * Method to save the advanced parameters.
	 *
	 * @param    array $data The form data.
	 *
	 * @return    boolean    True on success.
	 * @since    1.6
	 */
	public function saveAdvancedParams($data, $id = 0)
	{
		if (!$id)
		{
			$id = JFactory::getApplication()->input->getInt('id');
		}

		if (!$id)
		{
			return true;
		}

		require_once JPATH_ADMINISTRATOR . '/components/com_advancedmodules/tables/advancedmodules.php';
		$table_adv           = JTable::getInstance('AdvancedModules', 'AdvancedModulesTable');
		$table_adv->moduleid = $id;

		if ($id && !$table_adv->load($id))
		{
			$db = $table_adv->getDbo();
			$db->insertObject($table_adv->getTableName(), $table_adv, $table_adv->getKeyName());
		}

		if (isset($data['rules']))
		{
			$table_adv->_title = $data['title'];
			$table_adv->setRules($data['rules']);
		}

		$table_adv->params = json_encode($data);

		// Check the row
		$table_adv->check();

		try
		{
			// Store the data.
			$table_adv->store();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());

			return false;
		}

		return true;
	}

	/**
	 * Method to get and save the module core menu assignments
	 *
	 * @param    int $id The module id.
	 *
	 * @return    boolean    True on success.
	 * @since    1.6
	 */
	public function initAssignments($id, &$module)
	{
		if (!$id)
		{
			$id = JFactory::getApplication()->input->getInt('id');
		}

		if (empty($id))
		{
			$module->advancedparams = [
				'assignto_menuitems'           => $this->config->default_menu_assignment,
				'assignto_menuitems_selection' => [],
			];

			AdvancedModulesModelModule::saveAdvancedParams($module->advancedparams, $id);

			return $module->advancedparams;
		}

		if (is_object($module->advancedparams))
		{
			$module->advancedparams = (array) $module->advancedparams;
		}

		$changed = false;

		if (!isset($module->advancedparams['assignto_menuitems']))
		{
			$this->setMenuItemAssignments($id, $module->advancedparams);

			$changed = true;
		}
		else if (isset($module->advancedparams['assignto_menuitems_selection']['0']) && strpos($module->advancedparams['assignto_menuitems_selection']['0'], ',') !== false)
		{
			$module->advancedparams['assignto_menuitems_selection'] = explode(',', $module->advancedparams['assignto_menuitems_selection']['0']);

			$changed = true;
		}

		if (!isset($module->advancedparams['assignto_date']) || !$module->advancedparams['assignto_date'])
		{
			if ((isset($module->publish_up) && (int) $module->publish_up)
				|| (isset($module->publish_down) && (int) $module->publish_down)
			)
			{
				$module->advancedparams['assignto_date']              = 1;
				$module->advancedparams['assignto_date_publish_up']   = isset($module->publish_up) ? $module->publish_up : '';
				$module->advancedparams['assignto_date_publish_down'] = isset($module->publish_down) ? $module->publish_down : '';

				$changed = true;
			}
		}

		if (!isset($module->advancedparams['assignto_languages']) || !$module->advancedparams['assignto_languages'])
		{
			if (isset($module->language) && $module->language && $module->language != '*')
			{
				$module->advancedparams['assignto_languages']           = 1;
				$module->advancedparams['assignto_languages_selection'] = [$module->language];

				$changed = true;
			}
		}

		if ($changed)
		{
			AdvancedModulesModelModule::saveAdvancedParams($module->advancedparams, $id);
		}

		return $module->advancedparams;
	}

	protected function setMenuItemAssignments($id, &$params)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select('m.menuid')
			->from('#__modules_menu as m')
			->where('m.moduleid = ' . (int) $id);
		$db->setQuery($query);

		$params['assignto_menuitems_selection'] = $db->loadColumn();
		$params['assignto_menuitems']           = 0;

		if (empty($params['assignto_menuitems_selection']))
		{
			$params['assignto_menuitems_selection'] = [];

			return;
		}

		if ($params['assignto_menuitems_selection']['0'] == 0)
		{
			$params['assignto_menuitems_selection'] = [];

			return;
		}

		if ($params['assignto_menuitems_selection']['0'] < 0)
		{
			$params['assignto_menuitems'] = 2;
		}
		else
		{
			$params['assignto_menuitems'] = 1;
		}

		foreach ($params['assignto_menuitems_selection'] as $i => $menuitem)
		{
			if ($menuitem < 0)
			{
				$params['assignto_menuitems_selection'][$i] = $menuitem * -1;
			}
		}
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object $table A record object.
	 *
	 * @return  array  An array of conditions to add to add to ordering queries.
	 */
	protected function getReorderConditions($table)
	{
		$condition   = [];
		$condition[] = 'client_id = ' . (int) $table->client_id;
		$condition[] = 'position = ' . $this->_db->quote($table->position);

		return $condition;
	}

	/**
	 * Custom clean cache method for different clients
	 *
	 * @param   string  $group     The name of the plugin group to import (defaults to null).
	 * @param   integer $client_id The client ID. [optional]
	 *
	 * @return  void
	 */
	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_advancedmodules', $this->getClient());
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param    object $record A record object.
	 *
	 * @return    boolean    True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->state != -2)
			{
				return;
			}
			$user = JFactory::getUser();

			return $user->authorise('core.delete', 'com_modules.module.' . (int) $record->id);
		}
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array  $data An array of input data.
	 * @param   string $key  The name of the key for the primary key.
	 *
	 * @return  boolean
	 */
	protected function allowEdit($data = [], $key = 'id')
	{
		// Initialise variables.
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user     = JFactory::getUser();
		$userId   = $user->get('id');

		// Check general edit permission first.
		if ($user->authorise('core.edit', 'com_modules.module.' . $recordId))
		{
			return true;
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	/**
	 * Function that gets the config settings
	 *
	 * @return    Object
	 */
	private function getConfig()
	{
		if (isset($this->config))
		{
			return $this->config;
		}

		$this->config = RL_Parameters::getInstance()->getComponentParams('advancedmodules');

		return $this->config;
	}
}
