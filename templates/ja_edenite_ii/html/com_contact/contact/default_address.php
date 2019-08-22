<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * marker_class: Class based on the selection of text, none, or icons
 * jicon-text, jicon-none, jicon-icon
 */
?>
<div class="contact-address dl-horizontal">
	<?php if (($this->params->get('address_check') > 0) &&
		($this->contact->address || $this->contact->suburb  || $this->contact->state || $this->contact->country || $this->contact->postcode)) : ?>
		<div class="contact-info">
			<?php if ($this->params->get('address_check') > 0) : ?>
			
				<span class="<?php echo $this->params->get('marker_class'); ?>" >
					<span class="icon-home"></span>
					<!-- <?php echo $this->params->get('marker_address'); ?> -->
					Address:&nbsp;
				</span>
			<?php endif; ?>

			<?php if ($this->contact->address && $this->params->get('show_street_address')) : ?>
				<span class="contact-street">
					<?php echo $this->contact->address .'<br/>'; ?>
				</span>
			<?php endif; ?>
		</div>
		<div class="contact-info">
			<?php if ($this->contact->suburb && $this->params->get('show_suburb')) : ?>
				<span class="contact-suburb">
					<span class="non-icons">City or Suburb:&nbsp;</span> <?php echo $this->contact->suburb .'<br/>'; ?>
				</span>
			<?php endif; ?>
			<?php if ($this->contact->state && $this->params->get('show_state')) : ?>
				<span class="contact-state">
					<span class="non-icons">State or County:&nbsp;</span> <?php echo $this->contact->state . '<br/>'; ?>
				</span>
			<?php endif; ?>
		</div>
		<div class="contact-info">
			<?php if ($this->contact->postcode && $this->params->get('show_postcode')) : ?>
				<span class="contact-postcode">
					<span class="non-icons">Postal Code:&nbsp;</span> <?php echo $this->contact->postcode .'<br/>'; ?>
				</span>
			<?php endif; ?>
			<?php if ($this->contact->country && $this->params->get('show_country')) : ?>
				<span class="contact-country">
					<span class="non-icons">Country:&nbsp;</span> <?php echo $this->contact->country .'<br/>'; ?>
				</span>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	
	<?php if ($this->contact->mobile && $this->params->get('show_mobile')) :?>
		<div class="contact-info">
			<span class="<?php echo $this->params->get('marker_class'); ?>" >
				<span class="icon-mobile"></span>
				<!-- <?php echo $this->params->get('marker_mobile'); ?> -->
				Mobile:&nbsp;
			</span>
			<span class="contact-mobile">
				<?php echo nl2br($this->contact->mobile); ?>
			</span>
		</div>
	<?php endif; ?>

	<?php if ($this->contact->telephone && $this->params->get('show_telephone')) : ?>
		<div class="contact-info">
			<span class="<?php echo $this->params->get('marker_class'); ?>" >
				<span class="icon-phone"></span>
				<!-- <?php echo $this->params->get('marker_telephone'); ?> -->
				Phone:&nbsp;
			</span>
			<span class="contact-telephone">
				<?php echo nl2br($this->contact->telephone); ?>
			</span>
		</div>
	<?php endif; ?>
	
	<?php if ($this->contact->fax && $this->params->get('show_fax')) : ?>
		<div class="contact-info">
			<span class="<?php echo $this->params->get('marker_class'); ?>" >
				<span class="icon-print"></span>
				<!-- <?php echo $this->params->get('marker_fax'); ?> -->
				Fax:&nbsp;
			</span>
			<span class="contact-fax">
			<?php echo nl2br($this->contact->fax); ?>
			</span>
		</div>
	<?php endif; ?>
	
	<?php if ($this->contact->email_to && $this->params->get('show_email')) : ?>
		<div class="contact-info">
			<span class="<?php echo $this->params->get('marker_class'); ?>" >
				<span class="icon-envelope"></span>
				<!-- <?php echo nl2br($this->params->get('marker_email')); ?> -->
				Email:&nbsp;
			</span>
			<span class="contact-emailto">
				<?php echo $this->contact->email_to; ?>
			</span>
		</div>
	<?php endif; ?>
	
	<?php if ($this->contact->webpage && $this->params->get('show_webpage')) : ?>
		<div class="contact-info">
			<span class="<?php echo $this->params->get('marker_class'); ?>" >
				<span class="icon-globe"></span>
				Webpage:&nbsp;
			</span>
			<span class="contact-webpage">
				<a href="<?php echo $this->contact->webpage; ?>" target="_blank">
				<?php echo $this->contact->webpage; ?></a>
			</span>
		</div>
	<?php endif; ?>
</div>
