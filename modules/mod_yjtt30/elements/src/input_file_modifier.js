/**

 Author: Constantin Boiangiu ( constantin.b[at]gmail.com )

 Web: www.php-help.ro

 Script webpage: http://www.php-help.ro/mootools-12-javascript-examples/how-to-modify-input-type-file-aspect/ 

 Note: 

 	Major parts of this script were removed for YouJoomla component implementation. 

 **/





var Site = {

	start: function(){

		if($('css_upload')) Site.changeInput();		

	},

	

	changeInput: function(){

		var input = $('css_upload');	

		var container = new Element('span',{

			'class':'input_file_wrapper',

			'id':'input_file_wrapper'

		});

		

		var fake_text = new Element('input',{

			'type':'text',

			'class':'fake-text',

			'value':'',

			'z-index':600

		}).setProperty('disabled','disabled');

		

		input.set({

			styles:{

				'display':'block',

				'position':'absolute',

				'top':'0px',

				'left':'0px',

				'padding':'0px',

				'float':'left',

				'opacity':0.000001,//0.000001

				'font-size':14,

				'z-index':100,

				'background':'none',

				'width':223,

				'height':20

			}		  

		})

		container.injectBefore(input).adopt([fake_text, input]);

		

		input.addEvents({

			'change':function(){

				fake_text.set({

					'value':input.value

				});				

			}			

		});

		

		fake_text.addEvent('keydown', function(){

			fake_text.value='';

		})

		

	}

}

window.addEvent('domready', Site.start);