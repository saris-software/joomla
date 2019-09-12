<?php
// namespace administrator\components\com_jmap\views\overview;
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
 * @since 3.1
 */
class JMapViewGoogle extends JMapView {
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument() {
		$app = $this->app;
		$document = JFactory::getDocument();
		$menus = $app->getMenu();
		$title = null;
	
		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if(is_null($menu)) {
			return;
		}
	
		$this->params = new JRegistry;
		$this->params->loadString($menu->params);
	
		$title = $this->params->get('page_title', JText::_('COM_JMAP_GLOBAL_STATS_REPORT'));
		
		// Joomla 3.2+ support
		if(method_exists($app, 'get')) {
			if ($app->get('sitename_pagetitles', 0) == 1) {
				$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
			}
			elseif ($app->get('sitename_pagetitles', 0) == 2) {
				$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
			}
		}
		
		$document->setTitle($title);
	
		if ($this->params->get('menu-meta_description')) {
			$document->setDescription($this->params->get('menu-meta_description'));
		}
	
		if ($this->params->get('menu-meta_keywords')) {
			$document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
	
		if ($this->params->get('robots')) {
			$document->setMetadata('robots', $this->params->get('robots'));
		}
	}
	
	/**
	 * Default display listEntities
	 *        	
	 * @access public
	 * @param string $tpl
	 * @return void
	 */
	public function display($tpl = null) {
		$this->menuTitle = null;
		$menu = $this->app->getMenu ();
		$activeMenu = $menu->getActive ();
		if (isset ( $activeMenu )) {
			$this->menuTitle = $activeMenu->title;
		}
		
		// Minimal script inclusion
		$this->document->addScriptDeclaration("
			JMapSubmitform = function(task) {
				form = document.getElementById('adminForm');
				form.task.value = task;
				if (typeof form.onsubmit == 'function') {
					form.onsubmit();
				}
				if (typeof form.fireEvent == 'function') {
					form.fireEvent('submit');
				}
				form.submit();
			};");
		
		// Minimal styles inclusion
		$this->document->addStyleDeclaration("
			*.jes #ga-dash div.btn-wrapper {display: inline-block;border: 1px solid #bbb}
			*.jes #ga-dash div.btn-wrapper button {margin: 0;border-radius: 0}
			*.jes #ga-dash div.accordion-body.accordion-inner.collapse {min-height: 0}
			*.jes #ga-dash div.panel-heading{display:none}*.jes #ga-dash div.panel-body{height:auto!important}
			*.jes span.google.label.pull-right{display:none}
			*.jes *.well{padding:19px;background-color: #f5f5f5;}
			*.jes #toolbar-download{float:none}
			*.jes span.label-primary{background-color: #999;padding:2px 4px;color:#FFF;border-radius:3px}
			*.jes button.btn.active,*.jes button.btn-default:hover{background: #FFF}
			*.jes a.btn.btn-primary.google{border:1px solid #bbb;padding:4px 12px;border-radius:4px}
			*.jes input[type=submit],*.jes button.btn{cursor:pointer;padding:3px}
			*.jes #ga-dash{margin-top:10px}
			*.jes #ga-dash>div.btn-toolbar{margin-bottom:10px}");
		
		// Get main records
		$lists = $this->get ( 'Lists' );

		$googleStatsState = $this->getModel()->getState('googlestats', 'analytics');
		if($googleStatsState == 'alexafetch' || 
		   $googleStatsState == 'hypestatfetch'	|| 
		   $googleStatsState == 'searchmetricsfetch' ) {
			// Load resources, iframe script used for frontend module and template styling for the iframed contents
			$this->document->addScript ( JUri::root ( true ) . '/modules/mod_jmap/tmpl/iframe.js' );
			$this->document->addStyleDeclaration('#jmap-analytics-frame{width:100%;border:none}');
			
			// Setup the iframe container
			$onLoadIFrame = "jmapIFrameAutoHeight('jmap-analytics-frame')";
			
			// Initialize the multilanguage status
			$languageQueryStringParam = null;
			if($this->app->getLanguageFilter()) {
				$knownLangs = JLanguageHelper::getLanguages();
				$defaultLanguageCode = JFactory::getLanguage()->getTag();
				foreach ($knownLangs as $knownLang) {
					if($knownLang->lang_code == $defaultLanguageCode) {
						$languageQueryStringParam = '&lang=' . $knownLang->sef;
						break;
					}
				}
			}
			
			$renderGoogleStatsState = str_ireplace('fetch', 'render', $googleStatsState);
			$googleData = '<iframe id="jmap-analytics-frame" src="' . JUri::root (false) . 'index.php?option=com_jmap&task=google.display&googlestats=' . $renderGoogleStatsState . '&format=raw' . $languageQueryStringParam . '" onload="' . $onLoadIFrame . '"></iframe>';
			$tpl = 'framed';
		} else {
			$googleData = $this->get ( 'Data' );
		}
		
		$this->loadJQuery($this->document);
		
		$this->lists = $lists;
		$this->googleData = $googleData;
		$this->isLoggedIn = $this->getModel()->getToken();
		$this->option = $this->getModel ()->getState ( 'option' );
		$this->cparams = $this->getModel ()->getComponentParams ();
		
		// Aggiunta toolbar
		$this->_prepareDocument();
		
		parent::display ($tpl);
	}
}