<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
$open = !$links ? 'target="_blank"' : '';
$total = count($events);
$panes = ceil($total / $nr_events);
$inactive = $nr_events - $total % $nr_events;
$dates = modRseventsProSlider::getTimeline($events,$nr_events); ?>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#rs_events_slider_timeline<?php echo $module->id; ?>').rseproslider({
		'events': <?php echo $nr_events; ?>,
		'duration': <?php echo $tduration; ?>,
		'width' : <?php echo $width; ?>,
		'height' : <?php echo $height; ?>
	});
});
</script>

<div class="rs_eventsslider_timeline<?php echo $suffix; ?>" id="rs_eventsslider_timeline<?php echo $module->id; ?>">
	<?php if ($pretext) { ?><div class="rse_pretext"><?php echo $pretext; ?></div><?php } ?>
	<div class="rs_events_slider_timeline_container" id="rs_events_slider_timeline_container<?php echo $module->id; ?>">
		<div class="rs_events_slider_timeline" id="rs_events_slider_timeline<?php echo $module->id; ?>">
				<ul class="rs_events_slider_timeline_events">
					<?php foreach ($events as $event) { ?>
					<li>
						<div class="rs_events_slider_timeline_element">
							<?php if ($date) { ?>
							<span class="rse_date">
								<?php echo rseventsproHelper::showdate($event->start,'M j Y'); ?>
							</span>
							<?php } ?>
							<?php if ($title) { ?>
								<a class="rse_name" <?php echo $open; ?> href="<?php echo rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),true,$itemid); ?>"><?php echo $event->name; ?></a>
							<?php } ?>
							<span class="rse_descr"><?php echo substr(strip_tags($event->description),0,$length); ?></span>
						</div>
					</li>
					<?php } ?>
					<?php if ($total % $nr_events) { for ($i=0;$i<$inactive;$i++) { ?><li class="inactive"></li><?php } } ?>
				</ul>
		</div>
		<?php if ($panes > 1) { ?>
		<div class="resprobar_container">
			<div id="rseprobar<?php echo $module->id; ?>" class="rseprobar">
				<div class="rsepropanes">
					<?php for($i = 0; $i < $panes; $i++) { ?>
					<div class="rsepropane<?php if ($i == 0) echo ' active'; ?>"><span></span></div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="rseprodates" id="rsepro_dates<?php echo $module->id; ?>">
			<?php foreach ($dates as $i => $date) { ?>
				<div class="rseprodate<?php if ($i == 0) echo ' active'; ?>"><?php echo $date['start']; ?> - <?php echo $date['end']; ?></div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<?php if ($posttext) { ?><div class="rse_posttext"><?php echo $posttext; ?></div><?php } ?>
</div>