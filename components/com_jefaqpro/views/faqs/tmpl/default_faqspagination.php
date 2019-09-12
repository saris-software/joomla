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
?>
<!-- Area for pagination -->
<?php
	if( $this->pagination->get('pages.total') > 1) {
?>
<div class="pagination pagination-toolbar">
	<?php	echo $this->pagination->getPagesLinks();?>
</div>
<?php } ?>
<div class="btn-group" style="text-align:center;">
	<?php echo $this->pagination->getLimitBox(); ?>
</div>

<!-- Area for pagination Ends-->
