<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator'); ?>

<script type="text/javascript">
	jQuery(document).ready(function (){
		<?php if (!$this->social['js']) { ?>jQuery('#jform_user_display option[value=2]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['cb']) { ?>jQuery('#jform_user_display option[value=3]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['js']) { ?>jQuery('#jform_user_profile option[value=1]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['cb']) { ?>jQuery('#jform_user_profile option[value=2]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['easysocial']) { ?>jQuery('#jform_user_profile option[value=3]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['js']) { ?>jQuery('#jform_event_owner option[value=2]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['cb']) { ?>jQuery('#jform_event_owner option[value=3]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['js']) { ?>jQuery('#jform_event_owner_profile option[value=1]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['cb']) { ?>jQuery('#jform_event_owner_profile option[value=2]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['easysocial']) { ?>jQuery('#jform_event_owner_profile option[value=3]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['cb']) { ?>jQuery('#jform_user_avatar option[value=comprofiler]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['js']) { ?>jQuery('#jform_user_avatar option[value=community]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['k2']) { ?>jQuery('#jform_user_avatar option[value=k2]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['kunena']) { ?>jQuery('#jform_user_avatar option[value=kunena]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['fireboard']) { ?>jQuery('#jform_user_avatar option[value=fireboard]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['easydiscuss']) { ?>jQuery('#jform_user_avatar option[value=easydiscuss]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['easydiscuss']) { ?>jQuery('#jform_user_profile option[value=4]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['easysocial']) { ?>jQuery('#jform_user_avatar option[value=easysocial]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['rscomments']) { ?>jQuery('#jform_event_comment option[value=2]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['jcomments']) { ?>jQuery('#jform_event_comment option[value=3]').prop('disabled',true);<?php } ?>
		<?php if (!$this->social['jomcomment']) { ?>jQuery('#jform_event_comment option[value=4]').prop('disabled',true);<?php } ?>
		
		jQuery('#jform_user_display').trigger('liszt:updated');
		jQuery('#jform_user_profile').trigger('liszt:updated');
		jQuery('#jform_event_owner').trigger('liszt:updated');
		jQuery('#jform_event_owner_profile').trigger('liszt:updated');
		jQuery('#jform_user_avatar').trigger('liszt:updated');
		jQuery('#jform_event_comment').trigger('liszt:updated');
	});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rseventspro&view=settings'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" autocomplete="off" enctype="multipart/form-data">
	<div class="row-fluid">
		<div id="j-sidebar-container" class="span2">
			<?php echo JHtmlSidebar::render(); ?>
		</div>
		<div id="j-main-container" class="span10 j-main-container">
		<?php foreach ($this->layouts as $layout) {
			// add the tab title
			$this->tabs->title('COM_RSEVENTSPRO_CONF_TAB_'.strtoupper($layout), $layout);
			
			// prepare the content
			$content = $this->loadTemplate($layout);
			
			// add the tab content
			$this->tabs->content($content);
		}
		
		// render tabs
		echo $this->tabs->render();
		?>
		<div id="mapContainer" style="display: none;">
			<div id="map_canvas" style="width: 100%; height: 380px; float: left;"></div>
		</div>
		
			<div>
			<?php echo JHtml::_('form.token'); ?>
			<input type="hidden" name="task" value="" />
			</div>
		</div>
	</div>
</form>

<?php echo JHtml::_('bootstrap.renderModal', 'rseproFacebookLog', array('title' => JText::_('COM_RSEVENTSPRO_CONF_SYNC_LOG_BTN'), 'url' => 'index.php?option=com_rseventspro&view=settings&layout=log&from=facebook&tmpl=component', 'bodyHeight' => 70)); ?>
<?php echo JHtml::_('bootstrap.renderModal', 'rseproGoogleLog', array('title' => JText::_('COM_RSEVENTSPRO_CONF_SYNC_LOG_BTN'), 'url' => 'index.php?option=com_rseventspro&view=settings&layout=log&from=google&tmpl=component', 'bodyHeight' => 70)); ?>