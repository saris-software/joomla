<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted access');

// Import Joomla predefined functions
	jimport('joomla.application.component.modelitem');

class jefaqproModelFaqs extends JModelItem
{
	/**
	 * total faqs
	 */
	var $_total									= null;

	/**
	 * Pagination object
	 */
	var $_pagination							= null;

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		//Get configuration
			$app								= JFactory::getApplication();
			$config								= JFactory::getConfig();

		// Get the pagination request variables
			$this->setState('limit', $app->getUserStateFromRequest('com_jefaqpro.limit', 'limit', $config->get('list_limit'), 'int'));
			$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$settings								= $this->getSettings();
		$order_by								= $settings->orderby;
		$sort_by								= $settings->sortby;
		
		if($order_by==='random')
			$order								= 'rand()';
		else
			$order								= 'faq.'.$order_by;

		$this->setState('list.ordering', $order);

		$this->setState('list.direction', $sort_by);
	}

	/**
	 * Method to get FAQ data.
	 */
	public function getItems()
	{
		// Connect db
		$db									= $this->getDbo();
		$query								= $db->getQuery(true);
		$user								= JFactory::getUser();
		$groups								= implode(',', $user->getAuthorisedViewLevels());
		$lang 								= JFactory::getLanguage();
		$id									= (int) JRequest::getvar("id");

		$query->select('faq.*');
		$query->from('#__jefaqpro_faq AS faq');
		$query->where('faq.access IN ('.$groups.')');
		$query->where('faq.language IN( \''.$lang->getTag().'\',\'*\')');
		if($id)
			$query->where('faq.id = '.$id);
		$db->setQuery($query);
		$faq									= $db->loadObjectList();

		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}

		if( empty($faq) ) {
			JError::raiseNotice(404, JText::_('COM_JEFAQPOR_ERROR_FAQS_NOT_FOUND'));
		} else {
			$query->join( 'LEFT', '#__categories AS cat ON cat.id = faq.catid' );
			$query->where('cat.published = 1');
			$query->where('cat.access IN ('.$groups.')');
			$query->where('faq.published = 1');
			$query->order($db->escape($this->getState('list.ordering', 'faq.ordering')).' '.$db->escape($this->getState('list.direction', 'desc')));

			$faqs								= $db->loadObjectList();

			if( empty( $faqs )) {
				JError::raiseNotice(404, JText::_('COM_JEFAQPOR_ERROR_FAQS_NOT_PUBLISHED'));
			}

			$this->_total						= count($faqs);

			if ($this->getState('limit') > 0) {
				$this->_data					= array_splice($faqs, $this->getState('limitstart'), $this->getState('limit'));
			} else {
				$this->_data					= $faqs;
			}

			return $this->_data;
		}
	}

	/**
	 * Method to get the total number of weblink items for the category
	 */
	function getTotal()
	{
		return $this->_total;
	}

	/**
	 * Method to get a pagination object of the weblink items for the category
	 */
	function getPagination()
	{
		// Lets load the content if it doesn't already exist
			if (empty($this->_pagination))
			{
				jimport('joomla.html.pagination');
				$this->_pagination				= new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
			}

		return $this->_pagination;
	}

	public function storeHits( $faqid )
	{
		$db			= $this->getDbo();

		$db->setQuery( 'UPDATE #__jefaqpro_faq' .
		        ' SET hits = hits + 1' .
		        ' WHERE id = '.(int) $faqid
		);

		if (!$db->query()) {
		     $this->setError(JText::_('JE_QUERY_ERROR'));
		     return false;
		}

		$faqs  		= $this->getTable('Faq', 'jefaqproTable');
		$faqs->load($faqid);

		echo JText::_('JE_HITS').'&nbsp; '.$faqs->hits;

		exit;
	}

	public function storeResponses( $faqid, $like )
	{
		$response				= 0;
		$faq_like				= 0;
		$faq_dislike			= 0;

		$faqs  					= JTable::getInstance('Response', 'jefaqproTable');
		$post					= array();
		$db						= $this->getDbo();
		$user					= JFactory::getUser();
		$userid					= $user->get('id');
		$query					= $db->getQuery(true);

		if ($like) {
			$faq_like			= 1;
		} else {
			$faq_dislike		= 1;
		}

		$post['faqid'] 			= $faqid;
		$post['userid'] 		= $userid;
		$post['response_yes'] 	= $faq_like;
		$post['response_no'] 	= $faq_dislike;

		$faqs->save($post);

		if ($like) {
			$query->select('SUM(response_yes)');
		} else {
			$query->select('SUM(response_no)');
		}

		$query->where('faqid = '.$faqid);
		$query->from('#__jefaqpro_responses');
		$db->setQuery($query);
		$response			= $db->loadResult();

		return $response;
	}

	public function getTotalresponse( $like, $faqid )
	{
		$faq				= 0;
		$db					= $this->getDbo();
		$query				= $db->getQuery(true);

		if ($like) {
			$query->select('SUM(response_yes)');
		} else {
			$query->select('SUM(response_no)');
		}

		$query->where('faqid = '.$faqid);
		$query->from('#__jefaqpro_responses');
		$db->setQuery($query);
		$faq				= $db->loadResult();

		if ( $faq == '' || $faq == null ) {
			$faq = 0;
		}

		return $faq;
	}

	public function getResponsebyuser($faqid)
	{
		$faq				= 0;
		$db					= $this->getDbo();
		$user				= JFactory::getUser();
		$userid				= $user->get('id');

		$query				= $db->getQuery(true);
		$query->select('count(id)');
		$query->where('faqid = '.$faqid);
		$query->where('userid = '.$userid);
		$query->from('#__jefaqpro_responses');
		$db->setQuery($query);
		$count				= $db->loadResult();

		if( $count > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	public function getSettings()
	{
		$id					= 1;
		$settings  			= JTable::getInstance('Settings', 'jefaqproTable');
		$settings->load($id);

		return $settings;
	}
}
?>
