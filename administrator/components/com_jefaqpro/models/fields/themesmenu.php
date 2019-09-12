<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');

/**
 * Supports an HTML select list of themes
 */
class JFormFieldThemesmenu extends JFormField
{
	/**
	 * @var		string	The form field type.
	 */
	public $type = 'Themesmenu';

	/**
	 * Method to get the field options.
	 */
	protected function getInput()
	{
		// Initialise variables.
		$options		= array();
		$db				= JFactory::getDbo();
		$query			= $db->getQuery(true);

		$query->select('id, themes AS text');
		$query->from('#__jefaqpro_themes');
		$query->order('id');
		$db->setQuery($query);
		$themes			= $db->loadObjectList();
		$options[]	    = JHTML::_('select.option',0, JText::_("COM_JEFAQPRO_SELECT_THEME"));
		foreach ( $themes as $key=>$value ) {
			$options[]	= JHTML::_('select.option',  $value->id, $value->text);
		}
		$return   = '<script type="text/javascript">' .
						'function selectTheme(tid) {
							var path    = document.getElementById("theme_path");
							var preview = document.getElementById("jefaq_theme_preview");
							var img_src = path.value+tid+".jpg";
							preview.setAttribute("src",img_src);
						 }'.
				   '</script>';
		$return  .= '<input type="hidden" id="theme_path" value="'.JURI::base().'components/com_jefaqpro/assets/images/preview/"/>';
		$onchange = $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
		$return  .= JHtml::_('select.genericlist', $options, $this->name, $onchange, 'value', 'text', $this->value, $this->id);
		$return  .= "<br/>".JHTML::_('image','administrator/components/com_jefaqpro/assets/images/preview/'.$this->value.'.jpg', '', 'id="jefaq_theme_preview"', false);

		return $return;
	}
}