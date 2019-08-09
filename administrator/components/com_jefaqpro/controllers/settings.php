<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');

class jefaqproControllerSettings extends JControllerForm
{
	/**
	 * Method to cancel an edit.
	 */
	public function cancel($key = null)
	{
		// Redirect to the main page.
		$this->setRedirect(JRoute::_('index.php?option=com_jefaqpro&view=faqs', false));
	}
}
?>
