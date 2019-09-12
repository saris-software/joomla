<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

class RsfirewallController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false) {
		// View caching logic -- simple... are we logged in?
		$user = JFactory::getUser();
		if ($user->get('id')) {
			parent::display(false, $urlparams);
		} else {
			parent::display(true, $urlparams);
		}
	}
}