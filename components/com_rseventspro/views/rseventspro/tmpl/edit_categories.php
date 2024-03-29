<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<legend><?php echo JText::_('COM_RSEVENTSPRO_EVENT_CATEGORIES'); ?></legend>

<div class="control-group">
	<div class="controls">
		<select name="categories[]" id="categories" multiple="multiple" class="rsepro-chosen">
			<?php echo JHtml::_('select.options', $this->categories,'value','text',$this->eventClass->getCategories()); ?>
		</select>
		 <?php if (!empty($this->permissions['can_create_categories']) || $this->admin) { ?>
		<a href="#rsepro-add-new-categ" data-toggle="modal" class="btn" type="button"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_ADD_CATEGORY'); ?></a>
		<?php } ?>
	</div>
</div>

<div class="clearfix"></div>
<legend><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAGS'); ?></legend>

<div class="control-group">
	<div class="controls">
		<?php echo JHtml::_('rseventspro.tags', '#rsepro_tags'); ?>
		<select id="rsepro_tags" name="tags[]" class="rsepro-chosen" multiple="multiple" style="width: 350px;">
			<?php echo JHtml::_('select.options', $this->eventClass->getTags(true), 'value', 'text', $this->eventClass->getTags(true)); ?>
		</select>
	</div>
</div>

<div class="form-actions">
	<button class="btn btn-success rsepro-event-save" type="button"><?php echo JText::_('COM_RSEVENTSPRO_SAVE_EVENT'); ?></button>
	<button class="btn btn-success rsepro-event-update" type="button"><?php echo JText::_('COM_RSEVENTSPRO_UPDATE_EVENT'); ?></button>
	<button class="btn btn-danger rsepro-event-cancel" type="button"><?php echo JText::_('COM_RSEVENTSPRO_GLOBAL_CANCEL'); ?></button>
</div>