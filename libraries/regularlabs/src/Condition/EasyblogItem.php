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
 * Class EasyblogItem
 * @package RegularLabs\Library\Condition
 */
class EasyblogItem
	extends Easyblog
{
	public function pass()
	{
		if (!$this->request->id || $this->request->option != 'com_easyblog' || $this->request->view != 'entry')
		{
			return $this->_(false);
		}

		$pass = false;

		// Pass Article Id
		if (!$this->passItemByType($pass, 'ContentId'))
		{
			return $this->_(false);
		}

		// Pass Content Keywords
		if (!$this->passItemByType($pass, 'ContentKeyword'))
		{
			return $this->_(false);
		}

		// Pass Author
		if (!$this->passItemByType($pass, 'Author'))
		{
			return $this->_(false);
		}

		return $this->_($pass);
	}
}
