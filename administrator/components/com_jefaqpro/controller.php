<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Component Controller
 */
class jefaqproController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 */
	protected $default_view = 'faqs';

	/**
	 * Method to display a view.
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once(JPATH_COMPONENT.'/helpers/jefaqpro.php');

		// Load the submenu.
		jefaqproHelper::addSubmenu(JRequest::getCmd('view', 'faqs'));

		$view   = $this->input->get('view', 'faqs');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'faq' && $layout == 'edit' && !$this->checkEditId('com_jefaqpro.edit.faq', $id)) {

			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=faqs', false));

			return false;
		}

  		parent::display();

		return $this;
	}

	public function previewthemes()
	{
		$themeid		= JRequest::getInt('theme', 1);

		echo JHTML::_('image','administrator/components/com_jefaqpro/assets/images/preview/'.$themeid.'.jpg', JText::_('COM_JEFAQPRO_STYLE').$themeid, '', false);
		exit;
	}
}
