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

jimport('joomla.application.component.controlleradmin');

class jefaqproControllerFaqs extends JControllerAdmin
{
	/**
	 * Constructor.
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('sticky_unpublish',	'sticky_publish');
	}

	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = 'Faq', $prefix = 'jefaqproModel', $config = array('ignore_request' => true))
	{
		$model	= parent::getModel($name, $prefix, $config);

		return $model;
	}

	public function saveOrderAjax()
	{
		// Get the input

		$input = JFactory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
}
?>
