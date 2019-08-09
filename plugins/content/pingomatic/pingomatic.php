<?php
/**
 * @author Joomla! Extensions Store
 * @package JMAP::plugins::content
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.plugin.plugin' );

/**
 * Observer class notified on events <<testable_behavior>>
 *
 * @author Joomla! Extensions Store
 * @package JMAP::plugins::content
 * @since 3.0
 */
class plgContentPingomatic extends JPlugin {
	/**
	 * Application reference
	 *
	 * @access private
	 * @var Object
	 */
	private $app;
	
	/**
	 * Plugin execution context
	 *
	 * @access private
	 * @var array
	 */
	private $context;
	
	/**
	 * Plugin Joomla execution context
	 *
	 * @access private
	 * @var string
	 */
	private $jcontext;
	
	/**
	 * Curl adapter reference
	 *
	 * @access private
	 * @var Object
	 */
	private $curlAdapter;
	
	/**
	 * Pinger class for webblog services such as Pingomatic
	 *
	 * @access private
	 * @var Object
	 */
	private $pingerInstance;
	
	/**
	 * Database connector
	 *
	 * @access private
	 * @var Object
	 */
	private $db;
	
	/**
	 * Component config params
	 *
	 * @access private
	 * @var Object
	 */
	private $cParams;
	
	/**
	 * Adapters mapping based on context and route helper
	 *
	 * @access private
	 * @var array
	 */
	private $adaptersMapping;
	
	/**
	 * Single article routed link
	 *
	 * @access private
	 * @var string
	 */
	private $singleArticleRouted;
	
