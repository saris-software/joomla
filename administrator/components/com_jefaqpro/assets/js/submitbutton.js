/**
 * @version		$Id: submitbutton.js 74 2010-12-01 22:04:52Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */

Joomla.submitbutton = function(task)
{
location.reload();
	if (task == '') {
		return false;
	} else {
		var isValid=true;
		var action = task.split('.');
		if (action[1] != 'cancel' && action[1] != 'close') {
			var forms = $$('form.form-validate');
			for (var i=0;i<forms.length;i++)
			{
				if (!document.formvalidator.isValid(forms[i]))
				{
					isValid = false;
					break;
				}
			}
		}

		if (isValid) {
			Joomla.submitform(task);
			return true;
		} else {
			return false;
		}
	}
}

Joomla.submit1 = function(task)
{
	var isValid = false;
	var extValid = false;
	if (task == '') {
		return false;
	} else {
		var docs = document.getElementsByName("uploadedfile");
		for(var i = 0;i < docs.length;i++) {
			var doc		 = docs[i];
			var doc_name = doc.value;
			if( doc_name != "") {
				isValid = true;
				var ext = doc_name.split('.');
				if(ext[1] == "csv") {
					extValid = true;
					doc.style.border="1px solid #C0C0C0";
				} else {
					extValid = false;
					doc.style.border="1px solid #FF0000";
					break;
				}
			}
		}

		if(!isValid){
			alert("Please first select a file for upload");
			return false;
			}
		if (isValid && extValid) {
			Joomla.submitform(task);
			return true;
		} else {
			return false;
		}
	}
}

