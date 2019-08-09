<?php
/**
 * @package         Advanced Module Manager
 * @version         7.1.4
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2017 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.popover');
?>

<h2><?php echo JText::_('COM_MODULES_TYPE_CHOOSE') ?></h2>
<ul id="new-modules-list" class="list list-striped">
	<?php foreach ($this->items as &$item) : ?>
		<?php
		// Prepare variables for the link.

		$link       = 'index.php?option=com_advancedmodules&task=module.add&eid=' . $item->extension_id;
		$name       = $this->escape($item->name);
		$desc       = JHtml::_('string.truncate', ($this->escape($item->desc)), 200);
		$short_desc = JHtml::_('string.truncate', ($this->escape($item->desc)), 90);
		?>
		<?php if (JFactory::getDocument()->direction != "rtl") : ?>
			<li>
				<a href="<?php echo JRoute::_($link); ?>">
					<strong><?php echo $name; ?></strong>
				</a>
				<small class="hasPopover" data-placement="right" title="<?php echo $name; ?>"
				       data-content="<?php echo $desc; ?>"><?php echo $short_desc; ?></small>
			</li>
		<?php else : ?>
			<li>
				<small rel="popover" data-placement="left" title="<?php echo $name; ?>" data-content="<?php echo $desc; ?>"><?php echo $short_desc; ?></small>
				<a href="<?php echo JRoute::_($link); ?>">
					<strong><?php echo $name; ?></strong>
				</a>
			</li>
		<?php endif ?>
	<?php endforeach; ?>
</ul>
<div class="clr"></div>
