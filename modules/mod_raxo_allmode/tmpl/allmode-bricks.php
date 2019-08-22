<?php
/**
 * =============================================================
 * @package		RAXO Bricks Module Layout
 * -------------------------------------------------------------
 * @copyright	Copyright (C) 2009-2016 RAXO Group
 * @link		http://www.raxo.org
 * @license		GNU General Public License v2.0
 * 				http://www.gnu.org/licenses/gpl-2.0.html
 * =============================================================
 */


// no direct access
defined('_JEXEC') or die;

// add layout CSS
JHtml::stylesheet('modules/mod_raxo_allmode/tmpl/allmode-bricks/allmode-bricks.css');

// add layout JS
JHtml::_('jquery.framework');
JHtml::script('modules/mod_raxo_allmode/tmpl/allmode-bricks/allmode-bricks.js');
?>


<?php if ($list) { ?>
<div id="allmode-id<?php echo $module->id; ?>" class="allmode-itemsbox">

	<?php foreach ($list as $item) { ?>
	<div class="allmode-item allmode-category-id<?php echo $item->category_id; ?>">

		<?php if ($item->image) { ?>
		<div class="allmode-img">

			<?php echo $item->image; ?>

			<?php if ($item->category) { ?>
				<div class="allmode-category"><?php echo $item->category; ?></div>
			<?php } ?>

		</div>
		<?php } ?>


		<?php if ($item->title || $item->text || $item->date || $item->author) { ?>
		<div class="allmode-content">

			<?php if ($item->title) { ?>
			<h4 class="allmode-title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
			<?php } ?>

			<?php if ($item->date || $item->author) { ?>
			<div class="allmode-info">

				<?php if ($item->date) { ?>
				<span class="allmode-date"><?php echo $item->date; ?></span>
				<?php } ?>

				<?php if ($item->author) { ?>
				<span class="allmode-author"><?php echo $item->author; ?></span>
				<?php } ?>

			</div>
			<?php } ?>

			<?php if ($item->text) { ?>
			<div class="allmode-text"><?php echo $item->text; ?></div>
			<?php } ?>

		</div>
		<?php } ?>


		<?php if ($item->hits || $item->rating_value || $item->comments_count || $item->readmore) { ?>
		<div class="allmode-details">

			<?php if ($item->hits) { ?>
			<span class="allmode-hits" title="Hits: <?php echo $item->hits; ?>"><?php echo $item->hits; ?></span>
			<?php } ?>

			<?php if ($item->rating_value) { ?>
			<span class="allmode-rating" title="Rating: <?php echo $item->rating_value; ?>"><?php echo number_format ($item->rating_value, 1); ?></span>
			<?php } ?>

			<?php if ($item->comments_count) { ?>
			<span class="allmode-comments" title="Comments: <?php echo $item->comments_count; ?>"><?php echo $item->comments_count; ?></span>
			<?php } ?>

			<?php if ($item->readmore) { ?>
			<span class="allmode-readmore"><?php echo $item->readmore; ?></span>
			<?php } ?>

		</div>
		<?php } ?>

	</div>
	<?php } ?>

</div>
<?php } ?>


<?php
// Column width is equal to 75% of the thumbnail width
$col_width = $params->get('image_width', array());
$col_width = ($col_width[1]) ? (int) $col_width[1] * 0.75 : 200;
?>
<script>
(function($) {
	$('#allmode-id<?php echo $module->id; ?>').gridalicious({
		selector: '.allmode-item',
		width: <?php echo $col_width; ?>,
		gutter: 24,
		animate: true
	});
})(jQuery);
</script>
