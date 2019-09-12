<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the JE FAQPro component
 */
class jefaqproViewCategory extends JViewLegacy
{
	protected $state;
	protected $items;
	protected $category;
	protected $categories;
	protected $pagination;

	function display($tpl = null)
	{
		$category							= new stdClass();
		$app								= JFactory::getApplication();
		$user								= JFactory::getUser();
		$params								= $app->getParams();

		// Get some data from the models
			$state							= $this->get('State');
			$items							= $this->get('Items');
			$category						= $this->get('Category');
			$children						= $this->get('Children');
			$parent 						= $this->get('Parent');
			$pagination						= $this->get('Pagination');
			$settings						= $this->get('Settings');

			/*Theme Option from Menu*/
			$menu							= $app->getMenu();
			$active							= $menu->getActive();
			$settings->theme 				= $active->params->get('theme') ? $active->params->get('theme') : $settings->theme;
			/*Theme Option from Menu*/

		// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseNotice(500, implode("\n", $errors));
				return false;
			}

		// Check whether category access level allows access.
			$groups							= $user->getAuthorisedViewLevels();

			$cat_id							= JRequest::getVar('id');
			$cat_view						= JRequest::getVar('view');

			if( $cat_id > 0 && $cat_view == 'category' ) {
				$values						= $this->get('faqcategories');
				if( count($values) > 0 ) {
					if (!in_array($values->access, $groups)) {
						JError::raiseNotice(404, JText::_("JERROR_ALERTNOAUTHOR"));
						return false;
					}
				}
			}

			if ($category == false) {
				JError::raiseNotice(404, JText::_('Category Not found'));
				return false;
			}

			if ($parent == false) {
				JError::raiseNotice(404, JText::_('Parent category not found'));
				return false;
			}

			if (!in_array($category->access, $groups)) {
				JError::raiseNotice(404, JText::_("JERROR_ALERTNOAUTHOR"));
				return false;
			}

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

		// Setup the category parameters.
			$cparams						= $category->getParams();
			$category->params				= clone($params);
			$category->params->merge($cparams);

		$children							= array($category->id => $children);

		//Escape strings for HTML output
			$this->pageclass_sfx			= htmlspecialchars($params->get('pageclass_sfx'));

		$faqs_model								= JModelLegacy::getInstance('Faqs', 'jefaqproModel');

		$maxLevel							= $params->get('maxLevel', -1);
		$this->assignRef('maxLevel',	$maxLevel);
		$this->assignRef('state',		$state);
		$this->assignRef('items',		$items);
		$this->assignRef('category',	$category);
		$this->assignRef('children',	$children);
		$this->assignRef('params',		$params);
		$this->assignRef('parent',		$parent);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('model',		$faqs_model);
		$this->assignRef('user',		$user);
		$this->assignRef('settings',	$settings);
		$this->assignRef('allowed',     $allowed);

		// Check for layout override only if this is not the active menu item
		// If it is the active menu item, then the view and category id will match
			$active							= $app->getMenu()->getActive();
			if ((!$active) || ((strpos($active->link, 'view=category') === false) || (strpos($active->link, '&id=' . (string) $this->category->id) === false))) {
				if ($layout = $category->params->get('category_layout')) {
					$this->setLayout($layout);
				}
			} elseif (isset($active->query['layout'])) {
				// We need to set the layout in case this is an alternative menu item (with an alternative layout)
					$this->setLayout($active->query['layout']);
			}

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app								= JFactory::getApplication();
		$menus								= $app->getMenu();
		$pathway							= $app->getPathway();
		$title 								= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
			$menu							= $menus->getActive();

		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('COM_JEFAQPRO_DEFAULT_PAGE_TITLE'));
		}

		$id									= (int) @$menu->query['id'];

		if ($menu && ($menu->query['option'] != 'com_jegaqpro' || $menu->query['view'] == 'faqs' || $id != $this->category->id)) {
			$path							= array(array('title' => $this->category->title, 'link' => ''));
			$category						= $this->category->getParent();

			while (($menu->query['option'] != 'com_jefaqpro' || $menu->query['view'] == 'faqs' || $id != $category->id) && $category->id > 1) {
				$path[]						= array('title' => $category->title, 'link' => jefaqproHelperRoute::getCategoryRoute($category->id));
				$category					= $category->getParent();
			}

			$path							= array_reverse($path);

			foreach($path as $item)	{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		$title								= $this->params->get('page_title', '');

		if (empty($title)) {
			$title							= $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0)) {
			$title							= JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}

		$this->document->setTitle($title);

		if ($this->category->metadesc) {
			$this->document->setDescription($this->category->metadesc);
		}

		if ($this->category->metakey) {
			$this->document->setMetadata('keywords', $this->category->metakey);
		}

		if ($app->getCfg('MetaTitle') == '1') {
			$this->document->setMetaData('title', $this->category->getMetadata()->get('page_title'));
		}

		if ($app->getCfg('MetaAuthor') == '1') {
			$this->document->setMetaData('author', $this->category->getMetadata()->get('author'));
		}

		$mdata								= $this->category->getMetadata()->toArray();

		foreach ($mdata as $k => $v) {
			if ($v) {
				$this->document->setMetadata($k, $v);
			}
		}
	}
}