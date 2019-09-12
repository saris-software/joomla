/**
 * Import/export configuration file utility
 * 
 * @package JMAP::CONFIG::administrator::components::com_jmap
 * @subpackage js
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
//'use strict';
(function($) {
	var FileConfig = function() {
		/**
		 * Snippet to append for file uploader
		 * 
		 * @access private
		 * @var String
		 */
		var uploaderSnippet ='<div id="uploadrow" class="config-import" style="display: none;">' +
								'<span class="input-group">' +
									'<span class="input-group-addon"><span class="glyphicon glyphicon-upload"></span> ' + COM_JMAP_PICKFILE + '</span>' +
									'<input type="file" id="configurationimport" name="configurationimport" value="">' +
								'</span>' +
								'<button class="btn btn-primary btn-xs" id="startimport">' + COM_JMAP_STARTIMPORT + '</button> ' +
								'<button class="btn btn-primary btn-xs" id="cancelimport">' + COM_JMAP_CANCELIMPORT + '</button>' +
							'</div>';
		
		/**
		 * Function dummy constructor
		 * 
		 * @access private
		 * @param String
		 *            contextSelector
		 * @method <<IIFE>>
		 * @return Void
		 */
		(function __construct() {
			// Remove predefined Joomla behavior
			$('#toolbar-upload button').removeAttr('onclick');
			
			// Append uploader row
			$('#uploadrow').remove();
			$('#adminForm #tab_configuration').before(uploaderSnippet)
			
			// Attach custom feature
			$('#toolbar-upload button').on('click', function(jqEvent){
				jqEvent.preventDefault();
			
				// Append uploader row
				$('#uploadrow').slideDown();
				
				return false;
			});
			
			// Bind the uploader button
			$('#startimport').on('click', function(jqEvent){
				// Validate input
				var fileInput = $('#configurationimport');
				if(!fileInput.val()) {
					fileInput.next('span.validation.label-danger').remove();
					fileInput.css('border', '1px solid #F00').after('<span class="validation label label-danger">' + COM_JMAP_REQUIRED + '</span>');
					fileInput.on('click', function(jqEvent){
						$(this).css('border', '1px solid #ccc').next('span.validation').remove();
					});
					return false;
				}
				
				// Change the task and submit miniform uploader
				var currentMvcCore = $('#adminForm input[name=task]').val().split('.');
				
				$('#adminForm').attr('enctype', 'multipart/form-data');
				$('#adminForm input[name=task]').val(currentMvcCore[0] + '.importConfig');
				$('#adminForm').trigger('submit');
			});
			
			// Cancel upload operation
			$('#cancelimport').on('click', function(jqEvent){
				jqEvent.preventDefault();
				$('#uploadrow').slideUp();
				
				return false;
			});
		}).call(this);
	}

	// On DOM Ready
	$(function() {
		window.JMapFileConfig = new FileConfig();
	});
})(jQuery);