<?php
/**
 * @package         Advanced Module Manager
 * @version         7.1.4
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2017 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

if (!is_file(__DIR__ . '/vendor/autoload.php'))
{
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

use RegularLabs\AdvancedModules\Plugin;
use RegularLabs\Library\Protect as RL_Protect;

class PlgSystemAdvancedModules extends Plugin
{
	public $_alias       = 'advancedmodules';
	public $_title       = 'ADVANCED_MODULE_MANAGER';
	public $_lang_prefix = 'AMM';

	public $_page_types      = ['html'];
	public $_enable_in_admin = true;

	public function extraChecks()
	{
		if (!RL_Protect::isComponentInstalled('advancedmodules'))
		{
			return false;
		}

		return true;
	}
}

