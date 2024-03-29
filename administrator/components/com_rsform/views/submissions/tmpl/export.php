<?php
/**
* @package RSForm! Pro
* @copyright (C) 2007-2019 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHtml::_('bootstrap.tooltip');
JText::script('ERROR');
JText::script('RSFP_EXPORT_PLEASE_SELECT');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'submissions.export.task')
        {
            var isChecked = jQuery('input[id^=header]:checked').length > 0;

            if (!isChecked)
            {
                var messages = {"error": []};
                messages.error.push(Joomla.JText._('RSFP_EXPORT_PLEASE_SELECT'));
                Joomla.renderMessages(messages);
                return false;
            }
        }

        Joomla.submitform(task);
    };

    function updateCSVPreview()
    {
        <?php if ($this->exportType == 'csv') { ?>
        var form = document.adminForm;
        var headersPre = document.getElementById('headersPre');
        var rowPre = document.getElementById('rowPre');
        var delimiter = form.ExportDelimiter.value;
        var enclosure = form.ExportFieldEnclosure.value;
        var totalHeaders = <?php echo count($this->previewArray); ?>;

        var headers = [];
        var previewArray = [];
        var orderArray = [];

        for (var i=1; i<=totalHeaders; i++)
            if (document.getElementById('header' + i).checked)
            {
                var header = document.getElementById('header' + i).value;

                var order = document.getElementsByName('ExportOrder[' + header + ']')[0].value;
                orderArray.push(order + '_' + header);
            }

        orderArray.sort(function (a,b) {
                a = a.split('_');
                a = a[0];
                b = b.split('_');
                b = b[0];
                return a - b;
            });

        for (var i=0; i<orderArray.length; i++)
        {
            var header = orderArray[i].split('_');
            var header = enclosure + header[1] + enclosure;

            headers.push(header);
        }

        headersPre.innerHTML = headers.join(delimiter);
        headersPre.style.display = form.ExportHeaders.checked ? '' : 'none';

        for (var i=1; i<=headers.length; i++)
        {
            var item = enclosure + 'Value ' + i + enclosure;
            previewArray.push(item);
        }

        rowPre.innerHTML = previewArray.join(delimiter);
        <?php } ?>
    }

    function toggleCheckColumns()
    {
        var tocheck = document.getElementById('checkColumns').checked;
        var totalHeaders = <?php echo count($this->previewArray); ?>;

        for (var i=1; i<=totalHeaders; i++)
            document.getElementById('header' + i).checked = tocheck;

        updateCSVPreview();
    }
</script>

<form action="index.php?option=com_rsform" method="post" id="adminForm" name="adminForm">
	<?php
    if ($this->exportType == 'csv') {
        // prepare the content
        echo $this->loadTemplate('preview');
    }
	// add the tab title
	$this->tabs->addTitle(JText::_('RSFP_EXPORT_SELECT_FIELDS'), 'export-fields');
	// prepare the content
	$content = $this->loadTemplate('fields');
	// add the tab content
	$this->tabs->addContent($content);

	// add the tab title
	$this->tabs->addTitle(JText::_($this->exportType == 'csv' ? 'RSFP_EXPORT_CSV_OPTIONS' : 'RSFP_EXPORT_OPTIONS'), 'export-options');
	// prepare the content
	$content = $this->loadTemplate('options');
	// add the tab content
	$this->tabs->addContent($content);
	
	// render tabs
	$this->tabs->render();
	?>
	
	<input type="hidden" name="task" value="submissions.export.task" />
	<input type="hidden" name="exportType" value="<?php echo $this->exportType; ?>" />
	<input type="hidden" name="option" value="com_rsform" />
	<input type="hidden" name="formId" value="<?php echo $this->formId; ?>" />
	<input type="hidden" name="ExportFile" value="<?php echo $this->exportFile; ?>" />
</form>

<script type="text/javascript">updateCSVPreview();</script>
<?php JHtml::_('behavior.keepalive'); ?>