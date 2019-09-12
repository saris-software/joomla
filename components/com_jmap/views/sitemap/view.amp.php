<?php
// namespace components\com_jmap\views\sitemap;
/**
 * @package JMAP::SITEMAP::components::com_jmap
 * @subpackage views
 * @subpackage sitemap
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Main view class
 *
 * @package JMAP::SITEMAP::components::com_jmap
 * @subpackage views
 * @subpackage sitemap
 * @since 1.0
 */
class JMapViewSitemap extends JMapView {
	/**
	 * Display the XML AMP sitemap
	 * @access public
	 * @return void
	 */
	function display($tpl = null) {
		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/xml');
		$this->application = JFactory::getApplication();
		
		// Generate AMP links only when SEF is turned on
		if(!$this->application->get ( 'sef', 1 )) {
			return;
		}
		
		// Call by cache handler get no params, so recover from model state
		if(!$tpl) {
			$tpl = $this->getModel ()->getState ( 'documentformat' );
		}

		$this->data = $this->get('SitemapData');
		$this->cparams = $this->getModel()->getState('cparams');
		$this->sefSuffixEnabled = $this->cparams->get('amp_sef_suffix_enabled', 0) ? true : $this->application->get ( 'sef_suffix', 0 );
		$this->ampSuffix = $this->cparams->get('amp_suffix', 'amp');
		$this->xslt = $this->getModel()->getState('xslt');
		
		$uriInstance = JUri::getInstance();
		if($this->cparams->get('append_livesite', true)) {
			$customHttpPort = trim($this->cparams->get('custom_http_port', ''));
			$getPort = $customHttpPort ? ':' . $customHttpPort : '';
			
			$customDomain = trim($this->cparams->get('custom_sitemap_domain', ''));
			$getDomain = $customDomain ? rtrim($customDomain, '/') : rtrim($uriInstance->getScheme() . '://' . $uriInstance->getHost(), '/');

			$this->liveSite = rtrim($getDomain . $getPort, '/');
		} else {
			$this->liveSite = null;
		}
		
		// Initialize output links buffer with exclusion for links
		$this->outputtedLinksBuffer = $this->getModel()->getExcludedLinks($this->liveSite);
		
		$this->setLayout('default');
		parent::display($tpl);
	}
}