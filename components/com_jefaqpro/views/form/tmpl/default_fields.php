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

require_once (JPATH_COMPONENT.'/captcha/captcha.php');
?>


<?php
if( $this->user->get('id') == 0 ) {
?>
	<fieldset>
		<legend><?php echo JText::_('COM_JEFAQPRO_USER'); ?></legend>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('posted_by'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('posted_by');	?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('posted_email'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('posted_email');	?>
			</div>
		</div>
	</fieldset>
<?php
}
?>
<fieldset>
	<legend><?php echo JText::_('JEDITOR'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('questions'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('questions');	?>
		</div>
	</div>

	<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('catid'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('catid');	?>
			</div>
		</div>

	<?php if ( $this->item->params->get('access-edit') != '' ) { ?>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('answers'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('answers');	?>
		</div>
	</div>

	<?php }
	if( $this->params->get('captcha_show', 1) ) { ?>

	<div class="control-group">
		<div class="control-label">
			<label title="" class="hasTip" for="jform_captcha" id="jform_captcha-lbl">
			<?php echo JText::_('JEFAQPRO_CAPTCHA')." :"; echo '<span style="color:#CC0000;">*</span>';?>
			</label>
		</div>
		<div class="controls">
			<?php echo(AutarticaptchaHelper::generateInputTags());?>
			<?php echo(AutarticaptchaHelper::generateImgTags(JURI::Base()."components/com_jefaqpro/captcha/"));?>
			<?php echo(AutarticaptchaHelper::generateHiddenTags());?>
		</div>
	</div>
	<?php } ?>
</fieldset>

<?php if ( $this->item->params->get('access-edit') != '' ) { ?>
<fieldset>
	<legend><?php echo JText::_('COM_JEFAQPRO_PUBLISHING'); ?></legend>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('published'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('published');	?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('access'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('access');	?>
			</div>
		</div>
</fieldset>
<?php } ?>

<?php if ( $this->item->params->get('access-edit') != '' ) { ?>
<fieldset>
	<legend><?php echo JText::_('JFIELD_LANGUAGE_LABEL'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('language'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('language');	?>
		</div>
	</div>
</fieldset>

<?php } ?>

<fieldset>
	<div class="control-group">
		<div class="control-label">

		</div>
		<div class="controls">
			<button type="button" class="btn btn-small btn-success" onclick="Joomla.submitbutton('faq.save')">
			<?php echo JText::_('JSAVE') ?>
			</button>
			<button type="button" class="btn btn-small" onclick="Joomla.submitbutton('faq.cancel')">
				<?php echo JText::_('JCANCEL') ?>
			</button>
		</div>
	</div>

</fieldset>

