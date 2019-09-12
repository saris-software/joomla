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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');


$JRred  	  = @JRequest::getVar('redirect');
if($JRred) {
	$url 	  = JRoute::_('index.php?option=com_jefaqpro&task=import.importstart&redirect='.$JRred.'&importtasks='.JRequest::getVar('importtasks'), false);
//	echo $url; exit;
	echo '<meta http-equiv="refresh" content="1; url='.$url.'">';
}
?>

<script>
Joomla.submitbutton = function(task)
{
	var isValid = false;
	var extValid = false;
	if (task == '') {
		return false;
	} else {
		var docs = document.getElementsByName("jefaqpro_imports[]");
		for(var i = 0;i < docs.length;i++) {
			var doc		 = docs[i];
			var doc_name = doc.value;
			if( doc_name != "") {
				isValid = true;
				var ext = doc_name.split('.');
				if(ext[1] == "sql") {
					extValid = true;
					doc.style.border="1px solid #C0C0C0";
				} else {
					extValid = false;
					doc.style.border="1px solid #FF0000";
					break;
				}
			}
		}

		if(!isValid)
			alert("<?php echo JText::_('COM_JEFAQPRO_SELECT_FILE_FIRST'); ?>");

		if (isValid && extValid) {
			Joomla.submitform(task, document.getElementById('import-form'));
			return true;
		} else {
			return false;
		}
	}
}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_jefaqpro&view=import'); ?>" method="post" name="adminForm" id="import-form" class="form-validate form-horizontal" enctype="multipart/form-data">
	<fieldset class="adminform">
		<legend>
			<?php echo JText::_("COM_JEFAQPRO_IMPORT_FROM"); ?>
		</legend>
		<div class="tab-content">
			<div class="tab-pane active" id="displayset">
				<div class="control-group">
					<div class="control-label">
						<label id="jefaqpro_cat-lbl" class="hasTip" title="<?php echo JText::_("COM_JEFAQPRO_FIELD_CATEGORY_TABLE")." :: Table Name : jos_je_faq_category"; ?>" for="jefaqpro_cat"><?php echo JText::_("COM_JEFAQPRO_FIELD_CATEGORY_TABLE"); ?></label>
					</div>
					<div class="controls">
						<input type="file" id="jefaqpro_cat" name="jefaqpro_imports[]" value=""/>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<label id="jefaqpro_faq-lbl" class="hasTip" title="<?php echo JText::_("COM_JEFAQPRO_FIELD_FAQ_TABLE").":: Table Name : jos_je_faq"; ?>" for="jefaqpro_faq"><?php echo JText::_("COM_JEFAQPRO_FIELD_FAQ_TABLE"); ?></label>
					</div>
					<div class="controls">
						<input type="file" id="jefaqpro_faq" name="jefaqpro_imports[]" value=""/>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<label id="jefaqpro_res-lbl" class="hasTip" title="<?php echo JText::_("COM_JEFAQPRO_FIELD_RES_TABLE").":: Table Name : jos_je_faq_responses"; ?>" for="jefaqpro_res"><?php echo JText::_("COM_JEFAQPRO_FIELD_RES_TABLE"); ?></label>
					</div>
					<div class="controls">
						<input type="file" id="jefaqpro_res" name="jefaqpro_imports[]" value=""/>
					</div>
				</div>
				<div class="control-group">
					<div class="controls" style="font-weight:bold">
						<?php echo JText::_("COM_JEFAQPRO_IMPORT_NOTES"); ?>
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<div class="clr"></div>

<p class="copyright" align="center">
	<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
</p>