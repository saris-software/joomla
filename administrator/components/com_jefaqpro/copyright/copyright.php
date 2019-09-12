<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$pathToXML_File = JPATH_COMPONENT . '/jefaqpro.xml';
$xml	 		= JFactory::getXML($pathToXML_File,$isFile = true);
$name 			= $xml->name;
$version 		= $xml->version;
$author 		= $xml->author;
$authorurl 		= $xml->authorUrl;

echo $name."&nbsp;".$version."&nbsp;-";
echo $name['0']->_data."&nbsp;".$version['0']->_data;
?>
<a href="http://www.jextn.com" title="<?php echo JText::_('JE_DEVELOPED'); ?>" target="_blank">
	<?php echo JText::_('JE_DEVELOPED'); ?>
</a>