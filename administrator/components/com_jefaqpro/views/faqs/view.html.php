<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('_JEXEC') or die( 'Restricted Access' );

class JefaqproViewFaqs extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	function display( $tpl = null )
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->user			= JFactory::getUser();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

	foreach ($this->items as &$item) {
			$this->ordering[$item->catid][] = $item->id;
		}

	$this->addToolbar();

	$this->sidebar = JHtmlSidebar::render();

	parent::display( $tpl );
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		// Initialize variables
		$canDo		= jefaqproHelper::getActions($this->state->get('filter.category_id'));

		JToolBarHelper::title( JText::_('COM_JEFAQPRO').' : '.JText::_('COM_JEFAQPRO_MANAGE_FAQS'), 'jefaqpro.png');

		if ($canDo->get('core.create')) {
			JToolbarHelper::addNew('faq.add');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
			JToolbarHelper::editList('faq.edit');
		}

		if ($canDo->get('core.edit.state')) {
			JToolbarHelper::publish('faqs.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('faqs.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('faqs.archive');
		}


		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {

			JToolbarHelper::deleteList('', 'faqs.delete', 'JTOOLBAR_EMPTY_TRASH');

		} else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('faqs.trash','JTOOLBAR_TRASH');
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_jefaqpro');
		}

		JHtmlSidebar::setAction('index.php?option=com_jefaqpro');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_CATEGORY'),
			'filter_category_id',
			JHtml::_('select.options', JHtml::_('category.options', 'com_jefaqpro'), 'value', 'text', $this->state->get('filter.category_id'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);
	}

	protected function getSortFields()
	{
		return array(
			'ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'faq.published' => JText::_('JSTATUS'),
			'faq.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
?>