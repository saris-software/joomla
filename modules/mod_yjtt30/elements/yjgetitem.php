<?php
/**
 * @package		Youjoomla Extend Elements
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2010 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldYjGetitem extends JFormField
{

	var $type = 'yjgetitem';

	public function getInput() 
	{


		$mainframe = JFactory::getApplication();
	
		$doc =  JFactory::getDocument(); 
		$fieldName = $this->element['name'];
		$value = $this->value;
		$name  = $this->name;
		$e_folder = basename(dirname(dirname(__FILE__)));
		JTable::addIncludePath(JPATH_ROOT.'/modules/'.$e_folder.'/elements');
		$item =  JTable::getInstance('YjContent', 'Table');
		$title = $item->title;
		if ($value) {
			$item->load($value);
		}
		else {
			$title = JText::_('Select specific items...');
		}
		$document = JFactory::getDocument();
	
		$js = "
		function jSelectArticle(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
		}
		";
		$doc->addScriptDeclaration($js);
	
		$link = 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;object='.$name;
		JHTML::_('behavior.modal', 'a.modal');
	
		$html = '
		<div id="joomla_select_items">
			<div style="float:left;">
				<input style="background:#fff;margin:3px 0;" type="text" id="'.$name.'_name" value="Select specific items" disabled="disabled" />
			</div>
			<div class="button2-left btn">
				<div class="blank">
					<a class="modal" title="'.JText::_('Select specific items').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 700, y: 450}}">'.JText::_('Select').'</a>
				</div>
			</div>
			<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.( int )$value.'" />
		</div>
		';
	
		return $html;
	}

}