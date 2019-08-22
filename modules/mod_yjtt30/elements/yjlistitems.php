<?php
/**
 * @package		Youjoomla Extend Elements
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldYjListitems extends JFormField
{

	var	$type = 'yjlistitems'; 


	public function getInput()
	{
		
		
        $uri = str_replace(DIRECTORY_SEPARATOR,"/",str_replace( JPATH_SITE, JURI::base (), dirname(dirname(__FILE__)) ));
		$uri = str_replace("/administrator/", "", $uri);
		
		$name  = $this->name; 
		$document = JFactory::getDocument();
		$js = "		

		function  jSelectArticle_" . $this->id . "(id, title, catid, object) {
			var exists = false;
			
			$$('#itemsList input').each(function(element){
					if(element.value==id){
						alert('".JText::_('Item exists already in the list')."');
						exists = true;			
					}
			});
			if(!exists){
				var container = new Element('div').inject($('itemsList'));
				var img = new Element('img',{'class':'remove', 'src':'".$uri."/elements/images/publish_x.png'}).inject(container);
				var span = new Element('span',{'class':'handle'}).set('html',title).inject(container);
	var input = new Element('input',{'value':id, 'type':'hidden', 'name':'".$this->name."[]'}).inject(container);
				var div = new Element('div',{'style':'clear:both;'}).inject(container);
				fireEvent('sortingready');
				alert('".JText::_('Item added in the list')."');
				if($$('#module-sliders').length > 0){
					var mainholder_s = $('module-sliders').getElement('.togh_yj').getSize().y;
					var sel_elem_s 	= $('itemsList').getElement('.handle').getSize().y;	
					$('module-sliders').getElement('.togh_yj').setStyle('height',mainholder_s+sel_elem_s);
				}
			}
		}
		
		window.addEvent('domready', function(){			
			fireEvent('sortingready');
		});
		
		window.addEvent('sortingready', function(){
			new Sortables($('itemsList'), {
			 	handles:$$('.handle')
			
			});
			$$('#itemsList .remove').addEvent('click', function(){
				$(this).getParent().dispose();
				if($$('#module-sliders').length > 0){
					var mainholder_s = $('module-sliders').getElement('.togh_yj').getSize().y;
					var sel_elem_s 	= $('jform_params_k2items-lbl').getSize().y;
					$('module-sliders').getElement('.togh_yj').setStyle('height',mainholder_s-5);
				}

			});
		});
		";

		$document->addScriptDeclaration($js);
		$value = $this->value;
		$current = array();
		if(is_string($value) && !empty($value))
			$current[]=$value;
		if(is_array($value))
			$current=$value;
		$e_folder = basename(dirname(dirname(__FILE__)));	
		JTable::addIncludePath(JPATH_ROOT.'/modules/'.$e_folder.'/elements');
		
		
		$linkArticles = 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1';
		$link = $linkArticles . '&amp;function=jSelectArticle_' . $this->id;
			
		$button = '
		<div id="joomla_select_items">
			<div class="btn">
				<div class="blank">
					<a class="modal" title="'.JText::_('Select specific items').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 700, y: 450}}">'.JText::_('Select').'</a>
				</div>
			</div>
		</div>
		';
		
		$output = $button. '<div id="itemsList">';
		foreach($current as $id){
			$row = JTable::getInstance('YjContent', 'Table');
			$row->load($id);
			$output .= '
			<div class="jom_sel_ele">
				<img class="remove" src="'.$uri.'/elements/images/publish_x.png"/>
				<span class="handle">'.$row->title.'</span>
				<input type="hidden" value="'.$row->id.'" name="'.$this->name.'[]"/>
				<div style="clear:both;"></div>
			</div>
			';
		}
		$output .= '</div>';
		return $output;
	}
}
