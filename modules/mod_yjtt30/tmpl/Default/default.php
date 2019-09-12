<?php
/**
 * @package		YJ Title Ticker 3.0
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
//Title: 			$yj_get_items['item_title']
//Author: 			$yj_get_items['item_author'] = username || $yj_get_items['item_author_rn'] = real name
//Image:			$yj_get_items['img_url'] = use isset to check before output
//Intro text:		$yj_get_items['item_intro']
//Create date:		$yj_get_items['item_date']
//Category:			$yj_get_items['cat_title']
//Item url:			$yj_get_items['item_url']
//Author url: 		$yj_get_items['author_url']
//Cat url:			$yj_get_items['cat_url']
//Foreach to be used =  foreach ($main_yj_arr as $yj_get_items){ echo each part here }

/*Image sizing: The images are inside div that is resizing when you enter the values in module parameters. this way there is no image disortion. For those who dont like that , you can add this
style="width:<?php echo $img_width ?>;height:<?php echo $img_height ?>;"
within image tag after alt="" (space it please) and have the images resized */

  
defined('_JEXEC') or die('Restricted access'); ?>
<!-- http://www.Youjoomla.com  Youjoomla YJ Title Ticker 3.0 Module for Joomla 1.6.x and UP starts here -->
<div id="titles_holder" style="width:<?php echo $hold_w?>px; height:<?php echo $height?>;">
	<?php if($show_custom_title == 1) { ?>
	<div id="title_title" style="line-height:<?php echo $height -1?>px; width:<?php echo $title_w ?>;"><?php echo $custom_text ?> :</div>
	<?php } ?>
<?php if ($shownav == 1 ){ ?>
	<div class="title_nav" style="height:<?php echo $height?>;"> 
		<a class="prev" style="height:<?php echo $height?>;" onclick="javascript: title_slider.prev(type_slider);"></a> 
		<a class="next" style="height:<?php echo $height?>;" onclick="javascript: title_slider.next(type_slider);"></a> 
	</div>
<?php } ?>
<div id="yj_nt2" class="title_slide" style="width:<?php echo $width?>;height:<?php echo $height?>;">
	<?php  if ($orient == 1 ) { ?>
	<div style="width:<?php echo $main_w ?>px;overflow: hidden;float: left;">
		<?php } ?>
		<?php  if ($orient == 0 ) { ?>
		<div style="height:<?php echo $main_w?>px;overflow: hidden;">
			<?php } ?>
			<?php foreach ($main_yj_arr as $yj_get_items): ?>
			<div class="ttick" style="width:<?php echo $width ?>; line-height:<?php echo $tpad ?>px; text-indent:5px; height:<?php echo $height ?>;<?php echo $float ?>;"> <a href="<?php echo $yj_get_items['item_url'] ?>">
            <?php  if ($show_title == 1): ?>
				<?php echo $yj_get_items['item_title']?>&nbsp;>&nbsp;
            <?php endif; ?>
			<?php echo $yj_get_items['item_intro']?></a> </div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<script type='text/javascript'>
var  title_slider = new TitleTicker("yj_nt2",{type: "<?php echo $type ?>", direction: "forward", auto: "loop", time: <?php echo $time ?>, duration: <?php echo $duration ?>});
var type_slider='<?php echo $type ?>';
$('titles_holder').addEvent('mouseenter', function(){title_slider.pause();});
$('titles_holder').addEvent('mouseleave', function(){title_slider.run(type_slider);});
</script>