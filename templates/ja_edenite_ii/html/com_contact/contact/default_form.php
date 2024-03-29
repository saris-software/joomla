<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

if (isset($this->error)) : ?>
	<div class="contact-error">
		<?php echo $this->error; ?>
	</div>
<?php endif; ?>

<div class="contact-form">
	<form id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate form-horizontal">
		<fieldset>
			<legend><?php echo JText::_('COM_CONTACT_FORM_LABEL'); ?></legend>
			<div class="control-group-header">
				<div class="control-contact">
					<?php echo $this->form->getLabel('contact_name'); ?>
					<?php echo $this->form->getInput('contact_name'); ?>
				</div>
				<div class="control-contact">
					<?php echo $this->form->getLabel('contact_subject'); ?>
					<?php echo $this->form->getInput('contact_subject'); ?>
				</div>
				<div class="control-contact">
					<?php echo $this->form->getLabel('contact_email'); ?>
					<?php echo $this->form->getInput('contact_email'); ?>
				</div>					
				<div class="control-contact">
					<?php echo $this->form->getLabel('contact_message'); ?>
					<?php echo $this->form->getInput('contact_message'); ?>
				</div>		
			</div>
			<?php if ($this->params->get('show_email_copy')) { ?>			
					<div class="control-group">
						<?php echo $this->form->getInput('contact_email_copy'); ?>
						<?php echo $this->form->getLabel('contact_email_copy'); ?>
					</div>
				<?php } ?>
			<div class="re-captcha">
			<?php //Dynamically load any additional fields from plugins. ?>
			<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
				<?php if ($fieldset->name != 'contact'):?>
					<?php if ($fieldset->name === 'captcha' && !$this->captchaEnabled) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<?php $fields = $this->form->getFieldset($fieldset->name); ?>
					<?php if (count($fields)) : ?>
						<fieldset>
							<?php if (isset($fieldset->label) && ($legend = trim(JText::_($fieldset->label))) !== '') : ?>
								<legend><?php echo $legend; ?></legend>
							<?php endif; ?>
							<?php foreach ($fields as $field) : ?>
								<?php echo $field->renderField(); ?>
							<?php endforeach; ?>
						</fieldset>
					<?php endif; ?>
				<?php endif ?>
			<?php endforeach;?>
			</div>
			<div class="control-group-footer clearfix">
				
				<div class="control-group">
					<button class="btn btn-primary validate" type="submit"><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></button>
					<input type="hidden" name="option" value="com_contact" />
					<input type="hidden" name="task" value="contact.submit" />
					<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
					<input type="hidden" name="id" value="<?php echo $this->contact->slug; ?>" />
					<?php echo JHtml::_('form.token'); ?>
				</div>
			</div>
		</fieldset>
	</form>
</div>