	/**
	 * Load the CURL library needed from JMap Framework
	 *
	 * @access private
	 * @return boolean
	 */
	private function loadCurlLib() {
		// Check lib availability and load it
		if (file_exists ( JPATH_ROOT . '/administrator/components/com_jmap/framework/http/http.php' )) {
			include_once (JPATH_ROOT . '/administrator/components/com_jmap/framework/http/http.php');
			include_once (JPATH_ROOT . '/administrator/components/com_jmap/framework/http/response.php');
			include_once (JPATH_ROOT . '/administrator/components/com_jmap/framework/http/transport.php');
			include_once (JPATH_ROOT . '/administrator/components/com_jmap/framework/http/transport/curl.php');
			
			// Instantiate dependency
			$this->curlAdapter = new JMapHttp ( new JMapHttpTransportCurl (), $this->cParams );
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Load the Pinger lib to ping weblog services
	 *
	 * @access private
	 * @return boolean
	 */
	private function loadPingerLib() {
		// Check lib availability and load it
		if (file_exists ( JPATH_ROOT . '/administrator/components/com_jmap/framework/pinger/weblog.php' )) {
			include_once (JPATH_ROOT . '/administrator/components/com_jmap/framework/pinger/weblog.php');

			// Instantiate dependency
			$this->pingerInstance = new JMapPingerWeblog();

			return true;
		}
	
		return false;
	}
	
	/**
	 * Send auto ping for this article URL available in the ping table using the curl adapter
	 *
	 * @access private
	 * @return boolean
	 */
	private function autoSendPing($title, $url, $rssurl, $services) {
		// Load safely the CURL JMap lib without autoloader
		if ($this->loadCurlLib ()) {
			// Array of POST vars
			$post = array ();
			$post ['title'] = $title;
			$post ['blogurl'] = $url;
			$post ['rssurl'] = $rssurl;
			$post = array_merge ( $post, ( array ) $services );
			
			// Post HTTP request to Pingomatic
			$httpResponse = $this->curlAdapter->post ( 'http://pingomatic.com/ping/', $post, array (), 5, 'JSitemap Professional Pinger' );
			
			// Check if HTTP status code is OK
			if ($httpResponse->code != 200) {
				throw new RuntimeException ( JText::_ ( 'COM_JMAP_AUTOPING_ERROR_HTTP_STATUSCODE' ) );
			}
		}
		
		return true;
	}
	
	/**
	 * New router Joomla 3.7 management
	 */
	private function findItemidNewRouter($link, $siteRouter) {
		// 1° STEP: build the route using the new router
		$articleMenuRoutedUriObject = $siteRouter->build ( $link );
	
		// Add compatibility support for J3.8 new router between sites management
		if(version_compare(JVERSION, '3.8', '>=') && version_compare(JVERSION, '3.9', '<')) {
			$path = $articleMenuRoutedUriObject->getPath();
			$path = str_replace('/index.php/', '/', $path);
			$articleMenuRoutedUriObject->setPath($path);
		}
		
		// Add compatibility support for J3.9 new router between sites management
		if(version_compare(JVERSION, '3.9', '>=')) {
			$path = $articleMenuRoutedUriObject->getPath();
			$path = '/administrator' . $path;
			$path = str_replace('/index.php/', '/', $path);
			$articleMenuRoutedUriObject->setPath($path);
			$originalBackendLanguageTag = $this->app->getLanguage()->getTag();
		}
		
		// 2° STEP: parse back the URL now finally including the routed Itemid
		JApplicationCms::getInstance('site')->loadLanguage();
		// Avoid parse uri redirects when saving an article
		if ($this->app->get('force_ssl') >= 1) {
			$articleMenuRoutedUriObject->setScheme('https');
		}
		$articleMenuParsedUriArray = $siteRouter->parse ($articleMenuRoutedUriObject);
		
		// Override always the $siteRouter->parse language from the frontend side link applying the original backend language
		if(version_compare(JVERSION, '3.9', '>=')) {
			$jLang = JFactory::getLanguage();
			$jLang->setLanguage($originalBackendLanguageTag);
			$jLang->load('com_jmap', JPATH_ADMINISTRATOR . '/components/com_jmap', 'en-GB', true, true);
			if($originalBackendLanguageTag != 'en-GB') {
				$jLang->load('com_jmap', JPATH_ADMINISTRATOR, $originalBackendLanguageTag, true, false);
				$jLang->load('com_jmap', JPATH_ADMINISTRATOR . '/components/com_jmap', $originalBackendLanguageTag, true, false);
			}
		}
		
		if(isset($articleMenuParsedUriArray['Itemid'])) {
			$link .= '&Itemid=' . $articleMenuParsedUriArray['Itemid'];
		}
	
		return $link;
	}
	
	/**
	 * Route save single article to the corresponding SEF link
	 *
	 * @access private
	 * @return string
	 */
	private function routeArticleToSefMenu($articleID, $catID, $language, $article) {
		// Try to route the article to a single article menu item view
		$helperRouteClass = $this->context ['class'];
		$classMethod = $this->context ['method'];
		$siteRouter = JRouterSite::getInstance ( 'site', array (
				'mode' => JROUTER_MODE_SEF
		) );
		
		// Patch for K2, ensure to always evaluate the article language, override the Factory language instance
		if($this->jcontext == 'com_k2.item' && $language != '*' && JMapLanguageMultilang::isEnabled ()) {
			$originalLanguage = JFactory::getLanguage();
			$lang = JLanguage::getInstance($language, $language);
			JFactory::$language = $lang;
		}
		
		// Route helper native by component, com_content, com_k2
		if (! isset ( $this->context ['routing'] )) {
			$articleHelperRoute = $helperRouteClass::$classMethod ( $articleID, $catID, $language );
		} else {
			// Route helper universal JSitemap, com_zoo
			$articleHelperRoute = $helperRouteClass::$classMethod ( $article->option, $article->view, $article->id, $article->catid, null );
			if ($articleHelperRoute) {
				$articleHelperRoute = '?Itemid=' . $articleHelperRoute;
			}
		}
		
		// Extract Itemid from the helper routed URL
		$extractedItemid = preg_match ( '/Itemid=\d+/i', $articleHelperRoute, $result );
		
		// Joomla 3.7 new router
		if(version_compare ( JVERSION, '3.7', '>=' ) && stripos($articleHelperRoute, 'com_content') && !$extractedItemid) {
			$articleRouteWithItemid = $this->findItemidNewRouter ($articleHelperRoute, $siteRouter);
			$extractedItemid = preg_match ( '/Itemid=\d+/i', $articleRouteWithItemid, $result );
		}
		
		if (isset ( $result [0] )) {
			// Patch for K2, ensure to always evaluate the article language, override the Factory language instance
			if($this->jcontext == 'com_k2.item' && $language != '*' && JMapLanguageMultilang::isEnabled ()) {
				$result [0] .= '&lang='. $language;
				$articleHelperRoute .= '&lang='. $language;
			}
			
			// Get uri instance avoidng subdomains already included in the routing chunks
			$uriInstance = JUri::getInstance();
			$resourceLiveSite = rtrim($uriInstance->getScheme() . '://' . $uriInstance->getHost(), '/');

			$extractedItemid = $result [0];
			$articleMenuRouted = $siteRouter->build ( '?' . $extractedItemid )->toString ();
			
			// Store a single article routed URL so that i can be used at a later stage for the autoping feature only
			if($this->cParams->get('default_autoping_single_article', 1)) {
				if(isset($this->context['routing']) && $this->context['routing'] == 'jmap') {
					$this->singleArticleRouted = $siteRouter->build ( sprintf($this->context ['rawlink'], $articleID, $extractedItemid ) )->toString ();
				} else {
					$this->singleArticleRouted = $siteRouter->build ( $articleHelperRoute )->toString ();
				}
			}
			
			// Patch for K2, restore the original Factory language instance
			if($this->jcontext == 'com_k2.item' && $language != '*' && JMapLanguageMultilang::isEnabled ()) {
				JFactory::$language = $originalLanguage;
			}
			
			// Check if multilanguage is enabled
			if (JMapLanguageMultilang::isEnabled ()) {
				$defaultLanguage = JComponentHelper::getParams('com_languages')->get('site');
				if ($language != '*') {
					// New language manager instance
					$languageManager = JMapLanguageMultilang::getInstance ( $language );
				} else {
					// Get the default language tag
					// New language manager instance
					$languageManager = JMapLanguageMultilang::getInstance ( $defaultLanguage );
				}
				
				// Extract the language tag
				$selectedLanguage = $languageManager->getTag();
				$languageFilterPlugin = JPluginHelper::getPlugin('system', 'languagefilter');
				$languageFilterPluginParams = new JRegistry($languageFilterPlugin->params);
				if($defaultLanguage == $selectedLanguage && $languageFilterPluginParams->get('remove_default_prefix', 0)) {
					$articleMenuRouted = str_replace ( '/administrator', '', $articleMenuRouted );
					if($this->singleArticleRouted) {
						$this->singleArticleRouted = str_replace ( '/administrator', '', $this->singleArticleRouted );
					}
				} else {
					$localeTag = $languageManager->getLocale ();
					$sefTag = $localeTag [4];
					$articleMenuRouted = str_replace ( '/administrator', '/' . $sefTag, $articleMenuRouted );
					if($this->singleArticleRouted) {
						$this->singleArticleRouted = str_replace ( '/administrator', '/' . $sefTag, $this->singleArticleRouted );
					}
				}
			} else {
				$articleMenuRouted = str_replace ( '/administrator', '', $articleMenuRouted );
			}
			$articleMenuRouted = preg_match('/http/i', $articleMenuRouted) ? $articleMenuRouted : $resourceLiveSite . '/' . ltrim($articleMenuRouted, '/');
			if($this->singleArticleRouted) {
				$this->singleArticleRouted = preg_match('/http/i', $this->singleArticleRouted) ? $this->singleArticleRouted : $resourceLiveSite . '/' . ltrim($this->singleArticleRouted, '/');
			}
			return $articleMenuRouted;
		} else {
			// Patch for K2, restore the original Factory language instance
			if($this->jcontext == 'com_k2.item' && $language != '*' && JMapLanguageMultilang::isEnabled ()) {
				JFactory::$language = $originalLanguage;
			}
			
			// Check if routing is valid otherwise throw exception
			throw new RuntimeException ( JText::_ ( 'COM_JMAP_AUTOPING_ERROR_NOSEFROUTE_FOUND' ) );
		}
	}
	
	/**
	 * Method to be called everytime an article in backend is saved,
	 * it's responsible to check and find if the SEF link of the article has been
	 * added to the Pingomatic table, and if found submit the ping form through CURL http adapter
	 *
	 * @param string $context
	 *        	The context of the content passed to the plugin (added in 1.6)
	 * @param object $article
	 *        	A JTableContent object
	 * @param boolean $isNew
	 *        	If the content is just about to be created
	 *        	
	 * @return boolean true if function not enabled, is in front-end or is new. Else true or
	 *         false depending on success of save function.
	 */
	public function onContentAfterSave($context, $article, $isNew) {
		// Avoid operations if plugin is executed in frontend
		if (! $this->cParams->get ( 'default_autoping', 0 ) && ! $this->cParams->get ( 'autoping', 0 )) {
			return;
		}
		
		// Ensure to process only native Joomla articles
		if (array_key_exists ( $context, $this->adaptersMapping )) {
			// Store the Joomla context
			$this->jcontext = $context;
			
			// Extract the correct route helper
			$routeHelper = $this->adaptersMapping [$context] ['file'];
			// Include needed files for the correct multilanguage routing from backend to frontend of the save articles
			if (file_exists ( $routeHelper )) {
				include_once ($routeHelper);
				
				// Store the context for static class method call
				$this->context = $this->adaptersMapping [$context];
			}
			
			// Start HTTP submission process, manage users exceptions if debug is enabled
			try {
				// Try attempt to resolve the article to a single menu or container category SEF link
				$hasArticleMenuRoute = $this->routeArticleToSefMenu ( $article->id, $article->catid, $article->language, $article );
				
				// If article has been resolved, fetch pings URLs from jmap_pingomatic table and do lookup
				if ($hasArticleMenuRoute) {
					// Check if the auto Pingomatic ping based on records is enabled
					if($this->cParams->get ( 'autoping', 0 )) {
						$query = $this->db->getQuery ( true );
						$query->select ( '*' );
						$query->from ( $this->db->quoteName ( '#__jmap_pingomatic' ) );
						$query->where ( $this->db->quoteName ( 'blogurl' ) . '=' . $this->db->quote ( $hasArticleMenuRoute ) );
						
						// Is there a found pinged link for this article scope?
						$foundPingUrl = $this->db->setQuery ( $query )->loadObject ();
						if ($foundPingUrl) {
							// Retrieve ping record info and submit form using CURL adapter, else do nothing
							$titleToPing = $foundPingUrl->title;
							$urlToPing = $foundPingUrl->blogurl;
							$rssUrlToPing = $foundPingUrl->rssurl;
							$servicesToPing = json_decode ( $foundPingUrl->services );
							
							// If ping is OK update the pinging status and datetime in the Pingomatic table
							if ($this->autoSendPing ( $titleToPing, $urlToPing, $rssUrlToPing, $servicesToPing )) {
								$query = $this->db->getQuery ( true );
								$query->update ( $this->db->quoteName ( '#__jmap_pingomatic' ) );
								$query->set ( $this->db->quoteName ( 'lastping' ) . ' = ' . $this->db->quote ( date ( 'Y-m-d H:i:s' ) ) );
								$query->where ( $this->db->quoteName ( 'id' ) . '=' . ( int ) $foundPingUrl->id );
								$this->db->setQuery ( $query )->execute ();
								
								// Everything complete fine, ping sent and updated!
								if ($this->cParams->get ( 'enable_debug', 0 )) {
									$this->app->enqueueMessage ( JText::_ ( 'COM_JMAP_AUTOPING_COMPLETED_SUCCESFULLY' ), 'notice' );
								}
							}
						} else {
							// Display post message after save only if debug is enabled
							if ($this->cParams->get ( 'enable_debug', 0 )) {
								$this->app->enqueueMessage ( JText::_ ( 'COM_JMAP_AUTOPING_ERROR_NOPING_CONTENT_FOUND' ), 'notice' );
							}
						}
					}
					
					// Check if the default Pingomatic/Weblogs ping is enabled
					if($this->cParams->get ( 'default_autoping', 0 )) {
						// Always submit autoping using XMLRPC web services
						if($this->loadPingerLib()) {
							// Get a single article routed URL override
							if($this->cParams->get('default_autoping_single_article', 1)) {
								$hasArticleMenuRoute = $this->singleArticleRouted;
							}
							
							// Normalize language URL if needed, remove untraslated query string
							$hasArticleMenuRoute = preg_replace('/\?(.)*$/i', '', $hasArticleMenuRoute);
							
							// K2 management
							if($this->cParams->get('default_autoping_single_article', 1)) {
								if($context == 'com_k2.item') {
									$hasArticleMenuRoute .= '-' . $article->alias;
									// Check if the SEF suffix is enabled and correct the URL
									if($this->app->get ( 'sef_suffix', 1 )) {
										$hasArticleMenuRoute = str_replace('.html', '', $hasArticleMenuRoute) . '.html';
									}
								}
							}

							// Get debug state
							$debugEnabled = $this->cParams->get ( 'enable_debug', 0 );
							$pingomaticPinged = $this->pingerInstance->ping_ping_o_matic($article->title, $hasArticleMenuRoute);
							if($debugEnabled && $pingomaticPinged) {
								$this->app->enqueueMessage ( JText::_( 'COM_JMAP_AUTOPING_DEFAULT_AUTOPING_SENT_PINGOMATIC' ), 'notice' );
							}

							$googlePinged = $this->pingerInstance->ping_google($article->title, $hasArticleMenuRoute);
							if($debugEnabled && $googlePinged) {
								$this->app->enqueueMessage ( JText::_( 'COM_JMAP_AUTOPING_DEFAULT_AUTOPING_SENT_GOOGLE' ), 'notice' );
							}

							$weblogsPinged = $this->pingerInstance->ping_weblogs_com($article->title, $hasArticleMenuRoute);
							if($debugEnabled && $weblogsPinged) {
								$this->app->enqueueMessage ( JText::_( 'COM_JMAP_AUTOPING_DEFAULT_AUTOPING_SENT_WEBLOGS' ), 'notice' );
							}
							
							$blogsPinged = $this->pingerInstance->ping_blo_gs($article->title, $hasArticleMenuRoute);
							if($debugEnabled && $blogsPinged) {
								$this->app->enqueueMessage ( JText::_( 'COM_JMAP_AUTOPING_DEFAULT_AUTOPING_SENT_BLOGS' ), 'notice' );
							}
							
							$baiduPinged = $this->pingerInstance->ping_baidu($article->title, $hasArticleMenuRoute);
							if($debugEnabled && $baiduPinged) {
								$this->app->enqueueMessage ( JText::_( 'COM_JMAP_AUTOPING_DEFAULT_AUTOPING_SENT_BAIDU' ), 'notice' );
							}
							
							if($debugEnabled) {
								$this->app->enqueueMessage ( $hasArticleMenuRoute, 'notice' );
							}
						}
					}
				}
			} catch ( Exception $e ) {
				// Display post message after save only if debug is enabled
				if ($this->cParams->get ( 'enable_debug', 0 )) {
					$this->app->enqueueMessage ( $e->getMessage (), 'notice' );
				}
			}
		}
	}
	
	/**
	 * Class constructor, manage params from component
	 *
	 * @access private
	 * @return boolean
	 */
	public function __construct(&$subject) {
		// Load component config
		$this->cParams = JComponentHelper::getParams ( 'com_jmap' );
		
		// Framework object dependencies
		$this->app = JFactory::getApplication ();
		$this->db = JFactory::getDbo ();
		
		// Avoid operations if plugin is executed in frontend
		if (! $this->app->getClientId ()) {
			return;
		}
		
		// Avoid operation if not supported extension is detected
		if(!in_array($this->app->input->get('option'), array('com_content', 'com_k2', 'com_zoo'))) {
			return;
		}
		
		parent::__construct ( $subject );
		
		if (file_exists ( JPATH_ROOT . '/administrator/components/com_jmap/framework/language/multilang.php' )) {
			include_once (JPATH_ROOT . '/administrator/components/com_jmap/framework/language/multilang.php');
		}
		
		$this->adaptersMapping = array (
				'com_content.article' => array (
						'file' => JPATH_ROOT . '/components/com_content/helpers/route.php',
						'class' => 'ContentHelperRoute',
						'method' => 'getArticleRoute' 
				),
				'com_k2.item' => array (
						'file' => JPATH_ROOT . '/components/com_k2/helpers/route.php',
						'class' => 'K2HelperRoute',
						'method' => 'getItemRoute' 
				),
				'com_zoo.item' => array (
						'routing' => 'jmap',
						'rawlink' => 'index.php?option=com_zoo&view=item&task=item&item_id=%s&%s',
						'file' => JPATH_ROOT . '/administrator/components/com_jmap/framework/route/helper.php',
						'class' => 'JMapRouteHelper',
						'method' => 'getItemRoute' 
				) 
		);
		
		// Manage partial language translations
		$jLang = JFactory::getLanguage ();
		$jLang->load ( 'com_jmap', JPATH_ROOT . '/administrator/components/com_jmap', 'en-GB', true, true );
		if ($jLang->getTag () != 'en-GB') {
			$jLang->load ( 'com_jmap', JPATH_SITE, null, true, false );
			$jLang->load ( 'com_jmap', JPATH_SITE . '/administrator/components/com_jmap', null, true, false );
		}
	}
}