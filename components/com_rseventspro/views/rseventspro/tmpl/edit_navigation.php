<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<ul id="rsepro-edit-menu" class="nav nav-tabs">
	<li><a href="javascript:void(0);" data-toggle="modal" data-target="#rsepro-edit-event-photo" class="center" onclick="rsepro_reset_frame();"><?php echo $this->loadTemplate('icon'); ?></a></li>
	
	<?php if ($this->item->completed) { ?>
	<li><a href="javascript:void(0);" data-target="#rsepro-edit-tabd" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_DASHBOARD'); ?> <span class="fa fa-tachometer"></span></a></li>
	<?php } ?>
	
	<li class="<?php if (!$this->tab) echo 'active'; ?>"><a href="javascript:void(0);" data-target="#rsepro-edit-tab1" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_CREATE'); ?> <span class="fa fa-flag"></span></a></li>
	
	<li><a href="javascript:void(0);" data-target="#rsepro-edit-tab2" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_CATEGORIES'); ?> <span class="fa fa-tag"></span></a></li>
	
	<li class="rsepro-hide"<?php echo $this->item->rsvp ? ' style="display:block;"' : ''; ?>><a href="javascript:void(0);" data-target="#rsepro-edit-tabrsvp" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_RSVP'); ?>  <span class="fa fa-calendar"></span></a></li>
	
	<li class="rsepro-hide"<?php echo $this->item->registration ? ' style="display:block;"' : ''; ?>><a href="javascript:void(0);" data-target="#rsepro-edit-tab3" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_REGISTRATION'); ?>  <span class="fa fa-calendar"></span></a></li>
	
	<li class="rsepro-hide"<?php echo $this->item->registration ? ' style="display:block;"' : ''; ?>><a href="javascript:void(0);" data-target="#rsepro-edit-tab4" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_NEWTICKET'); ?> <span class="fa fa-plus-circle"></span></a></li>
	
	<?php if ($this->tickets) { ?>
	<?php foreach ($this->tickets as $ticket) { ?>
	<li class="rsepro-ticket rsepro-hide"<?php echo $this->item->registration ? ' style="display:block;"' : ''; ?> id="ticket_<?php echo $ticket->id; ?>"><a href="javascript:void(0);" data-target="#rsepro-edit-ticket<?php echo $ticket->id; ?>" data-toggle="tab"><?php echo $ticket->name; ?> <span class="fa fa-ticket"></span></a></li>
	<?php }} ?>
	
	<?php JFactory::getApplication()->triggerEvent('rsepro_addMenuOptionRegistration'); ?>
	
	<li class="rsepro-hide"<?php echo $this->item->discounts && $this->item->registration ? ' style="display:block;"' : ''; ?>><a href="javascript:void(0);" data-target="#rsepro-edit-tab6" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_DISCOUNTS'); ?> <span class="fa fa-scissors"></span></a></li>
	
	<li class="rsepro-hide"<?php echo $this->item->discounts && $this->item->registration ? ' style="display:block;"' : ''; ?>><a href="javascript:void(0);" data-target="#rsepro-edit-tab7" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_NEWCOUPON'); ?> <span class="fa fa-plus-circle"></span></a></li>
	
	<?php if ($this->coupons) { ?>
	<?php foreach ($this->coupons as $coupon) { ?>
	<li class="rsepro-hide"<?php echo $this->item->discounts && $this->item->registration ? ' style="display:block;"' : ''; ?>><a href="javascript:void(0);" data-target="#rsepro-edit-coupon<?php echo $coupon->id; ?>" data-toggle="tab"><?php echo $coupon->name; ?></a></li>
	<?php }} ?>
	
	<?php if (empty($this->item->parent) && (!empty($this->permissions['can_repeat_events']) || $this->admin)) { ?>
	<li class="rsepro-hide"<?php echo $this->item->recurring ? ' style="display:block;"' : ''; ?>><a href="javascript:void(0);" data-target="#rsepro-edit-tab8" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_RECURRING'); ?> <span class="fa fa-repeat"></span></a></li>
	<?php } ?>
	
	<?php if (!empty($this->permissions['can_upload']) || $this->admin) { ?>
	<li><a href="javascript:void(0);" data-target="#rsepro-edit-tab9" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_FILES'); ?> <span class="fa fa-file-o"></span></a></li>
	<?php } ?>
	
	<li><a href="javascript:void(0);" data-target="#rsepro-edit-tab10" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_CONTACT'); ?> <span class="fa fa-user"></span></a></li>
	
	<li><a href="javascript:void(0);" data-target="#rsepro-edit-tab11" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_META'); ?> <span class="fa fa-list"></span></a></li>
	
	<?php if (!empty($this->permissions['can_change_options']) || $this->admin) { ?>
	<li><a href="javascript:void(0);" data-target="#rsepro-edit-tab12" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_FRONTEND'); ?> <span class="fa fa-home"></span></a></li>
	<?php } ?>
	
	<?php if (rseventsproHelper::isGallery()) { ?>
	<li><a href="javascript:void(0);" data-target="#rsepro-edit-tab13" data-toggle="tab"><?php echo JText::_('COM_RSEVENTSPRO_EVENT_TAB_GALLERY'); ?> <span class="fa fa-picture-o"></span></a></li>
	<?php } ?>
	
	<?php JFactory::getApplication()->triggerEvent('rsepro_addMenuOption'); ?>
</ul>