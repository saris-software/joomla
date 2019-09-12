<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// no direct access
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$pageClass = $this->params->get('pageclass_sfx');
?>

<div class="newsfeed-category<?php echo $this->pageclass_sfx;?>">
	<?php
	if ($this->params->def('show_page_heading', 1)) {
	?>
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	<?php
	}

	if($this->params->get('show_category_title', 1)) {
	?>
		<h2>
			<?php echo JHtml::_('content.prepare', $this->category->title); ?>
		</h2>
	<?php
	}

	if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) {
	?>
		<div class="category-desc">
			<?php
			if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) {
			?>
				<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
			<?php
			}

			if ($this->params->get('show_description') && $this->category->description) {
				echo JHtml::_('content.prepare', $this->category->description);
			}
			?>

			<div class="clr"></div>
		</div>
	<?php
	}

	echo $this->loadTemplate('items');

	if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) {
	?>
		<div class="cat-children">
			<h3>
				<?php echo JText::_('JGLOBAL_SUBCATEGORIES') ; ?>
			</h3>

			<?php echo $this->loadTemplate('children'); ?>
		</div>
	<?php
	}
	?>
</div>

<?php
if($this->params->get('show_footertext')) {
?>
	<p class="copyright" style="text-align : right; font-size : 10px;">
		<?php require_once( JPATH_COMPONENT . '/copyright/copyright.php' ); ?>
	</p>
<?php
}
?>