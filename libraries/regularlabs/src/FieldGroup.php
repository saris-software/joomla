<?php
/**
 * @package         Regular Labs Library
 * @version         17.5.25583
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2017 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use JText;

class FieldGroup
	extends Field
{
	public $type          = 'Field';
	public $default_group = 'Categories';

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		return $this->getSelectList();
	}

	public function getGroup()
	{
		$this->params = $this->element->attributes();

		return $this->get('group', $this->default_group ?: $this->type);
	}

	public function getOptions()
	{
		$group = $this->getGroup();
		$id    = $this->type . '_' . $group;

		if (!isset($data[$id]))
		{
			$data[$id] = $this->{'get' . $group}();
		}

		return $data[$id];
	}

	public function getSelectList($group = '')
	{
		if (!is_array($this->value))
		{
			$this->value = explode(',', $this->value);
		}

		$size     = (int) $this->get('size');
		$multiple = $this->get('multiple');

		$group   = $group ?: $this->getGroup();
		$options = $this->getOptions();

		switch ($group)
		{
			case 'categories':
				return Form::selectList($options, $this->name, $this->value, $this->id, $size, $multiple);

			default:
				return Form::selectListSimple($options, $this->name, $this->value, $this->id, $size, $multiple);
		}
	}

	public function missingFilesOrTables($tables = ['categories', 'items'], $component = '', $table_prefix = '')
	{
		$component = $component ?: $this->type;

		if (!Extension::isInstalled($component))
		{
			return '<fieldset class="alert alert-danger">' . JText::_('ERROR') . ': ' . JText::sprintf('RL_FILES_NOT_FOUND', JText::_('RL_' . strtoupper($component))) . '</fieldset>';
		}

		$group = $this->getGroup();

		if (!in_array($group, $tables) && !in_array($group, array_keys($tables)))
		{
			// no need to check database table for this group
			return false;
		}

		$table_list = $this->db->getTableList();

		$table = isset($tables[$group]) ? $tables[$group] : $group;
		$table = $this->db->getPrefix() . strtolower($table_prefix ?: $component) . '_' . $table;

		if (in_array($table, $table_list))
		{
			// database table exists, so no error
			return false;
		}

		return '<fieldset class="alert alert-danger">' . JText::_('ERROR') . ': ' . JText::sprintf('RL_TABLE_NOT_FOUND', JText::_('RL_' . strtoupper($component))) . '</fieldset>';
	}
}
