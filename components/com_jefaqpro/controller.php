<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// No direct access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

/**
 * JE FAQPro Component Controller
 */
class jefaqproController extends JControllerLegacy
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Method to display a view.
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$cachable		= true;

		// Get the document object.
			$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
			$vName		= JRequest::getCmd('view', 'faqs');
			JRequest::setVar('view', $vName);

		$user			= JFactory::getUser();

		$safeurlparams = array('catid'=>'INT','id'=>'INT','cid'=>'ARRAY','year'=>'INT','month'=>'INT','limit'=>'INT','limitstart'=>'INT',
			'showall'=>'INT','return'=>'BASE64','filter'=>'STRING','filter_order'=>'CMD','filter_order_Dir'=>'CMD','filter-search'=>'STRING','print'=>'BOOLEAN','lang'=>'CMD');

		parent::display($cachable,$safeurlparams);

		return $this;
	}

	public function hits()
	{
		$faqid 							= JRequest::getInt('faqid', 0);
		$viewName						= JRequest::getString('view', $this->default_view);
		$model							= $this->getModel($viewName);

		$model->storeHits($faqid);
	}

	public function responselike()
	{
		$app							= JFactory::getApplication();
		$params 						= $app->getParams();

		$settings						= $params->get('add_votes');
		$app							= JFactory::getApplication();
		$viewName						= JRequest::getString('view', $this->default_view);
		$model							= $this->getModel($viewName);

		$faqid							= JRequest::getInt('faqid', 0);
		$cookieName						= JApplication::getHash( $app->getName() . 'faq' . $faqid );

		// ToDo - may be adding those information to the session?
			$voted						= JRequest::getVar( $cookieName, '0', 'COOKIE', 'INT');

		if( $settings == 1 ) {
			$response_already			= $model->getTotalresponse( $like=true, $faqid );
			$user						= JFactory::getUser();
			if( $user->get('id') > 0 ) {
				$user_voted				= $model->getResponsebyuser($faqid);
				if($user_voted) {
					echo $response_already."|".JText::_('JE_USERALREADYRESPONSEADDED');
				} else {
					$response_add		= $model->storeResponses($faqid, $like=true);
					echo $response_add.'|'.'';
				}
			} else {
				echo $response_already.'|'.JText::_('JE_PLEASELOGIN');
			}
		} else {
			if ($voted) {
				if($voted) {
					$response_already	= $model->getTotalresponse( $like=true, $faqid );
					echo $response_already.'|'.JText::_('JE_ALREADYRESPONSEADDED');
				}
			} else {
				$time_hr				= 86400;
				setcookie( $cookieName, '1', time()+$time_hr );
				$response_add			= $model->storeResponses($faqid, $like=true);

				echo $response_add.'|'.'';
			}
		}

		exit;
	}

	public function responsedislike()
	{
		$app							= JFactory::getApplication();
		$params 						= $app->getParams();

		$settings						= $params->get('add_votes');
		$app							= JFactory::getApplication();
		$viewName						= JRequest::getString('view', $this->default_view);
		$model							= $this->getModel($viewName);

		$faqid							= JRequest::getInt('faqid', 0);
		$cookieName						= JApplication::getHash( $app->getName() . 'faq' . $faqid );

		// ToDo - may be adding those information to the session?
			$voted						= JRequest::getVar( $cookieName, '0', 'COOKIE', 'INT');

		if( $settings == 1 ) {
			$response_already			= $model->getTotalresponse( $like=false, $faqid );
			$user						= JFactory::getUser();
			if( $user->get('id') > 0 ) {
				$user_voted				= $model->getResponsebyuser($faqid);
				if($user_voted) {
					echo $response_already."|".JText::_('JE_USERALREADYRESPONSEADDED');
				} else {
					$response_add		= $model->storeResponses($faqid, $like=false);
					echo $response_add.'|'.'';
				}
			} else {
				echo $response_already.'|'.JText::_('JE_PLEASELOGIN');
			}
		} else {
			if ($voted) {
				if($voted) {
					$response_already	= $model->getTotalresponse( $like=false, $faqid );
					echo $response_already.'|'.JText::_('JE_ALREADYRESPONSEADDED');
				}
			} else {
				$time_hr				= 86400;
				setcookie( $cookieName, '1', time()+$time_hr );
				$response_add			= $model->storeResponses($faqid, $like=false);

				echo $response_add.'|'.'';
			}
		}

		exit;
	}
}
