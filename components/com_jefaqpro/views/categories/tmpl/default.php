<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// no direct access
defined('_JEXEC') or die('Restricted Access');

JHtml::addIncludePath( JPATH_COMPONENT.'/helpers' );

?>

<div class="categories-list<?php echo $this->pageclass_sfx;?>">
	<?php
	if ($this->params->get('show_page_heading', 1)) {
	?>
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	<?php
	}
	?>

	<?php
	if ($this->params->get('show_base_description')) {
		if($this->params->get('categories_description')) {
	?>
			<div class="category-desc base-desc">
				<?php echo  JHtml::_('content.prepare',$this->params->get('categories_description')); ?>
			</div>

	<?php
		} else {
			if ($this->parent->description) {
	?>
				<div class="category-desc  base-desc">
					<?php  echo JHtml::_('content.prepare', $this->parent->description); ?>
				</div>
	<?php
			}
		}
	}

	echo $this->loadTemplate('items');
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