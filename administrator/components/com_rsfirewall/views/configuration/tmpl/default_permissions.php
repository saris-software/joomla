<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

// set description if required
if (isset($this->fieldset->description) && !empty($this->fieldset->description)) { ?>
	<div class="com-rsfirewall-tooltip"><?php echo JText::_($this->fieldset->description); ?></div>
<?php } ?>
<?php
$this->field->startFieldset();
foreach ($this->fields as $field) {
	$this->field->showField('', $field->input);
}
$this->field->endFieldset();
?>
<script type="text/javascript">
function getUrlParam(variable) {
	switch (variable)
	{
		default:
			return false;
		break;
		
		case 'option':
			return 'com_config';
		break;
		
		case 'view':
			return 'component';
		break;
		
		case 'component':
			return 'com_rsfirewall';
		break;
	}
}
</script>