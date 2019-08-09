<?php
/**
 * @version		$Id: items.php 478 2010-06-16 16:11:42Z joomlaworks $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks, a business unit of Nuevvo Webware Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldK2Items extends JFormField
{

	var	$type = 'k2items';

	public function getInput()
	{
$k2_check = JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR);
	if($k2_check):
        $uri = str_replace(DIRECTORY_SEPARATOR,"/",str_replace( JPATH_SITE, JURI::base (), dirname(dirname(__FILE__)) ));
		$uri = str_replace("/administrator/", "", $uri);
		$document = JFactory::getDocument();
		$js = "		
		function jSelectItem(id, title, catid, object) {
			var exists = false;
			$$('#itemsListk2 input').each(function(element){
					if(element.value==id){
						alert('".JText::_('Item exists already in the list')."');
						exists = true;			
					}
			});
			if(!exists){
				var container = new Element('div').inject($('itemsListk2'));
				var img = new Element('img',{'class':'removek2', 'src':'".$uri."/elements/images/publish_x.png'}).inject(container);
				var span = new Element('span',{'class':'handlek2'}).set('html',title).inject(container);
	var input = new Element('input',{'value':id, 'type':'hidden', 'name':'".$this->name."[]'}).inject(container);
				var div = new Element('div',{'style':'clear:both;'}).inject(container);
				fireEvent('sortingready');
				alert('".JText::_('Item added in the list')."');
				if($$('#module-sliders').length > 0){
					var mainholder_s = $('module-sliders').getElement('.togh_k2').getSize().y;
					var sel_elem_s 	= $('itemsListk2').getElement('.handlek2').getSize().y;	
			 		$('module-sliders').getElement('.togh_k2').setStyle('height',mainholder_s+sel_elem_s);
				}
			}
		}
		
		window.addEvent('domready', function(){			
			fireEvent('sortingready');
		});
		
		window.addEvent('sortingready', function(){
			new Sortables($('itemsListk2'), {
			 	handles:$$('.handlek2')
			
			});
			$$('#itemsListk2 .removek2').addEvent('click', function(){
				$(this).getParent().dispose();
				
				if($$('#module-sliders').length > 0){
					var mainholder_s = $('module-sliders').getElement('.togh_k2').getSize().y;
					var sel_elem_s 	= $('jform_params_k2items-lbl').getSize().y;	
			 		$('module-sliders').getElement('.togh_k2').setStyle('height',mainholder_s-5);
				}
			});
		});
		";

		$document->addScriptDeclaration($js);
		$current = array();
		if(is_string($this->value) && !empty($this->value))
			$current[]=$this->value;
		if(is_array($this->value))
			$current=$this->value;
			
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_k2'.DIRECTORY_SEPARATOR.'tables');
		$output = '<div id="itemsListk2">';
		foreach($current as $id){
			$row = &JTable::getInstance('K2Item', 'Table');
			$row->load($id);
			$output .= '
			<div class="k2_selected_item">
				<img class="removek2" src="'.$uri.'/elements/images/publish_x.png"/>
				<span class="handlek2">'.$row->title.'</span>
				<input type="hidden" value="'.$row->id.'" name="'.$this->name.'[]"/>
				<div style="clear:both;"></div>
			</div>
			';
		}
		$output .= '</div>';
else:
		$output='<div id="itemsListk2"><br /><br /><b><font color="red">K2 is not installed!</font></b><br /></div>';
endif;
		return $output;
	}
}
