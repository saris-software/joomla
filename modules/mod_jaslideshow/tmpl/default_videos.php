<?php
/**
 * ------------------------------------------------------------------------
 * JA Slideshow Module for Joomla 2.5 & 3.x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
defined('_JEXEC') or die('Restricted access');
?>
<div
	class="ja-slidewrap<?php echo $skin_name; ?><?php echo $params->get('moduleclass_sfx'); ?> ja-<?php echo $source; ?>"
	id="ja-slide-articles-<?php echo $module->id; ?>" style="visibility:hidden">
	<div class="ja-slide-main-wrap<?php echo($navAlignment == 'vertical_left' ? ' ja-slide-right' : '') ?>">
		<div class="ja-slide-mask"></div>
		<div class="ja-slide-main">
			<?php foreach ($list as $k => $item) : ?>
				<div class="ja-slide-item">
					<?php
					if (isset($item->video)) {
						echo $helper->renderVideo($item->video, $mainWidth, $mainHeight);
					} else {
						echo $helper->renderImage($item->title, $item->mainImage, $params, $mainWidth, $mainHeight);
					}
					?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ($animation == 'move' && $container) : ?>
			<div class="but_prev ja-slide-prev"></div>
			<div class="but_next ja-slide-next"></div>
		<?php endif; ?>
		<div class="ja-slide-progress"></div>
		<div class="ja-slide-loader"></div>

		<div class="maskDesc">
			<div class="inner">
			</div>
		</div>
	</div>
	<?php if ($showDescription) : ?>
		<div class="ja-slide-descs">
			<?php foreach ($list as $item) : ?>
				<div class="ja-slide-desc">
					<a <?php echo $target; ?>  href="<?php echo $item->link; ?>">
						<span><?php echo $helper->trimString($item->title, $titleMaxChars); ?></span>
					</a>
					<?php echo $helper->trimString($item->introtext, $descMaxChars, $includeTags); ?>
					<?php if ($showDescription == 'desc' && $readmoretext != ''): ?>
					<a <?php echo $target; ?> class="readon readmore" href="<?php echo $item->link; ?>">
						<?php echo $readmoretext; ?>
					</a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ($navigation == "thumbs") : ?>
		<div class="ja-slide-thumbs-wrap<?php echo $classNav; ?>">
			<div class="ja-slide-thumbs">
				<?php foreach ($list as $key => $item) : ?>
					<div class="ja-slide-thumb <?php echo isset($item->video) ? "ja-slide-thumb-video" : ''; ?>">
						<div class="ja-slide-thumb-inner">
							<?php if ($navShowthumb == 1) : ?>
								<?php
								if (isset($item->video)) {
									echo $helper->renderVideoThumb($item->video, $params, $thumbWidth, $thumbHeight);
								} else {
									if (file_exists(JPATH_SITE . '/' . $item->thumbnail)) {
										echo $helper->renderImage($item->title, $item->thumbnail, $params, $thumbWidth, $thumbHeight, 'align="left"');
									}
								}
								?>
							<?php endif; ?>
							<h3><?php echo $helper->trimString($item->title, $titleMaxChars); ?></h3>
							<?php if ($navShowDate) : ?>
								<span class="ja-createdate clearfix">
									<?php echo JTEXT::_("POSTED_DATE") . "&nbsp;	" . JHTML::_('date', $item->date, JText::_('DATE_FORMAT_LC4')); ?>
								 </span>
							<?php endif; ?>
							<?php if ($navShowdesc): ?>
								<?php echo $helper->trimString(strip_tags($item->introtext), $navDescmaxlength); ?>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="ja-slide-thumbs-mask" style=" display:none ">
				<div class="ja-slide-thumbs-mask-left">&nbsp;</div>
				<div class="ja-slide-thumbs-mask-center">&nbsp;</div>
				<div class="ja-slide-thumbs-mask-right">&nbsp;</div>
			</div>

			<p class="ja-slide-thumbs-handles">
				<?php foreach ($list as $item) : ?>
					<span>&nbsp;</span>
				<?php endforeach; ?>
			</p>
		</div>
	<?php elseif ($navigation == "number") : ?>
		<div class="ja-slide-thumbs-wrap<?php echo $classNav; ?>">
			<div class="ja-slide-thumbs">
				<?php foreach ($list as $key => $item) : ?>
					<div class="ja-slide-thumb">
						<span><?php echo($key + 1); ?></span>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="ja-slide-thumbs-mask" style=" display:none ">
				<div class="ja-slide-thumbs-mask-left">&nbsp;</div>
				<div class="ja-slide-thumbs-mask-center">&nbsp;</div>
				<div class="ja-slide-thumbs-mask-right">&nbsp;</div>
			</div>

			<p class="ja-slide-thumbs-handles">
				<?php foreach ($list as $item) : ?>
					<span>&nbsp;</span>
				<?php endforeach; ?>
			</p>

		</div>
	<?php else : ?>
		<p class="ja-slide-thumbs-handles">
			<?php foreach ($list as $item) : ?>
				<span>&nbsp;</span>
			<?php endforeach; ?>
		</p>
	<?php endif ?>

	<?php if ($control): ?>
		<div class="ja-slide-buttons clearfix">
			<span class="ja-slide-prev">&laquo; <?php echo JText::_('PREVIOUS'); ?></span>
			<span class="ja-slide-playback">&lsaquo; <?php echo JText::_('PLAYBACK'); ?></span>
			<span class="ja-slide-stop"><?php echo JText::_('STOP'); ?></span>
			<span class="ja-slide-play"><?php echo JText::_('PLAY'); ?> &rsaquo;</span>
			<span class="ja-slide-next"><?php echo JText::_('NEXT'); ?>  &raquo;</span>
		</div>
	<?php endif; ?>

</div>
