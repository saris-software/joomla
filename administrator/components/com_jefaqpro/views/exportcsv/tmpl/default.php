<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');

?>

<form action="#" method="post" name="adminForm" id="contact-form" class="form-validate" enctype="multipart/form-data">
	<div class="export-csvfile">
		<a href="<?php //echo JRoute::_('index.php?option=com_jefaqpro&view=exportcsv&task=exportcsv.exportcsvcat'); ?>"> <?php //echo JText::_('COM_JEFAQPRO_EXPORT_CATEGORIES').'<br>'; ?> </a>
		<a href="<?php echo JRoute::_('index.php?option=com_jefaqpro&view=exportcsv&task=exportcsv.exportcsvfaqs'); ?>"> <?php echo JText::_('COM_JEFAQPRO_EXPORT_FAQS'); ?> </a>
	</div>
</form>
<p class="copyright" align="center">
	<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
</p>