<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');


class JFormFieldPhocaTemplates extends JFormFieldList
{

	public $type = 'PhocaTemplates';


	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		// Get the client and client_id.
		$client = (string) $this->element['client'];
		$clientId = ($client == 'administrator') ? 1 : 0;

		// Get the database object and a new query object.
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);

	/*	// Build the query.
		$query->select('id, title, template');
		$query->from('#__template_styles');
		$query->where('client_id = '.(int) $clientId);
		$query->order('template');
		$query->order('title');
		$query->group('template');

		// Set the query and load the styles.
		$db->setQuery($query);
		$styles = $db->loadObjectList();
		
		//$options[] = JHTML::_('select.option', '', '- '.JText::_('COM_PHOCAFAVICON_SELECT_TEMPLATE').' -');
		
		// Build the grouped list array.
		foreach($styles as $style) {
			$options[] = JHtml::_('select.option', $style->id, $style->template);
		}*/
		
		$templateFolders = PhocaFaviconHelper::getTemplateFolders();
		if(!empty($templateFolders)) {
			foreach($templateFolders as $key => $value) {
				$options[] = JHtml::_('select.option', $value->name, $value->name);
			}
		}
		
		$options = array_merge(parent::getOptions(), $options);
		return $options;
		//return JHTML::_('select.genericlist',  $options,  $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id );

	}
}