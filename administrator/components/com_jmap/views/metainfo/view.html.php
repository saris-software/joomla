<?php
// namespace administrator\components\com_jmap\views\metainfo;
/**
 * @package JMAP::METAINFO::administrator::components::com_jmap
 * @subpackage views
 * @subpackage metainfo
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
 
/**
 * @package JMAP::METAINFO::administrator::components::com_jmap
 * @subpackage views
 * @subpackage metainfo
 * @since 3.2
 */
class JMapViewMetainfo extends JMapView {
	/**
	 * Add the page title and toolbar.
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-jmap{background-image:url("components/com_jmap/images/icon-48-data.png")}');
		JToolBarHelper::title( JText::_( 'COM_JMAP_SITEMAP_METAINFO' ), 'jmap' );
		
		if ($this->user->authorise('core.edit', 'com_jmap')) {
			JToolBarHelper::custom('metainfo.exportEntities', 'download', 'download', 'COM_JMAP_EXPORT_META', false);
			JToolBarHelper::custom('metainfo.importEntities', 'upload', 'upload', 'COM_JMAP_IMPORT_META', false);
		}
		
		if ($this->user->authorise('core.delete', 'com_jmap') && $this->user->authorise('core.edit', 'com_jmap')) {
			JToolBarHelper::deleteList(JText::_('COM_JMAP_DELETE_ALL_META_DESC'), 'metainfo.deleteEntity', 'COM_JMAP_DELETE_ALL_META');
		}
		
		if ($this->user->authorise('core.create', 'com_jmap') && $this->user->authorise('core.create', 'com_jmap')) {
			JToolBarHelper::custom('metainfo.saveAll', 'save', 'save', 'COM_JMAP_SAVEALL_META', false);
			JToolBarHelper::custom('metainfo.autoPopulate', 'database', 'database', 'COM_JMAP_AUTOPOPULATE_META', false);
			if($this->needhttpsmigration) {
				JToolBarHelper::custom('metainfo.httpsMigrate', 'refresh', 'refresh', 'COM_JMAP_MIGRATE_HTTPS_META', false);
			}
		}

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
		$limits[] = JHtml::_('select.option', '2000', '2000');
		$limits[] = JHtml::_('select.option', '5000', '5000');
		$limits[] = JHtml::_('select.option', '10000', '10000');
		$limits[] = JHtml::_('select.option', '20000', '20000');
		$limits[] = JHtml::_('select.option', '30000', '30000');
		$limits[] = JHtml::_('select.option', '50000', '50000');
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
	public function display($tpl = 'list') {
		// Get main records
		$rows = $this->get ( 'Data' );
		$lists = $this->get ( 'Filters' );
		$total = $this->get ( 'Total' );
		
		$doc = JFactory::getDocument();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		$doc->addScript ( JUri::root ( true ) . '/administrator/components/com_jmap/js/metainfo.js' );
		$doc->addScript ( JUri::root ( true ) . '/administrator/components/com_jmap/js/filesources.js' );
		$doc->addStylesheet ( JUri::root ( true ) . '/administrator/components/com_jmap/css/metainfo.css' );
		$doc->addScriptDeclaration("var jmap_baseURI='" . JUri::root() . "';");
		$doc->addScriptDeclaration("var jmap_crawlerDelay=" . $this->getModel()->getComponentParams()->get('seospider_crawler_delay', 0) . ";");
		
		$globalJConfig = JFactory::getConfig();
		$safeJsSitename = str_ireplace(PHP_EOL, '', addcslashes($globalJConfig->get('sitename'), "'"));
		$safeJsSitename = trim(preg_replace("/([\r\n]+)/", '', $safeJsSitename));
		$doc->addScriptDeclaration("var jmap_siteName='" . $safeJsSitename . "';");
		$doc->addScriptDeclaration("var jmap_siteNamePageTitles=" . $globalJConfig->get('sitename_pagetitles', 0) . ";");
		
		// Inject js translations
		$translations = array (
				'COM_JMAP_METAINFO_TITLE',
				'COM_JMAP_METAINFO_PROCESS_RUNNING',
				'COM_JMAP_METAINFO_STARTED_SITEMAP_GENERATION',
				'COM_JMAP_METAINFO_ERROR_STORING_FILE',
				'COM_JMAP_METAINFO_GENERATION_COMPLETE',
				'COM_JMAP_METAINFO_ANALYZING_LINKS',
				'COM_JMAP_METAINFO_ERROR_STORING_DATA',
				'COM_JMAP_METAINFO_SET_ATLEAST_ONE',
				'COM_JMAP_METAINFO_SAVED',
				'COM_JMAP_ALL_METAINFO_SAVED',
				'COM_JMAP_CHARACTERS',
				'COM_JMAP_REQUIRED',
				'COM_JMAP_PICKFILE',
				'COM_JMAP_STARTIMPORT',
				'COM_JMAP_CANCELIMPORT'
		);
		$this->injectJsTranslations($translations, $doc);
		$doc->addScriptDeclaration("
						Joomla.submitbutton = function(pressbutton) {
							Joomla.submitform( pressbutton );
							if (pressbutton == 'metainfo.exportEntities') {
								jQuery('#adminForm input[name=task]').val('metainfo.display');
							}
							return true;
						};
					");
						
		$orders = array ();
		$orders ['order'] = $this->getModel ()->getState ( 'order' );
		$orders ['order_Dir'] = $this->getModel ()->getState ( 'order_dir' );
		// Pagination view object model state populated
		$pagination = new JPagination ( $total, $this->getModel ()->getState ( 'limitstart' ), $this->getModel ()->getState ( 'limit' ) );
		
		$this->user = JFactory::getUser ();
		$this->pagination = $pagination;
		$this->searchpageword = $this->getModel ()->getState ( 'searchpageword', null );
		$this->exactsearchpage = $this->getModel ()->getState ( 'exactsearchpage', null ) ? 'checked' : '';
		$this->needhttpsmigration = $this->getModel ()->getState ( 'needhttpsmigration', null );
		$this->lists = $lists;
		$this->orders = $orders;
		$this->items = $rows;
		
		// Manage different metainfo media buttons for Joomla 3.5 -/+
		if (version_compare ( JVERSION, '3.5', '<' )) {
			$this->mediaField = new JMapHtmlMetaimage();
		} else {
			$jForm = new JForm('jmap_metainfo');
			$jForm->setValue('asset_id', null, 'com_jmap');
			$jForm->setValue('authorId', 'jmap');
			$this->mediaField = new JFormFieldMedia();
			$this->mediaField->setForm($jForm);
		}
		$element = new SimpleXMLElement('<field/>');
		$element->addAttribute('class', 'mediaimagefield');
		$element->addAttribute('default', null);
		$this->mediaField->setup($element, null);
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		parent::display ( $tpl );
	}
}