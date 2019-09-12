<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('_JEXEC') or die;

class jefaqproViewSettings extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->form			= $this->get('Form');
		$this->item			= $this->get('Item');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		parent::display($tpl);
		$this->addToolbar();
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		$canDo 				= jefaqproHelper::getActions();

		$title				= JText::_('COM_JEFAQPRO').' : '.JText::_('COM_JEFAQPRO_GLOBALSETTINGS');

		JToolBarHelper::title($title, 'jefaqpro.png');

		if ($canDo->get('core.admin')) {
			JToolBarHelper::apply('settings.apply','JTOOLBAR_APPLY');
		}

		JToolBarHelper::cancel('settings.cancel', 'JTOOLBAR_CLOSE');
		JToolBarHelper::divider();

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_jefaqpro');
			JToolBarHelper::divider();
		}

	}
}
