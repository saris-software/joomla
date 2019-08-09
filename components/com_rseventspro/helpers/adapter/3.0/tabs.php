<?php
/**
* @package RSJoomla! Adapter
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

/**
 * Utility class for Tabs.
 *
 * @package     RSJoomla!
 */
class RSTabs {
	
	protected $id		= null;
	protected $titles 	= array();
	protected $contents = array();
	
	/**
	 * Method to set the tab id
	 *
	 * @return  void
	 */
	public function __construct($id) {
		JHtml::_('behavior.tabstate');
		
		$this->id = preg_replace('#[^A-Z0-9_\. -]#i', '', $id);
	}

	/**
	 * Method to add tab title
	 *
	 * @return  void
	 */
	public function title($label, $id) {
		$this->titles[] = (object) array('label' => $label, 'id' => $id);
	}
	
	/**
	 * Method to add content to the tab
	 *
	 * @return  void
	 */
	public function content($content) {
		$this->contents[] = $content;
	}
	
	/**
	 * Render tabs
	 *
	 * @return  string  HTML for tabs
	 */
	public function render() {
		$version = new JVersion;
		if ($version->isCompatible('3.1')) {
			return $this->renderNative();
		} else {
			return $this->renderLegacy();
		}
	}
	
	protected function renderNative() {
		$active = reset($this->titles);
		
		echo JHtml::_('bootstrap.startTabSet', $this->id, array('active' => $active->id));
		
		foreach ($this->titles as $i => $title) {
			echo JHtml::_('bootstrap.addTab', $this->id, $title->id, JText::_($title->label));
			echo $this->contents[$i];
			echo JHtml::_('bootstrap.endTab');
		}
		
		echo JHtml::_('bootstrap.endTabSet');
	}
	
	protected function renderLegacy() {
		$html   = array();
		
		$html[] = "\t".'<ul class="nav nav-tabs" id="'.$this->id.'">';
		
		foreach ($this->titles as $i => $title) {
			$html[] = "\t\t".'<li'.($i == 0 ? ' class="active"' : '').'><a href="#'.$title->id.'" data-toggle="tab">'.JText::_($title->label).'</a></li>';
		}
		
		$html[] = "\t".'</ul>';
		$html[] = "\t".'<div class="tab-content">';
		
		foreach ($this->contents as $j => $content) {
			$html[] = "\t\t".'<div class="tab-pane'.($j == 0 ? ' active' : '').'" id="'.$this->titles[$j]->id.'">';
			$html[] = "\t\t\t".$content;
			$html[] = "\t\t".'</div>';
		}
		
		$html[] = "\t".'</div>';
		
		return implode("\n",$html);
	}
}