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

use RegularLabs\Library\Parameters as RL_Parameters;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Library\StringHelper as RL_String;

/**
 * View to edit a module.
 */
class AdvancedModulesViewModule extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = JHelperContent::getActions('com_modules', 'module', $this->item->id);
		$this->getConfig();
		$this->getAssignments();

		if (!isset($this->item->published) || $this->item->published == '')
		{
			$this->item->published = $this->config->default_state;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		if (RL_RegEx::match('_gk[1-9]', $this->item->module))
		{

			// Set message for Gavick modules
			JFactory::getApplication()->enqueueMessage(JText::sprintf(RL_String::html_entity_decoder(JText::_('AMM_MODULE_INCOMPATIBLE')), '<a href="index.php?option=com_modules&force=1&task=module.edit&id=' . (int) $this->item->id . '">', '</a>'), 'warning');
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Function that gets the config settings
	 *
	 * @return    Object
	 */
	protected function getConfig()
	{
		if (isset($this->config))
		{
			return $this->config;
		}

		$this->config = RL_Parameters::getInstance()->getComponentParams('advancedmodules');

		return $this->config;
	}

	/**
	 * Function that gets the config settings
	 *
	 * @return    Object
	 */
	protected function getAssignments()
	{
		if (!isset($this->assignments))
		{
			$xmlfile     = JPATH_ADMINISTRATOR . '/components/com_advancedmodules/assignments.xml';
			$assignments = new JForm('assignments', ['control' => 'advancedparams']);
			$assignments->loadFile($xmlfile, 1, '//config');
			$assignments->bind($this->item->advancedparams);
			$this->assignments = $assignments;
		}

		return $this->assignments;
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user       = JFactory::getUser();
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo      = $this->canDo;

		$title = $this->item->title . ' [' . $this->item->module . ']';
		JToolbarHelper::title(JText::sprintf('AMM_MODULE_EDIT', $title), 'advancedmodulemanager icon-reglab');

		// For new records, check the create permission.
		if ($isNew && $canDo->get('core.create'))
		{
			JToolbarHelper::apply('module.apply');
			JToolbarHelper::save('module.save');
			JToolbarHelper::save2new('module.save2new');
			JToolbarHelper::cancel('module.cancel');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission.
				if ($canDo->get('core.edit'))
				{
					JToolbarHelper::apply('module.apply');
					JToolbarHelper::save('module.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolbarHelper::save2new('module.save2new');
					}
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				JToolbarHelper::save2copy('module.save2copy');
			}

			JToolbarHelper::cancel('module.cancel', 'JTOOLBAR_CLOSE');
		}

		$tmpl = JFactory::getApplication()->input->get('tmpl');
		if ($tmpl != 'component')
		{
			if ($canDo->get('core.admin'))
			{
				JToolbarHelper::preferences('com_advancedmodules', 600, 900);
			}

			// Get the help information for the menu item.
			$lang = JFactory::getLanguage();

			$help = $this->get('Help');

			if ($lang->hasKey($help->url))
			{
				$debug = $lang->setDebug(false);
				$url   = JText::_($help->url);
				$lang->setDebug($debug);
			}
			else
			{
				$url = null;
			}

			JToolbarHelper::help($help->key, false, $url);
		}
	}

	protected function render(&$form, $name = '')
	{
		$items = [];

		foreach ($form->getFieldset($name) as $field)
		{
			$items[] = '<div class="control-group"><div class="control-label">'
				. $field->label
				. '</div><div class="controls">'
				. $field->input
				. '</div></div>';
		}

		if (empty ($items))
		{
			return '';
		}

		return implode('', $items);
	}
}
