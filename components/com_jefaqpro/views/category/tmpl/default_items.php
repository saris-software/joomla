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

JHtmlBehavior::framework();

$n = count($this->items);
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$id   		= JRequest::getVar('id');
$itemid   	= JRequest::getVar('Itemid');

$link	= JRoute::_('index.php?option=com_jefaqpro&view=categories&Itemid='.jefaqproHelperRoute::getBacktoRoute());
$form	= JRoute::_('index.php?option=com_jefaqpro&view=form&layout=edit&catid='.$id.'&Itemid='.jefaqproHelperRoute::getaddFormRoute());


if (empty($this->items)) {
?>
	<p>
		<?php echo JText::_('COM_JEFAQPOR_ERROR_FAQS_NOT_FOUND'); ?>
	</p>
<?php
}
else {
?>

<div id="je-faqpro">
	<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
		<?php

			if ( $this->params->get('show_onlyregusers', 1) && $this->params->get('add_votes', 1)) {
				if ( $this->allowed == '1' && $this->user->get('id') >0 ) {
		?>
				<div id="je-newbutton">
					<div style="text-align : right">
						<a id="je-addbutton" href="<?php echo $form; ?>" title="<?php echo JText::_('JE_ADDNEW'); ?>" > <strong> <?php echo JText::_('JE_ADDNEW'); ?> </strong> </a>
					</div>
				</div>
				<br/><br/><br/>

		<?php
			}
		}  else {
				if ($this->allowed == '1' ) {
			?>
					<div id="je-newbutton">
						<div style="text-align : right">
							<a id="je-addbutton" href="<?php echo $form; ?>" title="<?php echo JText::_('JE_ADDNEW'); ?>" > <strong> <?php echo JText::_('JE_ADDNEW'); ?> </strong> </a>
						</div>
					</div>
					<br/><br/><br/>
			<?php
				}
			}
			echo $this->loadTemplate('faqs');

			if($id > 0 && $this->params->get('show_backto_cat', 1)){ ?>
				<div id="je-backbutton">
					<a id="je-button" href="<?php echo $link; ?>" title="<?php echo JText::_('JE_BACK'); ?>" > <strong> <?php echo JText::_('JE_BACK'); ?> </strong> </a>
				</div><br/><br/>
		<?php  }
			if($this->params->get('show_pagination',1))
			echo $this->loadTemplate('faqspagination');
		?>

		<div>
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<input type="hidden" name="limitstart" value="" />
		</div>
	</form>
</div>
<?php
}
?>