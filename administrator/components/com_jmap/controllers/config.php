<?php
// namespace administrator\components\com_jmap\controllers;
/**
 *
 * @package JMAP::CONFIG::administrator::components::com_jmap
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Config controller concrete implementation
 *
 * @package JMAP::CONFIG::administrator::components::com_jmap
 * @subpackage controllers
 * @since 1.0
 */
class JMapControllerConfig extends JMapController {

	/**
	 * Show configuration
	 * @access public
	 * @param $cachable string
	 *       	 the view output will be cached
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) {
		// Access check.
		if (!$this->allowAdmin($this->option)) {
			$this->setRedirect('index.php?option=com_jmap&task=cpanel.display', JText::_('COM_JMAP_ERROR_ALERT_NOACCESS'));
			return false;
		}
		parent::display($cachable);
	}

	/**
	 * Save config entity
	 * @access public
	 * @return void
	 */
	public function checkEntityCrawler() {
		$model = $this->getModel ();
		
		// Get view and pushing model
		$view = $this->getView ();
		$view->setModel ( $model, true );
		
		// Call edit view
		$view->checkCrawler ('crawler');
	}

	/**
	 * Save config entity
	 * @access public
	 * @return void
	 */
	public function saveEntity() {
		$model = $this->getModel();
		
		if(!$model->storeEntity()) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError(null, false);
			$this->app->enqueueMessage($modelException->getMessage(), $modelException->getErrorLevel());
			$this->setRedirect ( 'index.php?option=com_jmap&task=config.display', JText::_('COM_JMAP_ERROR_SAVING_PARAMS'));
			return false;
		}
		$this->setRedirect( 'index.php?option=com_jmap&task=config.display', JText::_('COM_JMAP_SAVED_PARAMS'));
	}
	
	/**
	 * Export sources as db table entities
	 *
	 * @access public
	 * @return void
	 */
	public function exportConfig() {
		$option = $this->option;
		// Access check
		if (! $this->allowEdit ( $option )) {
			$this->setRedirect ( 'index.php?option=com_jmap&task=config.display', JText::_ ( 'COM_JMAP_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
	
		// Get the file manager instance with db connector dependency injection
		$filesManager = new JMapFileConfig( JFactory::getDbo (), $this->app );
	
		if (! $filesManager->export ()) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$filesManagerException = $filesManager->getError ( null, false );
			$this->app->enqueueMessage ( $filesManagerException->getMessage (), $filesManagerException->getErrorLevel () );
			$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_ ( 'COM_JMAP_ERROR_CONFIG_EXPORT' ) );
			return false;
		}
	
		$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_ ( 'COM_JMAP_SUCCESS_CONFIG_EXPORT' ) );
	}
	
	/**
	 * Import sources as db table entities
	 *
	 * @access public
	 * @return void
	 */
	public function importConfig() {
		$option = $this->option;
		// Access check
		if (! $this->allowEdit ( $option )) {
			$this->setRedirect ( 'index.php?option=com_jmap&task=config.display', JText::_ ( 'COM_JMAP_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
	
		// Get the file manager instance with db connector dependency injection
		$filesManager = new JMapFileConfig ( JFactory::getDbo (), $this->app );
	
		if (! $filesManager->import ()) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$filesManagerException = $filesManager->getError ( null, false );
			$this->app->enqueueMessage ( $filesManagerException->getMessage (), $filesManagerException->getErrorLevel () );
			$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_ ( 'COM_JMAP_ERROR_CONFIG_IMPORT' ) );
			return false;
		}
	
		$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_ ( 'COM_JMAP_SUCCESS_CONFIG_IMPORT' ) );
	}
}
?>