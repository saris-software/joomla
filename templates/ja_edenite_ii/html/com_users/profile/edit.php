<?php
/*
 * ------------------------------------------------------------------------
 * JA Edenite II Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
if(version_compare(JVERSION, '3.0', 'ge')){
	JHtml::_('bootstrap.tooltip');
	JHtml::_('formbehavior.chosen', 'select');
} else {
	JHtml::_('behavior.tooltip');
}
JHtml::_('behavior.formvalidation');

//load user_profile plugin language
$lang = JFactory::getLanguage();
$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);
?>
<div class="profile-edit<?php echo $this->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	</div>
<?php endif; ?>

<form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
<?php foreach ($this->form->getFieldsets() as $group => $fieldset):// Iterate through the form fieldsets and display each one.?>
	<?php $fields = $this->form->getFieldset($group);?>
	<?php if (count($fields)):?>
	<fieldset class="profile-<?php echo $fieldset->name; ?>">
		<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
		<legend><span><?php echo JText::_($fieldset->label); ?><span></legend>
		<?php endif;?>
		<div class="form-group">
			<?php foreach ($fields as $field):// Iterate through the fields in the set and display them.?>
				<?php if ($field->hidden):// If the field is hidden, just display the input.?>

				<div class="col-sm-12 controls">
					<?php echo $field->input;?>
				</div>

				<?php else:?>
					<div class="col-md-6">
						<div class="form-group">
							<div class="control-label">
								<?php echo $field->label; ?>
								<?php if (!$field->required && $field->type != 'Spacer') : ?>
								<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
								<?php endif; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					</div>
				<?php endif;?>
			<?php endforeach;?>
		</div>
	</fieldset>
	<?php endif;?>
<?php endforeach;?>

	<div class="form-group form-actions">
		<div class="col-sm-12">
			<button type="submit" class="btn btn-primary validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
			<a class="btn btn-default" href="<?php echo JRoute::_(''); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="profile.save" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>

	</form>
</div>
