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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

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
	 * Display the XML sitemap
	 * @access public
	 * @return void
	 */
	function display($tpl = null) {
		$document = $this->document;
		$document->setMimeEncoding('application/xml');
		
		// Call by cache handler get no params, so recover from model state
		if(!$tpl) {
			$tpl = $this->getModel ()->getState ( 'documentformat' );
		}
				   
		$this->data = $this->get ( 'SitemapData' );
		$this->cparams = $this->getModel ()->getState ( 'cparams' );
		// Transport wrapper
		$this->HTTPClient = new JMapHttp(null, $this->cparams);
		$this->application = JFactory::getApplication();
		$this->xslt = $this->getModel()->getState('xslt');
		
		// Set regex for the images crawler
		$this->mainImagesRegex = $this->cparams->get('regex_images_crawler', 'advanced') == 'standard' ?
										  '/(<img)([^>])*(src=["\']([^"\']+)["\'])([^>])*/i' : '/(<img)([^>])*(src=["\']?([^"\']+\.(jpg|jpeg|gif|png|svg|webp))["\']?)([^>])*/i';
		
		// Custom tags and attributes
		$this->validCustomImagesProcessor = false;
		$customImages = $this->cparams->get ( 'custom_images_processor', 0 );
		$explodedTags = null;
		$customImagesTags = trim($this->cparams->get ( 'custom_images_processor_tags', '' ));
		$explodedAttributes = null;
		$customImagesAttributes = trim($this->cparams->get ( 'custom_images_processor_attributes', '' ));
		if($customImages) {
			if($customImagesTags) {
				$explodedTags = explode(',', $customImagesTags);
				if(!empty($explodedTags)) {
					foreach ($explodedTags as &$customImageTag) {
						$customImageTag = strtolower(trim($customImageTag));
						$customImageTag = preg_replace("/[^a-z0-9-]/i", '', $customImageTag);
					}
				}
			}
			if($customImagesAttributes) {
				$explodedAttributes = explode(',', $customImagesAttributes);
				if(!empty($explodedAttributes)) {
					foreach ($explodedAttributes as &$customImageAttribute) {
						$customImageAttribute = strtolower(trim($customImageAttribute));
						$customImageAttribute = preg_replace("/[^a-z0-9-]/i", '', $customImageAttribute);
					}
				}
			}
		}
		$this->validCustomImagesProcessor = $customImages && is_array($explodedTags) && is_array($explodedAttributes);
		if($this->validCustomImagesProcessor) {
			$this->validExplodedTags = $explodedTags;
			$this->validExplodedAttributes = $explodedAttributes;
		}
		
		$uriInstance = JUri::getInstance();
		$customHttpPort = trim($this->cparams->get('custom_http_port', ''));
		$getPort = $customHttpPort ? ':' . $customHttpPort : '';
		
		$customDomain = trim($this->cparams->get('custom_sitemap_domain', ''));
		$getDomain = $customDomain ? rtrim($customDomain, '/') : rtrim($uriInstance->getScheme() . '://' . $uriInstance->getHost(), '/');

		if($this->cparams->get('append_livesite', true)) {
			$this->liveSite = rtrim($getDomain . $getPort, '/');
		} else {
			$this->liveSite = null;
		}
		
		// Initialize output links buffer with exclusion for links
		$this->outputtedLinksBuffer = $this->getModel()->getExcludedLinks($this->liveSite);
		
		// Crawler live site management
		if($this->cparams->get('sh404sef_multilanguage', 0) && JMapLanguageMultilang::isEnabled()) {
			$lang = '/' . $this->app->input->get('lang');
			// Check if sh404sef insert language code param is off, otherwise the result would be doubled language chunk in liveSiteCrawler
			$sh404SefParams = JComponentHelper::getParams('com_sh404sef');
			if($sh404SefParams->get('shInsertLanguageCode', 0) || !$sh404SefParams->get('Enabled', 1)) {
				$lang = null;
			}
			$this->liveSiteCrawler = rtrim($getDomain . $getPort . $lang, '/');
		} else {
			$this->liveSiteCrawler = rtrim($getDomain . $getPort, '/');
		}
		
		// Check if the live site crawler must be forced to the non https domain
		if($this->cparams->get('force_crawler_http', 0)) {
			$this->liveSiteCrawler = str_replace('https://', 'http://', $this->liveSiteCrawler);
		}
		
		$this->setLayout('default');
		parent::display ($tpl);
	}
}