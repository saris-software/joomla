<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class RseventsproTableDiscount extends JTable
{
	/**
	 * @param	JDatabase	A database connector object
	 */
	public function __construct($db) {
		parent::__construct('#__rseventspro_discounts', 'id', $db);
	}
	
	/**
	 * Method to perform sanity checks on the JTable instance properties to ensure
	 * they are safe to store in the database.  Child classes should override this
	 * method to make sure the data they are storing in the database is safe and
	 * as expected before storage.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 *
	 * @link    http://docs.joomla.org/JTable/check
	 * @since   11.1
	 */
	public function check() {
		$db			= $this->getDbo();
		$tzoffset	= JFactory::getConfig()->get('offset');
		$data		= JFactory::getApplication()->input->get('jform',array(),'array');
		$total		= isset($data['total']) ? 1 : 0;
		$payment	= isset($data['payment']) ? 1 : 0;
		
		$this->total = $total;
		$this->payment = $payment;
		
		if (empty($this->used)) $this->used = 0; 
		if (empty($this->usage)) $this->usage = 0; 
		if (empty($this->same_tickets)) $this->same_tickets = 0;
		if (empty($this->different_tickets)) $this->different_tickets = 0;
		if (empty($this->totalvalue)) $this->totalvalue = 0;
		
		if (!empty($this->from) && $this->from != $db->getNullDate()) {
			$this->from = JFactory::getDate($this->from, $tzoffset)->toSql();
		} else {
			$this->from = $db->getNullDate();
		}
		
		if (!empty($this->to) && $this->to != $db->getNullDate()) {
			$this->to = JFactory::getDate($this->to, $tzoffset)->toSql();
		} else {
			$this->to = $db->getNullDate();
		}
		
		if (isset($this->events) && is_array($this->events)) {
			$registry = new JRegistry();
			$registry->loadArray($this->events);
			$this->events = (string) $registry;
		} else $this->events = '';
		
		if (isset($this->groups) && is_array($this->groups)) {
			$registry = new JRegistry();
			$registry->loadArray($this->groups);
			$this->groups = (string) $registry;
		} else $this->groups = '';
		
		if ($this->apply_to == 2 && empty($this->events)) {
			$this->setError(JText::_('COM_RSEVENTSPRO_DISCOUNT_PLEASE_SELECT_EVENTS'));
			return false;
		}
		
		// Make sure the entered code is unique in the discounts table
		$query = $db->getQuery(true)->select($db->qn('id'))
			->from($db->qn('#__rseventspro_discounts'))
			->where($db->qn('code').' = '.$db->q($this->code))
			->where($db->qn('id').' <> '.$db->q($this->id));
		$db->setQuery($query);
		if ((int) $db->loadResult()) {
			$this->setError(JText::_('COM_RSEVENTSPRO_DISCOUNT_UNIQUE_CODE_ERROR'));
			return false;
		}
		
		// Search for coupon code within other codes
		$query = $db->getQuery(true)->select($db->qn('id'))
			->from($db->qn('#__rseventspro_coupon_codes'))
			->where($db->qn('code').' = '.$db->q($this->code));
		$db->setQuery($query);
		if ((int) $db->loadResult()) {
			$this->setError(JText::_('COM_RSEVENTSPRO_DISCOUNT_UNIQUE_CODE_EVENT_ERROR'));
			return false;
		}
		
		return true;
	}
}