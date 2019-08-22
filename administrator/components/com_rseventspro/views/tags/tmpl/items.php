<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

echo 'RS_DELIMITER0';
if (!empty($this->items)) {
	foreach ($this->items as $i => $item) {
		$offset = $this->pagination->getRowOffset($i) - 1;
		
		echo '<tr class="row'.($offset % 2).'">';
		echo '<td class="center hidden-phone">'.JHtml::_('grid.id', $offset, $item->id).'</td>';
		echo '<td class="nowrap has-context"><a href="'.JRoute::_('index.php?option=com_rseventspro&task=tag.edit&id='.$item->id).'">'.$item->name.'</a></td>';
		echo '<td class="center hidden-phone">'.$this->getEvents($item->id).'</td>';
		echo '<td class="center">'.JHtml::_('jgrid.published', $item->published, $offset, 'tags.').'</td>';
		echo '<td class="center hidden-phone">'.$item->id.'</td>';
		echo '</tr>';
	}
}
echo 'RS_DELIMITER1';
JFactory::getApplication()->close();