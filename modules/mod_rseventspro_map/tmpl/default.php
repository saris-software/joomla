<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 
$locations = count($events); ?>

<script type="text/javascript">
var rsepromapmodule<?php echo $module->id; ?>;
jQuery(document).ready(function() {
	rsepromapmodule<?php echo $module->id; ?> = jQuery('#rs_canvas<?php echo $module->id; ?>').rsjoomlamap({
		zoom: <?php echo (int) $config->google_map_zoom ?>,
		center: '<?php echo $config->google_maps_center; ?>',
		markerDraggable: false,
		markers: [
			<?php 
				if ($locations) {
					$i = 0;
					foreach ($events as $location => $events) {
						if (empty($events)) continue;
						$event = $events[0];
						if (empty($event->coordinates) && empty($event->address)) continue;
						$single = count($events) > 1 ? false : true;
			?>
			{
				title : '<?php echo addslashes($event->name); ?>',
				position: '<?php echo modRseventsProMap::escape($event->coordinates); ?>',
				<?php if ($event->marker) echo "icon : '".addslashes(rseventsproHelper::showMarker($event->marker))."',\n"; ?>
				address: '<?php echo addslashes($event->address); ?>',
				content: '<?php echo rseventsproHelper::locationContent($event, $single, $itemid); ?>'
			}
			
			<?php 
				$i++;
				if ($locations > $i) echo ','; 
			?>
			
			<?php 
					}
				}
			?>
		]
	});
});
</script>

<div id="rs_canvas<?php echo $module->id; ?>" class="rs_module_map<?php echo $params->get('moduleclass_sfx'); ?>" style="width: <?php echo modRseventsProMap::escape($width); ?>; height: <?php echo modRseventsProMap::escape($height); ?>; margin: 5px;"></div>