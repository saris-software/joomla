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
	jimport('joomla.application.component.controllerform');
	require_once (JPATH_COMPONENT.'/captcha/captcha.php');

class jefaqproControllerfaq extends JControllerForm
{
	/**
	 * @since	1.6
	 */
	protected $view_item 		= 'form';

	/**
	 * @since	1.6
	 */
	protected $view_list		= 'categories';

	/**
	 * Method to add a new record.
	 */
	public function add()
	{
		if (!parent::add()) {
			// Redirect to the return page.
				$this->setRedirect($this->getReturnPage());
		}
	}

	/**
	 * Method override to check if you can add a new record.
	 */
	protected function allowAdd($data = array())
	{
		// Initialise variables.
			$user				= JFactory::getUser();
			$categoryId			= JArrayHelper::getValue($data, 'catid', JRequest::getInt('catid'), 'int');

		$allow					= null;

		if ($categoryId) {
			// If the category has been passed in the data or URL check it.
				$allow			= $user->authorise('core.create', 'com_jefaqpro.category.'.$categoryId);
		}

		if ($allow === null) {
			// In the absense of better information, revert to the component permissions.
				return parent::allowAdd();
		} else {
			return $allow;
		}
	}

	/**
	 * Method override to check if you can edit an existing record.
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Initialise variables.
			$recordId			= (int) isset($data[$key]) ? $data[$key] : 0;
			$user				= JFactory::getUser();
			$userId				= $user->get('id');
			$asset				= 'com_jefaqpro.faqs.'.$recordId;

		// Check general edit permission first.
		if ($user->authorise('core.edit', $asset)) {
			return true;
		}

		// Fallback on edit.own.
		// First test if the permission is available.
			if ($user->authorise('core.edit.own', $asset)) {
				// Now test the owner is the user.
				$ownerId		= (int) isset($data['posted_by']) ? $data['posted_by'] : 0;
				if (empty($ownerId) && $recordId) {
					// Need to do a lookup from the model.
					$record		= $this->getModel()->getItem($recordId);

					if (empty($record)) {
						return false;
					}

					$ownerId	= $record->posted_by;
				}

				// If the owner matches 'me' then do the test.
				if ($ownerId == $userId) {
					return true;
				}
			}

		// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
	}

	/**
	 * Method to cancel an edit.
	 */
	public function cancel($key = 'a_id')
	{
			parent::cancel($key);
			// Redirect to the return page.
				$link			= $this->getReturnPage();

			$this->setRedirect($link);
	}

	/**
	 * Method to edit an existing record.
	 */
	public function edit($key = null, $urlVar = 'a_id')
	{
		$result					= parent::edit($key, $urlVar);

		return $result;
	}

	/**
	 * Method to get a model object, loading it if required.
	 */
	public function &getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'a_id')
	{
		// Need to override the parent method completely.
			$tmpl				= JRequest::getCmd('tmpl');
			$layout				= JRequest::getCmd('layout', 'edit');
			$append				= '';

		// Setup redirect info.
			if ($tmpl) {
				$append			.= '&tmpl='.$tmpl;
			}

		// TODO This is a bandaid, not a long term solution.
			$append				.= '&layout=edit';

		if ($recordId) {
			$append				.= '&'.$urlVar.'='.$recordId;
		}

		$itemId					= JRequest::getInt('Itemid');
		$return					= $this->getReturnPage();

		if ($itemId) {
			$append				.= '&Itemid='.$itemId;
		}

		if ($return) {
			$append				.= '&return='.base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL.
	 */
	protected function getReturnPage()
	{
		$return					= JRequest::getVar('return', null, 'default', 'base64');
		if (empty($return)) {
			return JURI::base();
		} else {
			return base64_decode($return);
		}
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		$task = $this->getTask();

		if ($task == 'save') {
			$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=category&id='.$validData['catid'], false));
		}
	}

	/**
	 * Method to save a record.
	 */
	public function saveOld($key = null, $urlVar = 'a_id')
	{
		$result = parent::save($key, $urlVar);

		// If ok, redirect to the return page.
			if ($result) {
				$this->setRedirect($this->getReturnPage());
			}

		return $result;
	}

	public function save($key = null, $urlVar = 'a_id')
	{

		$app = JFactory::getApplication();
		$config = $app->getParams();

		$enablecaptcha = $config->get('captcha_show', '1');

		if (AutarticaptchaHelper::checkCaptcha($enablecaptcha)){

			$result					= parent::save($key, $urlVar);

			// If ok, redirect to the return page.
			if ($result) {
				$this->setRedirect($this->getReturnPage());
			}
		}
		else
		{
			$post	 		= JRequest::getVar('jform', array(), 'post', 'array');
			$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=form&layout=edit'), "Entered a wrong Captcha sequence", "Error");
			$app->setUserState('com_jefaqpro.edit.faq.data', $post);

		}
		return $result;
	}
}
?>
