/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

Joomla.submitbutton = function(task) {
	if (task == 'faq.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		Joomla.submitform(task);
	} else {
		var dl					= document.getElementById('system-message');
		dl.style.display 		= 'block';
		var div					= document.getElementById('je-error-message');
		var jeerror				= document.getElementById('je-errorwarning-message').value;
		div.innerHTML			= jeerror;
	}
}