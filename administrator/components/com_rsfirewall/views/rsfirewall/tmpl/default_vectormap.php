<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

JHtml::_('rsfirewall_script', 'com_rsfirewall/jquery.vmap.min.js', array('relative' => true, 'version' => 'auto'));
JHtml::_('rsfirewall_script', 'com_rsfirewall/jquery.vmap.world.js', array('relative' => true, 'version' => 'auto'));

JHtml::_('rsfirewall_stylesheet', 'com_rsfirewall/jqvmap.css', array('relative' => true, 'version' => 'auto'));

JFactory::getDocument()->addScriptDeclaration(
	'jQuery(document).ready(function(){
		RSFirewall.vmap.init("#com-rsfirewall-virtual-map");
	});');
?>
<h2><?php echo JText::_('COM_RSFIREWALL_ATTACKS_BLOCKED_REGION_BASED'); ?></h2>
<div id="com-rsfirewall-virtual-map" style="height: 400px;"></div>


