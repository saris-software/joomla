<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/


// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.calendar');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'faq.cancel' || document.formvalidator.isValid(document.id('faq-form'))) {
			Joomla.submitform(task, document.getElementById('faq-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}

	}
</script>
<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
	<?php echo $this->loadTemplate('title'); ?>


	<dl id="system-message" style="display : none">
		<dd class="warning message">
			<ul>
				<li><div id="je-error-message"></div></li>
			</ul>
		</dd>
	</dl>

	<form action="<?php echo JRoute::_('index.php?option=com_jefaqpro&a_id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="faq-form" class="form-validate form-horizontal">
		<?php echo $this->loadTemplate('fields'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
		<input type="hidden" name="je-errorwarning-message" id="je-errorwarning-message" value="<?php echo JText::_('COM_JEFAQPRO_VALIDATION_FORM_FAILED'); ?>"/>

		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>

<?php
if($this->params->get('show_footertext')) {
?>
	<p class="copyright" style="text-align : right; font-size : 10px;">
		<?php require_once( JPATH_COMPONENT .'/copyright/copyright.php' ); ?>
	</p>
<?php
}
?>