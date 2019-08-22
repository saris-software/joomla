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
 * JE FAQPro categories view.
 */
class jefaqproViewCategories extends JViewLegacy
{
	protected $state			= null;
	protected $item				= null;
	protected $items			= null;

	/**
	 * Display the view
	 *
	 * @return	mixed	False on error, null otherwise.
	 */
	function display($tpl = null)
	{
		// Initialise variables
		$state					= $this->get('State');
		$items					= $this->get('Items');
		$parent					= $this->get('Parent');
		$settings				= $this->get('Settings');

		/*Theme Option from Menu*/
		$app					= JFactory::getApplication();
		$menu					= $app->getMenu();
		$active					= $menu->getActive();
		$settings->theme 		= $active->params->get('themes') ? $active->params->get('themes') : $settings->theme;
		/*Theme Option from Menu*/

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		if($items === false) {
			return JError::raiseNotice(404, JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
		}

		if($parent == false) {
			return JError::raiseNotice(404, JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
		}

		$params					= &$state->params;
		$items					= array($parent->id => $items);

		//Escape strings for HTML output
		$this->pageclass_sfx	= htmlspecialchars($params->get('pageclass_sfx'));

		$this->assign('maxLevelcat',	$params->get('maxLevelcat', -1));
		$this->assignRef('params',		$params);
		$this->assignRef('parent',		$parent);
		$this->assignRef('items',		$items);
		$this->assignRef('settings',	$settings);

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app					= JFactory::getApplication();
		$menus					= $app->getMenu();
		$title					= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
			$menu				= $menus->getActive();
			if($menu) {
				$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
			} else {
				$this->params->def('page_heading', JText::_('COM_JEFAQPRO_DEFAULT_PAGE_TITLE'));
			}

			$title				= $this->params->get('page_title', '');
			if (empty($title)) {
				$title			= $app->getCfg('sitename');
			} elseif ($app->getCfg('sitename_pagetitles', 0)) {
				$title			= JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
			}

		$this->document->setTitle($title);
	}
}
