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

namespace RegularLabs\Library\Condition;

defined('_JEXEC') or die;

use JFactory;

/**
 * Class UserUser
 * @package RegularLabs\Library\Condition
 */
class UserUser
	extends User
{
	public function pass()
	{
		return $this->passSimple(JFactory::getUser()->get('id'));
	}
}
