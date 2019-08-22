<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 
$open = !$links ? 'target="_blank"' : ''; ?>

<ul class="rsepro_events_module<?php echo $suffix; ?>">
	<?php foreach ($events as $eventid) { ?>
	<?php $details = rseventsproHelper::details($eventid->id); ?>
	<?php $image = !empty($details['image_s']) ? $details['image_s'] : rseventsproHelper::defaultImage(); ?>
	
	<?php if (isset($details['event']) && !empty($details['event'])) $event = $details['event']; else continue; ?>
	<li>
		<div class="rsepro-image">
			<img src="<?php echo $image; ?>" alt="" width="70" />
		</div>
		
		<a <?php echo $open; ?> href="<?php echo rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),true,$itemid); ?>"><?php echo $event->name; ?></a> 
		<br />
		<small>(<?php echo $event->allday ? rseventsproHelper::date($event->start,rseventsproHelper::getConfig('global_date'),true) : rseventsproHelper::date($event->start,null,true); ?>)</small>
	</li>
	<?php } ?>
</ul>
<div class="clearfix"></div>