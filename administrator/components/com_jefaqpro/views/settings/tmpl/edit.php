<?php
/**
 * @version		$Id: edit.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Administrator
 * @subpackage	com_admin
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
?>
<form action="<?php echo JRoute::_('index.php?option=com_jefaqpro&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	<fieldset>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#displayset" data-toggle="tab"><?php echo JText::_('COM_JEFAQPRO_FAQ_SETTINGS');?></a></li>
			<li><a href="#orderingset" data-toggle="tab"><?php echo JText::_('COM_JEFAQPRO_FAQ_ORDERSETTINGS');?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="displayset">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('date_format'); ?>
					</div>
					<div class="controls">
						<?php
							echo $this->form->getInput('date_format');
							$date	= JFactory::getDate();
							echo "&nbsp;&nbsp;<strong>(". $date->format( $this->item->date_format ) .")</strong>";
						?>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('theme'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('theme'); ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label id="jform_id-lbl" class="hasTip" title="<?php echo JText::_('COM_JEFAQPRO_THEMEID_LABEL').'::'.JText::_('COM_JEFAQPRO_THEMEID_LABEL_DESC'); ?>" for="jform_theme_id"><?php echo JText::_('COM_JEFAQPRO_THEMEID_LABEL') ?></label>
					</div>
					<div class="controls">
						<input id="jform_theme_id" class="readonly" type="text" value="<?php echo $this->item->theme; ?>" readonly="readonly">
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<?php echo JText::_('COM_JEFAQPRO_THEMEPREVIEW_LABEL'); ?>
					</div>
					<div class="controls">
						<div id="je-themepreview">
							<?php echo JHTML::_('image','administrator/components/com_jefaqpro/assets/images/preview/'.$this->item->theme.'.jpg', JText::_('COM_JEFAQPRO_STYLE').$this->item->theme, '', false)?>
						</div>
					</div>
				</div>
			</div>

			<div class="tab-pane" id="orderingset">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('orderby'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('orderby'); ?>
					</div>

					<div class="control-label">
						<?php echo $this->form->getLabel('sortby'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('sortby'); ?>
					</div>
				</div>
			</div>
		</div>
	</fieldset>

	<input type="hidden" name="theme_path" id="theme_path" value="<?php echo JURI::base(); ?>"/>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>

<p class="copyright" align="center">
	<?php require_once( JPATH_COMPONENT .'/copyright/copyright.php' ); ?>
</p>