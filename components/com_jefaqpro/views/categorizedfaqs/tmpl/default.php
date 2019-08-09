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

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$itemid   = JRequest::getVar('Itemid');
$form	  = JRoute::_('index.php?option=com_jefaqpro&view=form&layout=edit&Itemid='.jefaqproHelperRoute::getaddFormRoute());
?>

<div id="je-faqpro">
	<?php
	if( $this->total > 0 ) {

		if ($this->params->get('show_page_heading', 1)) {
		?>
			<h1>
				<?php
				if ($this->escape($this->params->get('page_heading'))) {
					 echo $this->escape($this->params->get('page_heading'));
				} else {
					echo $this->escape($this->params->get('page_title'));
				}
				?>
			</h1>
		<?php
		} else {
			?>
				<h1> <?php echo JText::_('COM_JEFAQPRO_TITLE');  ?> </h1>
			<?php
		}

		if ( $this->params->get('show_onlyregusers', 1) && $this->params->get('add_votes', 1)) {
			if ( $this->allowed == '1' && $this->user->get('id') >0 ) {
		?>
				<div id="je-newbutton">
					<div style="text-align : right">
						<a id="je-addbutton" href="<?php echo $form; ?>" title="<?php echo JText::_('JE_ADDNEW'); ?>" > <strong> <?php echo JText::_('JE_ADDNEW'); ?> </strong> </a>
					</div>
				</div>
				<br/><br/><br/>
		<?php
			}
		}  else {
				if ($this->allowed == '1' ) {
			?>
					<div id="je-newbutton">
						<div style="text-align : right">
							<a id="je-addbutton" href="<?php echo $form; ?>" title="<?php echo JText::_('JE_ADDNEW'); ?>" > <strong> <?php echo JText::_('JE_ADDNEW'); ?> </strong> </a>
						</div>
					</div>
						<br/><br/><br/>
			<?php
				}
			}
		?>

		<form id="adminForm" name="adminForm" action="<?php echo JRoute::_('index.php');?>" method="post">

			<?php
				echo $this->loadTemplate('faqs');
			?>

			<input type="hidden" name="task" value="faqs" />
			<input type="hidden" name="option" value="com_jefaqpro" />
			<input type="hidden" name="limitstart" value="" />
		</form>
	<?php
	}
	?>
</div>

<?php
if($this->params->get('show_footertext')) {
?>
	<p class="copyright" style="text-align : right; font-size : 10px;">
		<?php require_once( JPATH_COMPONENT . '/copyright/copyright.php' ); ?>
	</p>
<?php
}
?>
