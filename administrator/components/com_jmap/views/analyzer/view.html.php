<?php
// namespace administrator\components\com_jmap\views\analyzer;
/**
 * @package JMAP::ANALYZER::administrator::components::com_jmap
 * @subpackage views
 * @subpackage analyzer
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
 
/**
 * @package JMAP::ANALYZER::administrator::components::com_jmap
 * @subpackage views
 * @subpackage analyzer
 * @since 2.3.3
 */
class JMapViewAnalyzer extends JMapView {
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-jmap{background-image:url("components/com_jmap/images/icon-48-data.png")}');
		JToolBarHelper::title( JText::_( 'COM_JMAP_SITEMAP_ANALYZER' ), 'jmap' );
			
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_JMAP_CPANEL', false);
	}
	
	/**
	 * Creates a dropdown box for selecting how many records to show per page with override
	 *
	 * @return  string  The HTML for the limit # input box.
	 */
	protected function getLimitBox() {
		$limits = array();
		$limit = $this->getModel ()->getState ( 'limit' );
	
		// Make the option list.
		for ($i = 5; $i <= 30; $i += 5)
		{
			$limits[] = JHtml::_('select.option', "$i");
		}
	
		$limits[] = JHtml::_('select.option', '50', JText::_('J50'));
		$limits[] = JHtml::_('select.option', '100', JText::_('J100'));
		$limits[] = JHtml::_('select.option', '200', JText::_('J200'));
		$limits[] = JHtml::_('select.option', '500', JText::_('J500'));
		$limits[] = JHtml::_('select.option', '1000', '1000');
		$limits[] = JHtml::_('select.option', '0', JText::_('JALL'));
	
		$selected = $limit == 0 ? 0 : $limit;
	
		// Build the select list.
		$html = JHtml::_(
				'select.genericlist',
				$limits,
				'limit',
				'class="inputbox input-small" size="1" onchange="Joomla.submitform();"',
				'value',
				'text',
				$selected
		);
	
		return $html;
	}
	
	/**
	 * Default display listEntities
	 *        	
	 * @access public
	 * @param string $tpl
	 * @return void
	 */
	public function display($tpl = null) {
		// Get main records
		$rows = $this->get ( 'Data' );
		$lists = $this->get ( 'Filters' );
		$total = $this->get ( 'Total' );
		$this->validationType = (int)($this->getModel()->getComponentParams()->get('linksanalyzer_validation_analysis', 2));
		
		$doc = JFactory::getDocument();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		$doc->addScript ( JUri::root ( true ) . '/administrator/components/com_jmap/js/analyzer.js' );
		$doc->addScriptDeclaration("var jmap_baseURI='" . JUri::root() . "';");
		$doc->addScriptDeclaration("var jmap_validationAnalysis=" . $this->validationType . ";");
		
		// Inject js translations
		$translations = array (
				'COM_JMAP_ANALYZER_TITLE',
				'COM_JMAP_ANALYZER_PROCESS_RUNNING',
				'COM_JMAP_ANALYZER_STARTED_SITEMAP_GENERATION',
				'COM_JMAP_ANALYZER_ERROR_STORING_FILE',
				'COM_JMAP_ANALYZER_GENERATION_COMPLETE',
				'COM_JMAP_ANALYZER_ANALYZING_LINKS',
				'COM_JMAP_ANALYZER_INDEXED_LINK',
				'COM_JMAP_ANALYZER_NOAVAILABLE_LINK',
				'COM_JMAP_ANALYZER_NOINDEXED_LINK',
				'COM_JMAP_ANALYZER_LINKVALID',
				'COM_JMAP_ANALYZER_LINK_NOVALID',
				'COM_JMAP_ANALYZER_NOINFO');
		$this->injectJsTranslations($translations, $doc);
						
		$orders = array ();
		$orders ['order'] = $this->getModel ()->getState ( 'order' );
		$orders ['order_Dir'] = $this->getModel ()->getState ( 'order_dir' );
		// Pagination view object model state populated
		$pagination = new JPagination ( $total, $this->getModel ()->getState ( 'limitstart' ), $this->getModel ()->getState ( 'limit' ) );
		
		$this->user = JFactory::getUser ();
		$this->pagination = $pagination;
		$this->link_type = $this->getModel ()->getState ('link_type', null);
		$this->cparams = $this->getModel()->getComponentParams();
		$this->dataRole = $this->cparams->get('linksanalyzer_indexing_analysis', 1) ? 'link' : 'neutral';
		$this->lists = $lists;
		$this->orders = $orders;
		$this->items = $rows;
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		parent::display ( 'list' );
	}
}