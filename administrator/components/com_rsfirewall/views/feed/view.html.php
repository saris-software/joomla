<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallViewFeed extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $field;
	
	public function display($tpl = null) {
		$user = JFactory::getUser();
		if (!$user->authorise('feeds.manage', 'com_rsfirewall')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsfirewall', false));
		}
		
		$this->addToolBar();
		
		$this->form	= $this->get('Form');
		$this->item	= $this->get('Item');
		
		$this->field = $this->get('RSFieldset');
		
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		// set title
		JToolbarHelper::title('RSFirewall!', 'rsfirewall');
		
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSFirewallToolbarHelper::addToolbar('feeds');
		
		JToolbarHelper::apply('feed.apply');
		JToolbarHelper::save('feed.save');
		JToolbarHelper::save2new('feed.save2new');
		JToolbarHelper::save2copy('feed.save2copy');
		JToolbarHelper::cancel('feed.cancel');
	}
}