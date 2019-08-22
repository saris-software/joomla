<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/


// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML Form View class for the JEFAQPro component
 */
class jefaqproViewForm extends JViewLegacy
{

	protected $form;
	protected $item;
	protected $return_page;
	protected $state;

	public function display($tpl = null)
	{
		// Initialise variables.
			$app								= JFactory::getApplication();
			$user								= JFactory::getUser();

		// Get model data.
			$this->state						= $this->get('State');
			$this->item							= $this->get('Item');
			$this->form							= $this->get('Form');
			$this->return_page					= $this->get('ReturnPage');

		if (empty($this->item->id)) {
			$authorised							= $user->authorise('core.create', 'com_jefaqpro') || (count($user->getAuthorisedCategories('com_jefaqpro', 'core.create')));
		} else {
			$authorised							= $this->item->params->get('access-edit');
		}

		if ($authorised !== true) {
			JError::raiseWarning('', JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseWarning(500, implode("\n", $errors));
				return false;
			}

		// Create a shortcut to the parameters.
			$params								= &$this->state->params;

		//Escape strings for HTML output
			$this->pageclass_sfx 				= htmlspecialchars($params->get('pageclass_sfx'));

		$this->params							= $params;
		$this->user								= $user;

		$this->_prepareDocument();
		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app									= JFactory::getApplication();
		$menus									= $app->getMenu();
		$pathway								= $app->getPathway();
		$title 									= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
			$menu 								= $menus->getActive();

		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('COM_JEFAQPRO_FORM_EDIT_FAQ'));
		}

		$title									= $this->params->def('page_title', JText::_('COM_JEFAQPRO_FORM_EDIT_FAQ'));
		if ($app->getCfg('sitename_pagetitles', 0)) {
			$title								= JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		$this->document->setTitle($title);

		$pathway								= $app->getPathWay();
		$pathway->addItem($title, '');
	}
}
