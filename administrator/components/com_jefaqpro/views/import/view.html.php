<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('_JEXEC') or die( 'Restricted Access' );

jimport('joomla.application.component.view');

class jefaqproViewImport extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	function display( $tpl = null )
	{
//			$this->item			= $this->get('Item');

//			echo "test<br/><pre>"; print_r($this->item); exit;
		// Initialiase variables.
//			$this->form			= $this->get('Form');
//			$this->item			= $this->get('Item');
//			$this->state		= $this->get('State');
//			$this->script		= $this->get('Script');

		// Check for errors.
			if (count($errors	= $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}

			$this->addToolbar();

		parent::display( $tpl );

		// Set the document
		$this->setDocument();
}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		$user					= JFactory::getUser();
		$userId					= $user->get('id');
		$canDo					= jefaqproHelper::getActions();

		$title					= JText::_('COM_JEFAQPRO').' : '.JText::_('COM_JEFAQPRO_IMPORT');

		JToolBarHelper::title($title, 'jefaqpro.png');

		// For new records, check the create permission.
		if ($canDo->get('core.create')) {
			JToolBarHelper::custom('import.import', 'import.png', 'import.png', 'COM_JEFAQPRO_IMPORT', false);
		}
		JToolBarHelper::divider();
		JToolBarHelper::cancel('import.cancel', 'JTOOLBAR_CLOSE');
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		$document		= JFactory::getDocument();
		$document->setTitle(JText::_('COM_JEFAQPRO_IMPORT'));
	}
}
?>