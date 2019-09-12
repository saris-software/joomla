<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

class RseventsproViewSubscription extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $params;
	protected $fields;
	protected $tickets;
	
	public function display($tpl = null) {		
		$this->layout	= $this->getLayout();
		$this->document	= JFactory::getDocument();
		
		if ($this->layout == 'seats') {
			JHtml::stylesheet('com_rseventspro/tickets.css', array('relative' => true, 'version' => 'auto'));
			
			$eventId		= $this->getEventId();
			$this->tickets	= rseventsproHelper::getTickets($eventId);
			$this->id		= JFactory::getApplication()->input->getInt('id',0);
			
		} else if ($this->layout == 'tickets') {
			$this->type			= $this->get('Type');
			$this->id			= JFactory::getApplication()->input->getInt('id',0);
			
			if ($this->type) {
				JHtml::script('com_rseventspro/jquery-ui.min.js', array('relative' => true, 'version' => 'auto'));
				JHtml::stylesheet('com_rseventspro/tickets.css', array('relative' => true, 'version' => 'auto'));
			}
			
			$this->tickets	= rseventsproHelper::getTickets($this->id);
			
		} else {
			$this->form 		= $this->get('Form');
			$this->item 		= $this->get('Item');
			$this->fields 		= $this->get('Fields');
			$this->events 		= $this->get('Events');
			$this->tickets 		= $this->get('Tickets');
			$this->params		= $this->item->gateway == 'offline' ? $this->get('Card') : $this->item->params;
			
			$this->addToolBar();
		}
		
		JFactory::getApplication()->triggerEvent('rsepro_adminSubscriptionDisplayLayout', array(array('view' => &$this)));
		
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSEVENTSPRO_ADD_EDIT_SUBSCRIPTION'),'rseventspro48');
		JToolBarHelper::apply('subscription.apply');
		JToolBarHelper::save('subscription.save');
		JToolBarHelper::save2new('subscription.save2new');
		JToolBarHelper::cancel('subscription.cancel');
		
		JHtml::_('rseventspro.chosen','select');
	}
	
	protected function getEvent($id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->clear()
			->select($db->qn('id'))->select($db->qn('name'))->select($db->qn('allday'))
			->select($db->qn('start'))->select($db->qn('end'))
			->select($db->qn('ticketsconfig'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $id);
		
		$db->setQuery($query);
		return $db->loadObject();
		
	}
	
	protected function getEventId() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$id = JFactory::getApplication()->input->getInt('id',0);
		
		$query->clear()
			->select($db->qn('ide'))
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('id').' = '.(int) $id);
		
		$db->setQuery($query);
		return $db->loadResult();
	}
}