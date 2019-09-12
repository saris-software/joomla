<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSTabs
{
	protected $id		= null;
	protected $titles 	= array();
	protected $contents = array();
	
	public function __construct($id) {
		$this->id	   = preg_replace('#[^A-Z0-9_\. -]#i', '', $id);
	}
	
	public function addTitle($label, $id) {
		$this->titles[] = (object) array('label' => $label, 'id' => $id);
	}
	
	public function addContent($content) {
		$this->contents[] = $content;
	}
	
	public function render()
	{
		$version = new JVersion;
		if ($version->isCompatible('3.1'))
		{
			return $this->renderNative();
		}
		else
		{
			return $this->renderLegacy();
		}
	}
	
	public function renderNative()
	{
		$active = reset($this->titles);
		
		echo JHtml::_('bootstrap.startTabSet', $this->id, array('active' => $active->id));
		
		foreach ($this->titles as $i => $title)
		{
			echo JHtml::_('bootstrap.addTab', $this->id, $title->id, JText::_($title->label));
			echo $this->contents[$i];
			echo JHtml::_('bootstrap.endTab');
		}
		
		echo JHtml::_('bootstrap.endTabSet');
	}
	
	public function renderLegacy() {
		?>
		<ul class="nav nav-tabs" id="<?php echo $this->id; ?>">
		<?php foreach ($this->titles as $i => $title) { ?>
			<li<?php if ($i == 0) { ?> class="active"<?php } ?>><a href="#<?php echo $title->id; ?>" data-toggle="tab"><?php echo JText::_($title->label); ?></a></li>
		<?php } ?>
		</ul>
		<div class="tab-content">
		<?php foreach ($this->contents as $i => $content) { ?>
			<div class="tab-pane<?php if ($i == 0) { ?> active<?php } ?>" id="<?php echo $this->titles[$i]->id;?>">
			<?php echo $content; ?>
			</div>
		<?php } ?>
		</div>
		<?php
	}
}