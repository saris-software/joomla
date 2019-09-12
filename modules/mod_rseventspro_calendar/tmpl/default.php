<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div id="rs_calendar_module<?php echo $module->id; ?>" class="rs_calendar_module<?php echo $calendar->class_suffix; ?>">

	<table cellpadding="0" cellspacing="2" border="0" width="100%" class="rs_table" style="width:100%;">
		<tr>
			<td align="left">
				<a rel="nofollow" href="javascript:void(0);" onclick="rs_calendar('<?php echo JURI::root(true); ?>/','<?php echo $calendar->getPrevMonth(); ?>','<?php echo $calendar->getPrevYear(); ?>','<?php echo $module->id; ?>')" class="rs_calendar_arrows_module" id="rs_calendar_arrow_left_module">&laquo;</a>
			</td>
			<td align="center">
				<?php $current = JFactory::getDate($calendar->unixdate); ?>
				<span id="rscalendarmonth<?php echo $module->id; ?>"><?php echo $calendar->months[$calendar->cmonth].' '.$current->format('Y'); ?></span>
				<?php echo JHtml::image('com_rseventspro/loader.gif', '', array('id' => 'rscalendar'.$module->id, 'style' => 'vertical-align: middle; display: none;'), true); ?> 
			</td>
			<td align="right">
				<a rel="nofollow" href="javascript:void(0);" onclick="rs_calendar('<?php echo JURI::root(true); ?>/','<?php echo $calendar->getNextMonth(); ?>','<?php echo $calendar->getNextYear(); ?>','<?php echo $module->id; ?>')" class="rs_calendar_arrows_module" id="rs_calendar_arrow_right_module">&raquo;</a>
			</td>
		</tr>
	</table>

	<div class="rs_clear"></div>
	
	<table class="rs_calendar_module rs_table" cellpadding="0" cellspacing="0" width="100%">
		<thead>
			<tr>
				<?php foreach ($calendar->days->weekdays as $weekday) { ?>
				<th><?php echo $weekday; ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($calendar->days->days as $day) { ?>
		<?php $unixdate = JFactory::getDate($day->unixdate); ?>
		<?php if ($day->day == $calendar->weekstart) { ?>
			<tr>
		<?php } ?>
				<td class="<?php echo $day->class; ?>">
					<a <?php echo $nofollow; ?> href="<?php echo rseventsproHelper::route('index.php?option=com_rseventspro&view=calendar&layout=day&date='.$unixdate->format('m-d-Y').'&mid='.$module->id,true,$itemid);?>" class="<?php echo rseventsproHelper::tooltipClass(); ?>" title="<?php echo rseventsproHelper::tooltipText(modRseventsProCalendar::getDetailsSmall($day->events)); ?>">
						<span class="rs_calendar_date"><?php echo $unixdate->format('j'); ?></span>
					</a>
				</td>
			<?php if ($day->day == $calendar->weekend) { ?></tr><?php } ?>
			<?php } ?>
		</tbody>
	</table>
	
</div>
<div class="rs_clear"></div>