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
<form action="<?php echo JRoute::_('index.php?option=com_jefaqpro&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="faq-form" class="form-validate form-horizontal">
	<fieldset>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#addques" data-toggle="tab"><?php echo empty($this->item->id) ? JText::_('COM_JEFAQPRO_NEW_FAQ') : JText::sprintf('COM_JEFAQPRO_EDIT_FAQ', $this->item->id); ?></a></li>
			<li><a href="#optionset" data-toggle="tab"><?php echo empty($this->item->id) ? JText::_('COM_JEFAQPRO_NEW_FAQ_OPTIONS') : JText::sprintf('COM_JEFAQPRO_EDIT_FAQ_OPTIONS', $this->item->id); ?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="addques">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('id');	?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('catid'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('catid'); ?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('questions'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('questions'); ?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('answers'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('answers'); ?>
					</div>
				</div>
			</div>

			<div class="tab-pane" id="optionset">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('published'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('published'); ?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('access'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('access'); ?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('language'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('language'); ?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('ordering'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('ordering'); ?>
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<p class="copyright" align="center">
	<?php require_once( JPATH_COMPONENT . '/copyright/copyright.php' ); ?>
</p>