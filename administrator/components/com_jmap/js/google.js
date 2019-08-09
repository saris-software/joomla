/**
 * Submit sitemaps to GWT utility class
 * 
 * @package JMAP::GOOGLE::administrator::components::com_jmap
 * @subpackage js
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
//'use strict';
(function($) {
	var WebmastersTools = function() {
		/**
		 * Snippet to append for file uploader
		 * 
		 * @access private
		 * @var String
		 */
		var submitsitemapSnippet ='<div id="uploadrow" style="display: none;">' +
								'<span class="input-group input-large">' +
									'<span title="' + COM_JMAP_ADDSITEMAP_DESC + '" class="input-group-addon"><span class="glyphicon glyphicon-upload"></span> ' + COM_JMAP_ADDSITEMAP + '</span>' +
									'<input type="text" id="sitemaplink" name="sitemaplink" value="">' +
								'</span>' +
								'<button class="btn btn-primary btn-xs" id="startimport">' + COM_JMAP_SUBMIT + '</button> ' +
								'<button class="btn btn-primary btn-xs" id="cancelimport">' + COM_JMAP_CANCEL + '</button>' +
							'</div>';
		
		/**
		 * Clicked sitemap URL to delete
		 * 
		 * @access private
		 * @var String
		 */
		var sitemapUrl = '';
		
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
			$('#adminForm').prepend(submitsitemapSnippet)
			
			// Attach custom feature
			$('#toolbar-upload button').on('click', function(jqEvent){
				jqEvent.preventDefault();
			
				// Append uploader row
				$('#uploadrow').slideDown();
				
				return false;
			});
			
			// Bind the uploader button
			$('#startimport').on('click', function(jqEvent) {
				// Validate input
				var sitemapInput = $('#sitemaplink');
				var sitemapLink = $('#sitemaplink').val();
				if(!sitemapLink) {
					sitemapInput.css('border', '1px solid #F00').next('span.validation').remove().end().after('<span class="validation label label-danger">' + COM_JMAP_REQUIRED + '</span>');
					sitemapInput.on('change', function(jqEvent){
						$(this).css('border', '1px solid #ccc').next('span.validation').remove();
					});
					return false;
				}
				
				// Validate URL
				var expression = "^(http[s]?:\\/\\/(www\\.)?|ftp:\\/\\/(www\\.)?|www\\.){1}([0-9A-Za-z-\\.@:%_\+~#=]+)+((\\.[a-zA-Z]{2,3})*)(/(.)*)?(\\?(.)*)?";
				var regexUrl = new RegExp(expression);
				if(!sitemapLink.match(regexUrl)) {
					sitemapInput.css('border', '1px solid #F00').next('span.validation').remove().end().after('<span class="validation label label-danger">' + COM_JMAP_INVALID_URL_FORMAT + '</span>');
					sitemapInput.on('change', function(jqEvent){
						$(this).css('border', '1px solid #ccc').next('span.validation').remove();
					});
					return false;
				}
				
				// Change the task and submit miniform uploader
				$('#adminForm input[name=task]').val('google.submitSitemap');
				$('#adminForm').trigger('submit');
			});
			
			// Cancel upload operation
			$('#cancelimport').on('click', function(jqEvent){
				jqEvent.preventDefault();
				$('#uploadrow').slideUp();
				
				return false;
			});
			
			// Modal sitemap delete confirmation
			$('#sitemapDeleteModal').on('shown.bs.modal', function(bsEvent) {
				$('button[data-role=confirm-delete]').one('click', function(jqEvent){
					$('#adminForm input[name=sitemapurl]').val(sitemapUrl);
					$('#adminForm input[name=task]').val('google.deleteSitemap');
					$('#adminForm').submit();
				});
			});
			
			$('a[data-role=sitemapdelete]').on('click', function (jqEvent) {
				sitemapUrl = $(this).data('url');
				
				$('#sitemapDeleteModal').modal({
					backdrop:'static'
				});
			});
			
			// Resubmit sitemap button
			$('a[data-role=sitemapresubmit]').on('click', function (jqEvent) {
				sitemapUrl = $(this).data('url');
				
				// Change the task and submit miniform uploader
				$('#sitemaplink').val(sitemapUrl);
				$('#adminForm input[name=task]').val('google.submitSitemap');
				$('#adminForm').trigger('submit');
			});
		}).call(this);
	};

	// On DOM Ready
	$(function() {
		window.JMapWebmastersTools = new WebmastersTools();
	});
})(jQuery);