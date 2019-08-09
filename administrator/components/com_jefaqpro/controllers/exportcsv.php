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

jimport('joomla.application.component.controllerform');

class jefaqproControllerExportcsv extends JControllerForm
{
	/**
	 * Method override to check if you can add a new record.
	 */
	protected function allowAdd($data = array())
	{
		// Initialise variables.
			$user		= JFactory::getUser();
			$categoryId	= JArrayHelper::getValue($data, 'catid', JRequest::getInt('filter_category_id'), 'int');
			$allow		= null;

		if ($categoryId) {
			// If the category has been passed in the URL check it.
				$allow	= $user->authorise('core.create', $this->option.'.category.'.$categoryId);
		}

		if ($allow === null) {
			// In the absense of better information, revert to the component permissions.
				return parent::allowAdd($data);
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
			$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
			$user		= JFactory::getUser();
			$userId		= $user->get('id');
			$categoryId	= (int) isset($data['catid']) ? $data['catid'] : 0;

		// Check general edit permission first.
			if ($user->authorise('core.edit', $this->option.'.category.'.$categoryId)) {
				return true;
			}

		// Fallback on edit.own.
		// First test if the permission is available.
			if ($user->authorise('core.edit.own', $this->option.'.category.'.$categoryId)) {
				// Now test the owner is the user.
					$ownerId	= (int) isset($data['created_by']) ? $data['created_by'] : 0;
					if (empty($ownerId) && $recordId) {
						// Need to do a lookup from the model.
							$record		= $this->getModel()->getItem($recordId);

						if (empty($record)) {
							return false;
						}

						$ownerId = $record->created_by;
					}

				// If the owner matches 'me' then do the test.
					if ($ownerId == $userId) {
						return true;
					}
			}

		// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
	}



	public function exportcsvcat($model = null)
	{

		//JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel();
		$model->exportcategory();

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=exportcsv', false));
	}

	public function exportcsvfaqs($model = null)
	{

		//JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel();
		$model->exportfaq();

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=exportcsv', false));
	}

}
?>
