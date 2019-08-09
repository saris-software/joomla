<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2011 - 2012 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$path = JURI::root();
?>
<form method="post" name="adminForm" id="contact-form" class="form-validate" enctype="multipart/form-data">

	<ul class="adminformlist">
		


		<li>
			<label id="jefaqpro_faq-lbl" class="hasTip" title="<?php echo JText::_("COM_JEFAQPRO_FIELD_CSV_FAQ"); ?>"><?php echo JText::_("COM_JEFAQPRO_FIELD_CSV_FAQ"); ?></label>
			<input type="file" id="jefaqpro_faq" name="uploadedfile" value="" />
			<input type="submit"  value="Import FAQ" onclick="Joomla.submit1('importcsv.importcsvfaqs')" />
		</li>
		<input type="hidden" name="option" value="com_jefaqpro" />
<input type="hidden" name="task" value="importcsv.importcsvfaqs" />

	</ul>
	<?php echo JHtml::_('form.token'); ?>
</form>

<div class="clr"></div>




<p class="copyright" align="center">
	<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
</p>