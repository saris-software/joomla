<?php
/**
 * @package   AdminTools
 * @copyright Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\AdminTools\Admin\View\SEOAndLinkTools;

defined('_JEXEC') or die;

use Akeeba\AdminTools\Admin\Model\SEOAndLinkTools;
use FOF30\View\DataView\Html as BaseView;

class Html extends BaseView
{
	/**
	 * The configuration for this feature
	 *
	 * @var  array
	 */
	public $salconfig;

	protected function onBeforeMain()
	{
		/** @var SEOAndLinkTools $model */
		$model  = $this->getModel();
		$config = $model->getConfig();

		$this->salconfig = $config;
	}
}
