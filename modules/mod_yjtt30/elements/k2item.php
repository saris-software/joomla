<?php
/**
 * @version		$Id: item.php 478 2010-06-16 16:11:42Z joomlaworks $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks, a business unit of Nuevvo Webware Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldK2Item extends JFormField
{

	var $type = 'k2Item';

	public function getInput()
	{
$k2_check = JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR);
	if($k2_check):	
		$mainframe = JFactory::getApplication();
	
		$db = JFactory::getDBO();
		$doc = JFactory::getDocument();
		$fieldName = $this->name;
		$name  = $this->name;
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_k2'.DIRECTORY_SEPARATOR.'tables');
		$item = JTable::getInstance('K2Item', 'Table');
		$title = $item->title;
	
		if ($this->value) {
			$item->load($this->value);
		}
		else {
			$item->title = JText::_('Select an item...');
		}
	
		$js = "
		function jSelectItem(id, title, object) {
			document.getElementById('".$this->name."' + '_id').value = id;
			document.getElementById('".$this->name."' + '_name').value = title;
		}
		";
		
		$doc->addScriptDeclaration($js);
	
		$link = 'index.php?option=com_k2&amp;view=items&amp;task=element&amp;tmpl=component&amp;object='.$this->name;
	
		JHTML::_('behavior.modal', 'a.modal');
	
		$html = '
		<div id="k2_select_items">
			<div style="float:left;">
				<input style="background:#fff;margin:3px 0;" type="text" id="'.$name.'_name" value="Select specific items" disabled="disabled" />
			</div>
			<div class="button2-left btn">
				<div class="blank">
					<a class="modal" title="'.JText::_('Select specific items').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 700, y: 450}}">'.JText::_('Select').'</a>
				</div>
			</div>
			<input type="hidden" id="'.$this->name.'_id" name="'.$fieldName.'" value="'.( int )$this->value.'" />
		</div>
		';
	
else:
		$html = '<div id="k2_select_items"><b><font color="red">K2 is not installed!</font></b></div>';
endif;
		return $html;
	}

}

