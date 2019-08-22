<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSFilterBar
{
	// show the search (filter)
	public $search = null;
	// show additional items located in the right
	public $rightItems = array();
	
	public function __construct($options=array()) {
		foreach ($options as $k => $v) {
			$this->{$k} = $v;
		}
	}
	
	protected function escape($string) {
		return htmlentities($string, ENT_COMPAT, 'utf-8');
	}
	
	public function show() {
		?>
		<fieldset id="filter-bar">
			<?php if ($this->search) { ?>
			<div class="filter-search fltlft">
				<label class="filter-search-lbl" for="filter_search"><?php echo $this->search['label']; ?></label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->search['value']); ?>" />
				<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button type="button" onclick="document.getElementById('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
			</div>
			<?php } ?>
			<?php if ($this->rightItems) { ?>
				<?php foreach ($this->rightItems as $item) { ?>
				<div class="filter-select fltrt">
					<?php echo $item['input']; ?>
				</div>
				<?php } ?>
			<?php } ?>
		</fieldset>
		<div class="clr"> </div>
		<?php
	}
}