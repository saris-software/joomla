<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license     GNU General Public License version 2 or later; see LICENSE
*/

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('hidden');
class JFormFieldScripts extends JFormFieldHidden
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'Scripts';

	public function __construct() {
		if (file_exists(JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php')) {		
			// Load jQuery
			rseventsproHelper::loadjQuery();
			
			JHtml::script('mod_rseventspro_events/script.js', array('relative' => true, 'version' => 'auto'));
		}
	}
	
	/**
	 * Method to get the field label markup for a spacer.
	 * Use the label text or name from the XML element as the spacer or
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   11.1
	 */
	protected function getLabel() {
		return '<script type="text/javascript">rsepro_select_type();</script>';
	}
}