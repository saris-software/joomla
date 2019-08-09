<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallViewLists extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $filterbar;
	protected $sidebar;
	protected $dropdown;
	
	public function display($tpl = null) {
		$user = JFactory::getUser();
		if (!$user->authorise('lists.manage', 'com_rsfirewall')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsfirewall', false));
		}
		
		$this->addToolBar();

		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state 		= $this->get('State');
		
		$this->filterbar	= $this->get('FilterBar');		
		$this->sidebar 		= $this->get('SideBar');
		$this->dropdown		= $this->get('Dropdown');
		
		// Load GeoIP helper class
		require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/geoip/geoip.php';
		$this->geoip = RSFirewallGeoIP::getInstance();
		
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		// set title
		JToolbarHelper::title('RSFirewall!', 'rsfirewall');
		
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSFirewallToolbarHelper::addToolbar('lists');
		
		JToolbarHelper::addNew('list.add');
		JToolbarHelper::addNew('list.bulkadd', JText::_('COM_RSFIREWALL_BULK_ADD'));
		JToolbarHelper::editList('list.edit');
		JToolbarHelper::divider();
		JToolbarHelper::publish('lists.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('lists.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolbarHelper::divider();
		JToolbarHelper::deleteList('COM_RSFIREWALL_CONFIRM_DELETE', 'lists.delete');
	}
}