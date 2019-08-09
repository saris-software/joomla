<?php
// namespace administrator\components\com_jmap\views\google;
/**
 * @package JMAP::GOOGLE::administrator::components::com_jmap
 * @subpackage views
 * @subpackage google
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
 
/**
 * @package JMAP::GOOGLE::administrator::components::com_jmap
 * @subpackage views
 * @subpackage google
 * @since 4.6.5
 */
class JMapViewGoogle extends JMapView {
	/**
	 * Default display listEntities
	 *        	
	 * @access public
	 * @param string $tpl
	 * @return void
	 */
	public function display($tpl = null) {
 		if($this->getModel()->getState('googlestats', 'analytics') == 'alexarender') {
			echo $this->get ( 'DataAlexa' );
		}
		
		if($this->getModel()->getState('googlestats', 'analytics') == 'hypestatrender') {
			echo $this->get ( 'DataHypeStat' );
		}
		
		if($this->getModel()->getState('googlestats', 'analytics') == 'searchmetricsrender') {
			echo $this->get ( 'DataSearchMetrics' );
		}
	}
}