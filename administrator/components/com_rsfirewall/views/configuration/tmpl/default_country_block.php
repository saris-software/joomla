<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

if (!$this->geoip->php_version_compat) { ?>
	<div class="alert alert-danger">
		<h4><?php echo JText::_('COM_RSFIREWALL_GEOIP_PHP_COMPAT_ERROR'); ?></h4>
		<p><?php echo JText::sprintf('COM_RSFIREWALL_GEOIP_PHP_COMPAT_ERROR_DESC', PHP_VERSION); ?></p>
	</div>
<?php 
} else {
	JText::script('COM_RSFIREWALL_DOWNLOAD_GEOIP_SERVER_ERROR');
	JText::script('COM_RSFIREWALL_GEOIP_DB_CANNOT_DOWNLOAD');
	JText::script('COM_RSFIREWALL_GEOIP_DB_CANNOT_DOWNLOAD_CONTINUED');
    JText::script('COM_RSFIREWALL_GEOIP_DB_TRY_TO_DOWNLOAD_MANUALLY');

	$blocked_countries = $this->config->get('blocked_countries');
	$class = in_array('US', $blocked_countries) ? '' : 'com-rsfirewall-hidden';



	// set description if required
	if (isset($this->fieldset->description) && !empty($this->fieldset->description)) { ?>
		<div class="com-rsfirewall-tooltip"><?php echo JText::_($this->fieldset->description); ?><br />
		<a href="https://www.rsjoomla.com/support/documentation/rsfirewall-user-guide/frequently-asked-questions/how-do-i-use-country-blocking-and-where-do-i-get-geoipdat-.html" target="_blank"><?php echo JText::_('COM_RSFIREWALL_GEOIP_DOCUMENTATION_LINK'); ?></a></div>

		<?php if ($this->geoip->works) { ?>
			<div class="alert alert-success rsfirewall-geoip-works">
				<?php echo JText::_('COM_RSFIREWALL_GEOIP_SETUP_CORRECTLY'); ?>
			</div>
		<?php } ?>

		<?php if (!$this->geoip->mmdb) { ?>
			<div class="alert alert-info">
				<h4><?php echo JText::_('COM_RSFIREWALL_GEOIP_LITE_DB'); ?></h4>
				<p><?php echo JText::_('COM_RSFIREWALL_GEOIP_DB_LITE_DOWNLOAD_INSTRUCTIONS'); ?></p>
				<p><a class="btn btn-primary" onclick="RSFirewall.GeoIPDownload(this)"><i class="icon-refresh"></i> <?php echo JText::_('COM_RSFIREWALL_DOWNLOAD_GEOIP_DB_LITE'); ?></a></p>
			</div>
		<?php } elseif (!empty($this->geoip->mmdb_old)) { ?>
			<div class="alert alert-info">
				<h4><?php echo JText::_('COM_RSFIREWALL_GEOIP_LITE_DB'); ?></h4>
				<p><?php echo JText::sprintf('COM_RSFIREWALL_GEOIP_DB_UPDATE_INSTRUCTIONS', $this->geoip->mmdb_modified); ?></p>
				<p><a class="btn btn-primary" onclick="RSFirewall.GeoIPDownload(this)"><i class="icon-refresh"></i> <?php echo JText::_('COM_RSFIREWALL_UPDATE_GEOIP_DB_LITE'); ?></a></p>
			</div>
		<?php } ?>
	<?php } ?>
		<div class="alert alert-danger <?php echo $class ?>" id="us-country-blocked">
			<?php echo JText::_('COM_RSFIREWALL_YOU_BANNED_US'); ?>
		</div>
	<?php
	$this->field->startFieldset();
	foreach ($this->fields as $field) {
		if ($field->fieldname == 'geoip_upload') {
			continue;
		}
		
		$input = $field->input;
		
		// Let's disable the checkboxes if GeoIP is not available
		if (!$this->geoip->works) {
			$input = str_replace('type="checkbox"', 'type="checkbox" disabled', $field->input);		
		}

		$this->field->showField($field->hidden ? '' : $field->label, $input);
	}
	$this->field->endFieldset();
}