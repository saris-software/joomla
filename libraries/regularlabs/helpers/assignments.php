<?php
/**
 * @package         Regular Labs Library
 * @version         17.5.25583
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2017 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/* @DEPRECATED */

defined('_JEXEC') or die;

if (is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';
}

use RegularLabs\Library\Conditions as RL_Conditions;

class RLAssignmentsHelper
{
	function passAll($assignments, $matching_method = 'all', $article = 0)
	{
		return RL_Conditions::pass($assignments, $matching_method, $article);
	}

	public function getAssignmentsFromParams(&$params)
	{
		return RL_Conditions::getConditionsFromParams($params);
	}

	public function getAssignmentsFromTagAttributes(&$params, $types = [])
	{
		return RL_Conditions::getConditionsFromTagAttributes($params, $types);
	}

	public function hasAssignments(&$assignments)
	{
		return RL_Conditions::hasConditions($assignments);
	}
}
