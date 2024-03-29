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

/**
 * Class AgentDevice
 * @package RegularLabs\Library\Condition
 */
class AgentDevice
	extends Agent
{
	public function pass()
	{
		$pass = (in_array('mobile', $this->selection) && $this->isMobile())
			|| (in_array('tablet', $this->selection) && $this->isTablet())
			|| (in_array('desktop', $this->selection) && $this->isDesktop());

		return $this->_($pass);
	}
}
