<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted access');

// Import Joomla predefined functions
	jimport( 'joomla.application.component.view' );

class  jefaqproViewCategorizedFaqs  extends JViewLegacy
{
	protected $state;
	protected $items;
	protected $pagination;
	protected $total;

	function display( $tpl = null )
	{
		$app						= JFactory::getApplication();
		$params 					= $app->getParams();
		$model						= $this->getModel();
		$user						= JFactory::getUser();

		// Get some data from the models
			$state					= $this->get('State');
			$items					= $this->get('Items');
			$pagination				= $this->get('Pagination');
			$total					= $this->get('total');
			$settings				= $this->get('Settings');

			if (empty($this->item->id)) {
				$authorised							= $user->authorise('core.create', 'com_jefaqpro') || (count($user->getAuthorisedCategories('com_jefaqpro', 'core.create')));
			} else {
				$authorised							= $this->item->params->get('access-edit');
			}
			if ($authorised !== true) {
				$allowed = 0;
			}
			else
			{
				$allowed = 1;
			}

		// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}

		$this->assignRef('items', 		$items);
		$this->assignRef('total',		$total);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('params',		$params);
		$this->assignRef('model',		$model);
		$this->assignRef('user',		$user);
		$this->assignRef('settings',	$settings);
		$this->assignRef('allowed',     $allowed);

		$this->_prepareDocument();

		$this->setDocument();

		parent::display( $tpl );
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app						= JFactory::getApplication();
		$menus						= $app->getMenu();
		$pathway					= $app->getPathway();
		$title						= null;
		$params 					= $app->getParams();
		$menu						= $menus->getActive();

		// because the application sets a default page title, we need to get it
		// right from the menu item itself
			if (is_object($menu)) {
				$menu_params		= new JRegistry;
				$menu_params->loadString($menu->params);
				if (!$menu_params->get('page_title')) {
					$params->set('page_title',	JText::_('COM_JEFAQPRO_TITLE'));
				}
			} else {
				$params->set('page_title',	JText::_('COM_JEFAQPRO_TITLE'));
			}

		$title						= $params->get('page_title');
		if ($app->getCfg('sitename_pagetitles', 0)) {
			$title					= JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}

		$this->document->setTitle($title);
	}

	protected function setDocument()
	{
		$document	= JFactory::getDocument();
		$path		= JURI::root()."components/com_jefaqpro/assets/css/accordionviewcategorized.css";
		$document->addStyleSheet($path);
	}
}
