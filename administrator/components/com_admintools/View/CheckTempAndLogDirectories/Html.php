<?php
/**
 * @package   AdminTools
 * @copyright Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\AdminTools\Admin\View\CheckTempAndLogDirectories;

defined('_JEXEC') or die;

use FOF30\View\DataView\Html as BaseView;
use JHtml;
use JText;

class Html extends BaseView
{
	protected function onBeforeMain()
	{
		JHtml::_('behavior.modal');
		$this->addJavascriptFile('admin://components/com_admintools/media/js/CheckTempAndLogDirectories.min.js');
		
		JText::script('COM_ADMINTOOLS_LBL_CHECKTEMPANDLOGDIRECTORIES_CHECKCOMPLETED', true);
	}

}
