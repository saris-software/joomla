<?php 
/**
 * ------------------------------------------------------------------------
 * JA Edenite II Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die;
?>

<?php
	$count = $helper->getRows('data.image');
	$shadow = $helper->get('text-shadow');
  $autoPlay = $helper->get('slide-auto','0');
?>

<div class="acm-slideshow">
	<div id="acm-slideshow-<?php echo $module->id; ?>" <?php if($count >=10) echo 'class="so-much"'; ?>>
		<div class="owl-carousel owl-theme">
				<?php 
          for ($i=0; $i<$count; $i++) : 
        ?>
			<div class="item">
				<?php if($helper->get('data.image', $i)): ?>
				<img class="img-bg" alt="" src="<?php echo $helper->get('data.image', $i); ?>" />
				<?php endif; ?>

				<?php if($helper->get('data.title-text', $i)): ?>
				<div class="item-info">
					<?php if($helper->get('data.label-text', $i)): ?>
						<span><?php echo $helper->get('data.label-text', $i); ?></span>
					<?php endif; ?>

					<?php if($helper->get('data.title-text', $i)): ?>
						<h2><?php echo $helper->get('data.title-text', $i); ?></h2>
					<?php endif; ?>

					<?php if($helper->get('data.desc-text', $i)): ?>
						<p><?php echo $helper->get('data.desc-text', $i); ?></p>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
		<?php endfor ;?>
		</div>
	</div>
</div>

<script>
(function($){
  jQuery(document).ready(function($) {
    $("#acm-slideshow-<?php echo $module->id; ?> .owl-carousel").owlCarousel({
      items: 1,
      singleItem : true,
      itemsScaleUp : true,
      navigation : true,
      navigationText : ["<span class='fa fa-long-arrow-left'></span>", "<span class='fa fa-long-arrow-right'></span>"],
      pagination: true,
      merge: false,
      mergeFit: true,
      slideBy: 1,
      autoPlay: <?php echo $autoPlay ? 'true' : 'false'; ?>
    });
  });
})(jQuery);
</script>