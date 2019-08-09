<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSFirewallHtml
{
	public static function script()
	{
		$args = func_get_args();

		if (version_compare(JVERSION, '3.7.0', '<'))
		{
			if ($args)
			{
				if (isset($args[0]) && strpos($args[0], 'rsfirewall') !== false)
				{
					if (is_array($args[1]))
					{
						$file 			= $args[0];
						$options 		= $args[1];
						$framework		= !empty($options['framework']);
						$relative		= !empty($options['relative']);
						$path_only		= !empty($options['pathOnly']);
						$detect_browser = !empty($options['detectBrowser']);
						$detect_debug 	= !empty($options['detectDebug']);
						$args 			= array($file, $framework, $relative, $path_only, $detect_browser, $detect_debug);
					}
				}
			}
		}

		array_unshift($args, 'script');

		return call_user_func_array(array('JHtml', '_'), $args);
	}

	public static function stylesheet()
	{
		$args = func_get_args();

		if (version_compare(JVERSION, '3.7.0', '<'))
		{
			if ($args)
			{
				if (isset($args[0]) && strpos($args[0], 'rsfirewall') !== false)
				{
					if (is_array($args[1]))
					{
						$file 			= $args[0];
						$options 		= $args[1];
						$attribs		= isset($args[2]) ? $args[2] : array();
						$relative		= !empty($options['relative']);
						$path_only		= !empty($options['pathOnly']);
						$detect_browser = !empty($options['detectBrowser']);
						$detect_debug 	= !empty($options['detectDebug']);
						$args 			= array($file, $attribs, $relative, $path_only, $detect_browser, $detect_debug);
					}
				}
			}
		}

		array_unshift($args, 'stylesheet');

		return call_user_func_array(array('JHtml', '_'), $args);
	}

	public static function registerFunctions()
	{
		if (!JHtml::isRegistered('rsfirewall_script') || !JHtml::isRegistered('rsfirewall_stylesheet'))
		{
			JHtml::register('rsfirewall_script', array('RSFirewallHtml', 'script'));
			JHtml::register('rsfirewall_stylesheet', array('RSFirewallHtml', 'stylesheet'));
		}
	}
}