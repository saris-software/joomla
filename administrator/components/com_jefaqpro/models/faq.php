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

jimport('joomla.application.component.modeladmin');

class jefaqproModelFaq extends JModelAdmin
{
		/**
	 * Method to test whether a record can be deleted.
	 */
	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if ($record->catid) {
			return $user->authorise('core.delete', 'com_jefaqpro.faq.'.(int) $record->catid);
		} else {
			return parent::canDelete($record);
		}
	}

	/**
	 * Method to test whether a record can be deleted.
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check against the category.
			if (!empty($record->catid)) {
				return $user->authorise('core.edit.state', 'com_jefaqpro.faq.'.(int) $record->catid);
			}
		// Default to component settings if category not known.
			else {
				return parent::canEditState($record);
			}
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 */
	public function getTable($type = 'Faq', $prefix = 'jefaqproTable', $config = array())
{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the row form.
	 */
	public function getForm($data = array(), $loadData = true)
	{
		jimport('joomla.form.form');

		// Get the form.
			$form	= $this->loadForm('com_jefaqpro.faq', 'faq', array('control' => 'jform', 'load_data' => $loadData));
			if (empty($form)) {
				return false;
			}

		// Modify the form based on access controls.
			if (!$this->canEditState((object) $data)) {
				// Disable fields for display.
					$form->setFieldAttribute('ordering', 'disabled', 'true');
					$form->setFieldAttribute('published', 'disabled', 'true');
					$form->setFieldAttribute('access', 'disabled', 'true');
					$form->setFieldAttribute('language', 'disabled', 'true');

				// Disable fields while saving.
				// The controller has already verified this is a record you can edit.
					$form->setFieldAttribute('ordering', 'filter', 'unset');
					$form->setFieldAttribute('published', 'filter', 'unset');
					$form->setFieldAttribute('access', 'filter', 'unset');
					$form->setFieldAttribute('language', 'filter', 'unset');
			}

		return $form;
	}

	/**
	 * Method to get a single record.
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		return $item;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
			$data					= JFactory::getApplication()->getUserState('com_jefaqpro.edit.faq.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('faq.id') == 0) {
				$app = JFactory::getApplication();
				$data->set('catid', JRequest::getInt('catid', $app->getUserState('com_jefaqpro.faqs.filter.category_id')));
			}
		}

		return $data;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if (empty($table->id)) {
			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__jefaqpro_faq');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		} else {
			// Set the values
			$table->modified_date	= $date->toSql();
			$table->modified_by		= $user->get('id');
		}
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = '.(int) $table->catid;
		$condition[] = 'published >= 0';

		return $condition;
	}

	public function reorder($pks, $delta = 0)
	{
		// Clear the component's cache
		$cache = JFactory::getCache();
		$cache->clean('com_jefaqpro');

		return parent::reorder($pks, $delta);
	}

	public function mailtoAdmin( $user_email, $user_name, $user_questions, $catid )
	{
		$app							= JFactory::getApplication();
		$config							= JComponentHelper::getParams('com_jefaqpro');

		$to								= $app->getCfg('mailfrom');
		$sett_to						= $config->get('emailid');
		$category						= $this->getCategoryName( $catid );

		if ( $sett_to == 'admin@emailid.com' || $sett_to == '' ) {
			$to		= $app->getCfg('mailfrom');  //outputs mailfrom
		} else {
			$to		= $sett_to;
		}

		$from		= $user_email;
		$name		= $user_name;
		$site		= $app->getCfg('sitename'); //outputs sitename

		$question	= $user_questions;

		$sender 	= array( $from, $name );
		$mailer 	= & JFactory::getMailer();
		$mailer->setSender( $sender );

		$mailer->addRecipient( $to );

		$subject 	= sprintf ( JText::_( 'SEND_MSG_ADMIN_SUB' ), $name );
		$subject 	= html_entity_decode($subject, ENT_QUOTES);

		$message 	= sprintf ( JText::_( 'SEND_MSG_ADMIN' ), $name, $category, $question, $name );
		$message 	= html_entity_decode($message, ENT_QUOTES);

		$mailer->setSubject( $subject );
		$mailer->setBody( $message );

		$mailer->IsHTML(true);

		if ($mailer->Send() == true) {
			return true;
		} else {
			return false;
		}
	}

	public function mailtoUser( $user_email, $user_name, $user_questions, $catid, $admin_answer )
	{
		$app				= & JFactory::getApplication();

		$to					= $user_email ;
		$from				= $app->getCfg('mailfrom'); //outputs mailfrom
		$name				= $user_name;
		$site				= $app->getCfg('sitename'); //outputs sitename

		$question			= $user_questions;
		$answers			= $admin_answer;

		$category_name 		= $this->getCategoryName( $catid );

		$sender				= array( $from, $site );
		$mailer				= & JFactory::getMailer();
		$mailer->setSender( $sender );

		$mailer->addRecipient( $to );

		// Subject
		$subject 			= sprintf ( JText::_( 'SEND_MSG_ADMIN_SUB' ), $site );
		$subject 			= html_entity_decode($subject, ENT_QUOTES);

		// Message
		$message 	   		= sprintf ( JText::_( 'SEND_MSG_ADMIN' ),$name, $category_name, $question, $answers, $name );
		$message 	   		= html_entity_decode($message, ENT_QUOTES);

		$img_tag_count 		= substr_count($message, '<img');
		$regex 				= '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im';

		if($img_tag_count > 0) {
			preg_match_all ( $regex, $message, $matches1,PREG_SET_ORDER );

			foreach ($matches1 as $val) {
				$imageurl[] = $val[2];
			}

			$matches = array_unique($imageurl);

			foreach ($matches as $val) {
				$image 					= '';
				$img_path_check_http 	= '';
				$img_path_check_www 	= '';
				$image_new				= '';
				$image 					= trim ( $val );
				$img_path_check_http 	= substr_count($image, 'http');
				$img_path_check_www 	= substr_count($image, 'www');
				if($img_path_check_http == '0' && $img_path_check_www == '0') {
					$image_new 			= JURI::root().$image;
					$message 			= str_replace($image, $image_new, $message);
				}
			}
		}

		$mailer->setSubject( $subject );
		$mailer->setBody( $message );

		$mailer->IsHTML(true);

		if ($mailer->Send() == true) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get the category name.
	 */
	protected function getCategoryName( $catid )
	{
		if ($catid) {
			$db			= $this->getDbo();
			$query		= $db->getQuery(true);
			$query->select('title');
			$query->from('`#__categories`');
			$query->where('`id`='.$db->quote($catid));
			$db->setQuery((string)$query);
			$name		= $db->loadResult();

			if ($db->getErrorNum()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		} else {
			$name		= JText::_('COM_BANNERS_NOCATEGORYNAME');
		}

		return $name;
	}
}
?>
