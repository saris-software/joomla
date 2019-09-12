<?php
/**
 * @version		$Id: categoriesmultiple.php 1034 2011-10-04 17:00:00Z joomlaworks $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
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




class JFormFieldk2categoriesmultiple extends JFormField
{

	var	$type = 'k2categoriesmultiple';

	function getInput(){

$k2_check = JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_k2".DIRECTORY_SEPARATOR);
	if($k2_check):

		
		$params = JComponentHelper::getParams('com_k2');
		
		$document = JFactory::getDocument();
		
		JHtml::_('behavior.framework', true);
		
		$db = JFactory::getDBO();
		$query = 'SELECT m.* FROM #__k2_categories m WHERE published=1 AND trash = 0 ORDER BY parent, ordering';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		$children = array();
		if ($mitems){
			foreach ( $mitems as $v ){
				$v->title = $v->name;
				$v->parent_id = $v->parent;
				$pt = $v->parent;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		$mitems = array();

		foreach ( $list as $item ) {
			$item->treename = JString::str_ireplace('&#160;', '- ', $item->treename);
			$mitems[] = JHTML::_('select.option',  $item->id, '   '.$item->treename );
		}



		$fieldName = $this->name.'[]';


		$output= JHTML::_('select.genericlist',  $mitems, $fieldName, ' multiple="multiple"', 'value', 'text', $this->value );
else:
		$output= '
<select id="jformparamscategory_id" class="inputbox" size="10" multiple="multiple" style="width:90%;" name="params[category_id][]" disabled="disabled">
<option value="" disabled="disabled">K2 is not installed!</option>
</select><br />
		';
endif;
		return $output;
	}
}
