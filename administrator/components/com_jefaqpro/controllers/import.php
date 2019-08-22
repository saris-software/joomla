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
jimport('joomla.filesystem.folder');

class jefaqproControllerImport extends JControllerLegacy
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

	public function import() {
		$files			  = JRequest::getVar( 'jefaqpro_imports', '', 'files', 'array');
		$noerror		  = $importtasks  = array();
		$upload_directory = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jefaqpro'.DS.'assets'.DS.'importtables';
		if(!JFolder::exists($upload_directory))
			JFolder::create($upload_directory, '0755');
		for($i=0;$i<3;$i++) {
			$name 	  = $files['name'][$i];
			$error 	  = $files['error'][$i];
			$tmp_name = $files['tmp_name'][$i];
			$size 	  = $files['size'][$i];
			$format   = strtolower(JFile::getExt($name));

			if($name) {
				if($error == 0) {
					if($format != "sql") {
						JError::raiseWarning(21, JText::_("COM_JEFAQPRO_UPLOAD_FAILED_WRONG_FILE")." ".$name);
						continue;
					}
					if($i==0) {
						$file_name     = "category.sql";
						$importtasks[] = "cat";
					} else if($i==1) {
						$file_name     = "faq.sql";
						$importtasks[] = "faq";
					} else if($i==2){
						$file_name     = "responses.sql";
						$importtasks[] = "res";
					}

					$file_newname  =  $upload_directory . DS . $file_name;
					if (!JFile::upload($tmp_name,$file_newname)) {
						JError::raiseWarning( 500, JText::_('COM_JEFAQPRO_NOTUPLOADED')." ".$name);
					} else
						$noerrorfiles[] = $name;
				} else
					JError::raiseWarning( 500, JText::_('COM_JEFAQPRO_NOTUPLOADED')." ".$name);
			}
		}

		if(count($noerrorfiles)) {
			JFactory::getApplication()->enqueueMessage(count($noerrorfiles).' '.JText::_("COM_JEFAQPRO_UPLOADED_SUCCESSFULLY").' '.implode(" , ",$noerrorfiles));
			$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=import&redirect=1&importtasks='.implode(',',$importtasks), false));
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=import', false));
		}
	}

	public function importstart() {
		set_time_limit(0);
		$tasks = JRequest::getvar("importtasks");
		$tasks = explode(',',$tasks);
		foreach($tasks as $task) {
			if($task == "cat") {
				$this->importcategory();
				break;
			}
			else if($task == "faq") {
				$this->importfaq();
				break;
			}
			else if($task == "res") {
				$this->importres();
				break;
			}
		}
	}

	public function importcategory() {
		$model = $this->getmodel('import');
		if($model->importCategoryItems())
			$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=import&redirect=1&importtasks=faq,res', false));
		else
			$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=import', false));
	}

	public function importfaq() {
		$model = $this->getmodel('import');
		if($model->importFaqItems())
			$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=import&redirect=1&importtasks=res', false));
		else
			$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=import', false));
	}

	public function importres() {
		$model = $this->getmodel('import');
		$model->importResposeItems();
		$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=import', false));
	}
}
?>
