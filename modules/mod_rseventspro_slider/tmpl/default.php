<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 
$item_align = $params->get('responsive_align_details','center');
$open = !$links ? 'target="_blank"' : ''; ?>

<?php if (!empty($pretext)) echo $pretext; ?>
<?php if (!empty($events)) { ?>
<div class="row-fluid">
	<div class="span12">
		<div class="rseprocarousel slide mod_slider_container mod_rseventspro_slider<?php echo $module->id;?>" id="mod_rseventspro_slider<?php echo $module->id;?>">
			<div class="mod_slider_inner carousel-inner">
				<?php foreach($events as $i => $event) { ?>
					<div class="item <?php echo $i==0 ? 'active' : ''?>" data_captions="<?php echo $i;?>">
						<div class="span12">
						<a <?php echo $open; ?> href="<?php echo rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),true,$itemid); ?>">
							<?php if (!empty($event->icon)) { ?>
							<img src="<?php echo $event->image;?>" alt="<?php echo $event->name; ?>" />
							<?php } else { ?>
							<?php echo JHtml::image('mod_rseventspro_slider/default.gif', '', array(), true); ?> 
							<?php } ?>
						</a>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php if (!empty($title) || !empty($date)) { ?>
				<div class="mod_slider_caption_container">
				<?php foreach($events as $i=>$event) { ?>
					<div class="mod_slider_caption mod_slider_<?php echo $item_align;?>_element carousel-caption <?php echo $i==0 ? 'active' : ''?>" id="mod_slider_caption<?php echo $i;?>">
						<?php if (!empty($title)) { ?>
							<h3><a <?php echo $open; ?> href="<?php echo rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),true,$itemid); ?>" class="bnr_link"><?php echo $event->name; ?></a></h3>
						<?php } ?>
						<?php if (!empty($date)) { ?>
						<p class="lead">
							<?php echo JText::_('MOD_RSEVENTSPRO_SLIDER_DATE');?>: <?php echo $event->allday ? rseventsproHelper::showdate($event->start,rseventsproHelper::getConfig('global_date'),true) : rseventsproHelper::showdate($event->start,null,true); ?>
						</p>
						<?php } ?>
					</div>
				<?php } ?>
				</div>
			<?php } ?>
			<?php if ($buttons) { ?>
			<a class="mod_slider_left mod_slider_control carousel-control" href="#mod_rseventspro_slider<?php echo $module->id; ?>" data-slide="prev">&lsaquo;</a>
			<a class="mod_slider_right mod_slider_control carousel-control" href="#mod_rseventspro_slider<?php echo $module->id; ?>" data-slide="next">&rsaquo;</a>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>
<?php if (!empty($posttext)) echo '<br />'.$posttext; ?>