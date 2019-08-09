/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Function for theme Preview

function selectTheme( id )
{
	var path 	 = document.getElementById('theme_path').value;
	var theme_id = id;
		var ajaxRequest;  // The variable that makes Ajax possible!
		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("Your browser broke!");
					return false;
				}
			}
		}

		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				var ajaxDisplay = document.getElementById('je-themepreview');
				ajaxDisplay.innerHTML = ajaxRequest.responseText;
				document.getElementById('jform_theme_id').value = id;
			}
		}

	var timeNow = Math.floor(Math.random()*11);
	var url=path+'index.php?option=com_jefaqpro&task=previewthemes&theme='+theme_id+'&timeNow'+timeNow;
	ajaxRequest.open("GET", url, true);
	ajaxRequest.send(null);
}