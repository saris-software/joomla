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

class jefaqproTableFaq extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct( '#__jefaqpro_faq', 'id', $db );
	}

	/**
	 * Overloaded bind function
	 */
	public function bind($array, $ignore = '')
	{
		return parent::bind($array, $ignore);
	}

	/**
	 * Stores a faq
	 */
	public function store($updateNulls = false)
	{
		// Joomla predefined functions.
			$date									= JFactory::getDate();
			$user									= JFactory::getUser();
			$config									= JComponentHelper::getParams('com_jefaqpro');

		if ($this->id) {
			// Existing item
				$this->modified_date	= $date->toSql();
				$this->modified_by		= $user->get('username');

				$posted_user						= JFactory::getUser( $this->uid );
				$author 							= $posted_user->authorise('core.admin', 'com_jefaqpro');

			// Send mail to users, when admin submit the reply.
				if( $author ) {
				} else {
					if($config->get('send_user')) {
						if( $this->email_status == '0') {
							$model					= JModelLegacy::getInstance('Faq','jefaqproModel');
							if($model->mailtoUser( $this->posted_email, $this->posted_by, $this->questions, $this->catid, $this->answers ))
								$this->email_status = '1';

						}
					}
				}

			// Get the old row
				$oldrow 							= JTable::getInstance('Faq', 'jefaqproTable');
				if (!$oldrow->load($this->id) && $oldrow->getError()) {
					$this->setError($oldrow->getError());
				}

			// Change the order from old to new..
				if ($oldrow->published>=0 && ($this->published < 0 || $oldrow->catid != $this->catid)) {
					$this->ordering						= self::getNextOrder('`catid`=' . $this->_db->Quote($this->catid).' AND published>=0');
				}

			parent::store($updateNulls);

			// Reorder the oldrow
				if ($oldrow->published>=0 && ($this->published < 0 || $oldrow->catid != $this->catid)) {
					$this->reorder('`catid`=' . $this->_db->Quote($oldrow->catid).' AND published>=0');
				}

		} else {

			if (!intval($this->posted_date)) {
				$this->posted_date	= $date->toSql();
			}

			if (empty($this->posted_by)) {
				$this->posted_by	= $user->get('username');
			}

			if (empty($this->posted_email)) {
			$this->posted_email		= $user->get('email');
		}

			if (empty($this->language)) {
				$this->language						= '*';
	                }

			$this->uid								= $user->get('id');

			// Set ordering to last if ordering was 0
				$this->ordering						= self::getNextOrder('`catid`=' . $this->_db->Quote($this->catid).' AND published>=0');

			// Mail to Admin when user the post FAQ's
				require_once(JPATH_ADMINISTRATOR.'/components/com_jefaqpro/helpers/jefaqpro.php');
				$canDo 								= jefaqproHelper::getActions();
				if ($canDo->get('core.admin')) {
				} else {
					if($config->get('auto_publish')) {
						$this->published			= '1';
					}
					if($config->get('send_admin')) {
						$model						= JModelLegacy::getInstance('Faq','jefaqproModel');
						$model->mailtoAdmin( $this->posted_email, $this->posted_by, $this->questions, $this->catid );
					}
				}

			parent::store($updateNulls);
		}

		// Attempt to store the data.
			return count($this->getErrors())==0;
	}

	function check()
	{
		require_once(JPATH_ADMINISTRATOR.'/components/com_jefaqpro/helpers/jefaqpro.php');
		$canDo 										= jefaqproHelper::getActions();
		if ($canDo->get('core.admin')) {
			if (trim($this->answers) == '') {
				$this->setError(JText::_('COM_JEFAQPRO_ANSWERS_MUST_HAVE_TEXT'));
				return false;
			}
		}

		return true;
	}
}
?>
