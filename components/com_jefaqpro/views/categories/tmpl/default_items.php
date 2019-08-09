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

$class	= ' class="first"';

if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) {
?>
	<ul>
		<?php
		foreach($this->items[$this->parent->id] as $id => $item) {
		?>
			<?php
			if($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) {
				if(!isset($this->items[$this->parent->id][$id + 1]))
				{
					$class = ' class="last"';
				}
			?>

			<li<?php echo $class; ?>>
				<?php $class = ''; ?>

				<div class="je-categorylisting" id="je-categorylisting<?php echo $this->settings->theme; ?>">
					<?php
					if($this->params->get('show_description_image')):
						$secid     = $item->id;
						$category  = JCategories::getInstance('jefaqpro')->get($secid);
						$image_cat = $category->getParams()->get('image');
						$path      = JURI::root();
					?>
						<img style="padding:0px 10px 0px 0px" width="50" height="50"  align="left" src="<?php echo $path; if ($image_cat!= '') : echo $category->getParams()->get('image'); else : echo "components/com_jefaqpro/assets/images/noimage/noimage.png"; endif; ?>"/>
					<?php endif; ?>

					<span class="item-title je-category">
						<a href="<?php echo JRoute::_(jefaqproHelperRoute::getCategoryRoute($item->id));?>">
							<?php echo $this->escape($item->title); ?>
						</a>
					</span>

				<?php
				if ($this->params->get('show_subcat_desc_cat') == 1){
				?>
					<?php
					if ($item->description) {
					?>
						<div id="je-introtext">
							<?php echo JHtml::_('content.prepare', $item->description); ?>
						</div>
					<?php
					}
					?>
		        <?php
				}
		        ?>

				<?php
				if ($this->params->get('show_cat_items_cat') == 1) {
				?>
					<dl class="newsfeed-count">
						<dt>
							<?php echo JText::_('COM_JEFAQPRO_CAT_NUM'); ?>
						</dt>
						<dd>
							<?php echo $item->numitems; ?>
						</dd>
					</dl>
				<?php
				}
				?>
				</div>
				<?php
					if(count($item->getChildren()) > 0) {
						$this->items[$item->id] = $item->getChildren();
						$this->parent = $item;
						$this->maxLevelcat--;
						echo $this->loadTemplate('items');
						$this->parent = $item->getParent();
						$this->maxLevelcat++;
					}
				?>
			</li>
			<?php
			}
		}
		?>
	</ul>
<?php
}
?>