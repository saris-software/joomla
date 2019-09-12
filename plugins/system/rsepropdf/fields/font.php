<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license     GNU General Public License version 2 or later; see LICENSE
*/

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');
class JFormFieldFont extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'Font';
	
	/**
	 * Method to get the field input markup for a combo box field.
	 *
	 * @return  string   The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getOptions() {
		$path = JPATH_SITE.'/components/com_rseventspro/helpers/pdf/dompdf/lib/fonts/';
		
		$options 	= array();
		$options[] = JHTML::_('select.option', 'dejavu sans', JText::_('RSEPRO_PDF_FONT_DEJAVU_SANS'), 'value', 'text', !file_exists($path.'DejaVuSans.ufm'));
		$options[] = JHTML::_('select.option', 'fireflysung', JText::_('RSEPRO_PDF_FONT_FIREFLYSUNG'), 'value', 'text', !file_exists($path.'fireflysung.ufm'));
		// get fonts
		$options[] = JHTML::_('select.option', 'courier', JText::_('RSEPRO_PDF_FONT_COURIER'));
		$options[] = JHTML::_('select.option', 'helvetica', JText::_('RSEPRO_PDF_FONT_HELVETICA'));
		$options[] = JHTML::_('select.option', 'times', JText::_('RSEPRO_PDF_FONT_TIMES'));
		
		return $options;
	}
}