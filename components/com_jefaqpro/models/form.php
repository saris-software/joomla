<?php
/**
 * @version		$Id: form.php 20228 2011-01-10 00:52:54Z eddieajau $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Base this model on the backend version.
	require_once JPATH_ADMINISTRATOR.'/components/com_jefaqpro/models/faq.php';

/**
 * JEFAQ Pro Component Form Model
 */
class jefaqproModelForm extends jefaqproModelFaq
{
	/**
	 * Method to auto-populate the model state.
	 */
	protected function populateState()
	{
		$app					= JFactory::getApplication();
		
		$catid = JRequest::getVar('catid');

		if($catid > 0)
			$cat_id = $catid;
		else
			$cat_id =  JRequest::getInt('catid');

		// Load state from the request.
			$pk					= JRequest::getInt('a_id');
			$this->setState('faq.id', $pk);

		$this->setState('faq.catid',$cat_id);

		$return					= JRequest::getVar('return', null, 'default', 'base64');
		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
			$params				= $app->getParams();
			$this->setState('params', $params);

		$this->setState('layout', JRequest::getCmd('layout'));
	}

	/**
	 * Method to get FAQ's data.
	 */
	public function getItem($itemId = null)
	{
		// Initialise variables.
			$itemId				= (int) (!empty($itemId)) ? $itemId : $this->getState('faq.id');

		// Get a row instance.
			$table				= $this->getTable();

		// Attempt to load the row.
			$return				= $table->load($itemId);

		// Check for a table object error.
			if ($return === false && $table->getError()) {
				$this->setError($table->getError());
				return false;
			}

		$properties				= $table->getProperties(1);
		$value					= JArrayHelper::toObject($properties, 'JObject');

		// Convert attrib field to Registry.
			$value->params		= new JRegistry;

		// Compute selected asset permissions.
			$user				= JFactory::getUser();
			$userId				= $user->get('id');
			$asset				= 'com_jefaqpro.faq.'.$value->id;

		// Check general edit permission first.
			if ($user->authorise('core.edit', $asset)) {
				$value->params->set('access-edit', true);
			}


		// Now check if edit.own is available.
			else if (!empty($userId) && $user->authorise('core.edit.own', $asset)) {
				// Check for a valid user and that they are the owner.
					if ($userId == $value->created_by) {
						$value->params->set('access-edit', true);
					}
			}

		// Check edit state permission.
			if ($itemId) {
				// Existing item
					$value->params->set('access-change', $user->authorise('core.edit.state', $asset));
			} else {
				// New item.
					$catId		= (int) $this->getState('faqs.catid');
					if ($catId) {
						$value->params->set('access-change', $user->authorise('core.edit.state', 'com_jefaqpro.category.'.$catId));
					} else {
						$value->params->set('access-change', $user->authorise('core.edit.state', 'com_jefaqpro'));
					}
			}

		return $value;
	}

	/**
	 * Get the return URL.
	 */
	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}
}