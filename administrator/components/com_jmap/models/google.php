<?php
// namespace administrator\components\com_jmap\models;
/**
 *
 * @package JMAP::GOOGLE::administrator::components::com_jmap
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Google model responsibilities for access Google Analytics and Webmasters Tools API
 *
 * @package JMAP::SOURCES::administrator::components::com_jmap
 * @subpackage models
 * @since 3.1
 */
interface IJMapModelGoogle {
	/**
	 * Submit a sitemap link using the GWT API
	 *
	 * @access public
	 * @param string $sitemapUri
	 * @return boolean
	 */
	public function submitSitemap($sitemapUri);

	/**
	 * Delete a sitemap link using the GWT API
	 *
	 * @access public
	 * @param string $sitemapUri
	 * @return boolean
	 */
	public function deleteSitemap($sitemapUri);

	/**
	 * Get data method for webmasters tools stats
	 *
	 * @access public
	 * @return mixed Returns a data string if success or boolean if exceptions are trigged
	 */
	public function getDataWebmasters();

	/**
	 * Return the google token
	 *
	 * @access public
	 * @return string
	 */
	public function getToken();
}

/**
 * Sources model concrete implementation <<testable_behavior>>
 *
 * @package JMAP::GOOGLE::administrator::components::com_jmap
 * @subpackage models
 * @since 3.1
 */
class JMapModelGoogle extends JMapModel implements IJMapModelGoogle {
	/**
	 * Google_Client object
	 *
	 * @access private
	 * @var Google_Client
	 */
	private $client;
	
	/**
	 * Current profile found for Google Analytics
	 *
	 * @access private
	 * @var string
	 */
	private $currentProfile;
	
	/**
	 * Track the API connection mode, built in JSitemap Google App or user own
	 *
	 * @access private
	 * @var string
	 */
	private $hasOwnCredentials;
	
	/**
	 * Get the top level host domain for each kind of URL needed to avoid Alexa redirects on CURL exec
	 *
	 * @access private
	 * @param string $url
	 * @return string
	 */
	private function getHost($url) {
		if (strpos ( $url, "http" ) !== false) {
			$httpurl = $url;
		} else {
			$httpurl = "http://" . $url;
		}
		$parse = parse_url ( $httpurl );
		$domain = $parse ['host'];
	
		$parts = explode ( ".", $domain );
		$count = sizeof ( $parts ) - 1;
	
		if ($count > 1) {
			$slicedParts = array_slice( $parts, -2, 1 );
			$slice = ( strlen( reset( $slicedParts ) ) == 2 || in_array(reset( $slicedParts ), array('com', 'org', 'gov', 'net'))) && ( count( $parts ) > 2 ) ? 3 : 2;
			$result = implode( '.', array_slice( $parts, ( 0 - $slice ), $slice ) );
		} else {
			$result = $domain;
		}
		return $result;
	}
	
	/**
	 * Purify and normalize domain protocol
	 *
	 * @access private
	 * @return string
	 */
	private function purifyDomain($domain) {
		if($this->getComponentParams ()->get('ga_domain_match_protocol', 0)) {
			return $domain;
		}
		return str_replace ( array (
				"https://",
				"http://",
				" "
		), "", rtrim ( $domain, "/" ) );
	}
	
	/**
	 * Purify and normalize domain uri for webmasters tools stats
	 *
	 * @access private
	 * @return string
	 */
	private function purifyWebmastersDomain($domain) {
		return str_replace ( array (
				" "
		), "", rtrim ( $domain, "/" ) );
	}
	
	/**
	 * Manage the authentication form and action
	 *
	 * @param Object $params
	 * @access private
	 * @return mixed A string when auth is needed, null if performing an auth
	 */
	private function authentication($params) {
		$this->client = new Google_Client ();
		$this->client->setAccessType ( 'offline' );
		$this->client->setScopes ( array( 'https://www.googleapis.com/auth/analytics.readonly', 'https://www.googleapis.com/auth/webmasters' ));
		$this->client->setApplicationName ( 'JSitemap Professional' );
		$this->client->setRedirectUri ( 'urn:ietf:wg:oauth:2.0:oob' );
	
		$this->hasOwnCredentials = false;
		if ($params->get ( 'ga_api_key' ) and $params->get ( 'ga_client_id' ) and $params->get ( 'ga_client_secret' )) {
			$this->client->setClientId ( $params->get ( 'ga_client_id' ) );
			$this->client->setClientSecret ( $params->get ( 'ga_client_secret' ) );
			$this->client->setDeveloperKey ( $params->get ( 'ga_api_key' ) ); // API key
			$this->hasOwnCredentials = true;
		} else {
			$this->client->setClientId ( '1229958023-v6o02cp8hj71rijdpc110efvsmjd9f9e.apps.googleusercontent.com' );
			$this->client->setClientSecret ( 'mkicQ8LsbYyMes2DwF6DhQ-n' );
			$this->client->setDeveloperKey ( 'AIzaSyBOXBjtrtYPTQmpupLwY5AhKmazQqVQPzw' );
		}
	
		try {
			if ($this->getToken ()) { // extract token from session and configure client
				$token = $this->getToken ();
				$this->client->setAccessToken ( $token );
			}
		} catch ( Exception $e ) {
			$jmapException = new JMapException ( $e->getMessage (), 'error' );
			$this->app->enqueueMessage ( $jmapException->getMessage (), $jmapException->getErrorLevel () );
			return '<a class="btn btn-primary" href="index.php?option=com_jmap&amp;task=google.deleteEntity">' . JText::_ ( 'COM_JMAP_GOOGLE_LOGOUT' ) . '</a>';
		}

		if (! $result = $this->client->getAccessToken ()) { // auth call to google
			$authUrl = $this->client->createAuthUrl ();
			// Trying to authenticate?
			if (!$this->app->input->get('ga_dash_authorize')) {
				$JText = 'JText';
				$htmlSnippet = <<<HTML
					<div class="google_login">
						<p class="well">
							<span class="label label-primary">
								{$JText::_ ( 'COM_JMAP_GOOGLE_STEP1_CODE_DESC' )}
							</span>
	  						<a class="btn btn-primary btn-sm hasPopover google" data-content="{$JText::_ ( 'COM_JMAP_GOOGLE_CODE_INSTUCTIONS' )}" href="$authUrl" target="_blank">
	  							{$JText::_ ( 'COM_JMAP_GOOGLE_CODE' )}
	  						</a>
  						</p>

  						<p class="well">
  							<span class="label label-primary">
  								{$JText::_ ( 'COM_JMAP_GOOGLE_STEP2_ACCESS_CODE_INSERT' )}
  							</span>
  							<input type="text" name="ga_dash_code" value="" size="61">
  						</p>

  						<p class="well">
  							<span class="label label-primary">
  								{$JText::_ ( 'COM_JMAP_GOOGLE_STEP3_AUTHENTICATE' )}
  							</span>
							<input type="submit" class="btn btn-primary btn-sm waiter" name="ga_dash_authorize" value="{$JText::_ ( 'COM_JMAP_GOOGLE_AUTHENTICATE' )}"/>
						</p>
					</div>

HTML;
					return $htmlSnippet;
				} else {
				// Yes! This is an authentication attempt let's try it
				try {
					$this->client->authenticate ( $this->app->input->getString('ga_dash_code'));
  				} catch ( JMapException $e ) {
					$this->app->enqueueMessage ( $e->getMessage (), $e->getErrorLevel () );
					return '<a class="btn btn-primary" href="index.php?option=com_jmap&amp;task=google.display">' . JText::_ ( 'COM_JMAP_GOBACK' ) . '</a>';
				} catch ( Exception $e ) {
					$jmapException = new JMapException ( $e->getMessage (), 'error' );
					$this->app->enqueueMessage ( $jmapException->getMessage (), $jmapException->getErrorLevel () );
					return '<a class="btn btn-primary" href="index.php?option=com_jmap&amp;task=google.display">' . JText::_ ( 'COM_JMAP_GOBACK' ) . '</a>';
				}

  				// Store the Google token in the DB for further login and authentication
				$this->storeToken ( $this->client->getAccessToken () );

				return null;
			}
		}
	}

	/**
	 * Store the Google token
	 *
	 * @access private
	 * @return boolean
	 */
	private function storeToken($token) {
		$clientID = (int)$this->app->getClientId();
		try {
			$query = "INSERT IGNORE INTO #__jmap_google (id, token) VALUES ($clientID, '$token');";
			$this->_db->setQuery ( $query );
			$result = $this->_db->execute ();
			
			// Store logged in status in session
			$session = JFactory::getSession();
			$session->set('jmap_ga_authenticate', true);
		} catch ( JMapException $e ) {
			$this->app->enqueueMessage ( $e->getMessage (), $e->getErrorLevel () );
			$result = false;
		} catch ( Exception $e ) {
			$jmapException = new JMapException ( $e->getMessage (), 'error' );
			$this->app->enqueueMessage ( $jmapException->getMessage (), $jmapException->getErrorLevel () );
			$result = false;
		}
		
		return $result;
	}
	
	/**
	 * Delete the Google token
	 *
	 * @access private
	 * @return boolean
	 */
	private function deleteToken() {
		$clientID = (int)$this->app->getClientId();
		try {
			$query = "DELETE FROM #__jmap_google WHERE id = " . $clientID;
			$this->_db->setQuery ( $query )->execute();
			
			// Store logged in status in session
			$session = JFactory::getSession();
			$session->clear('jmap_ga_authenticate');
		} catch ( JMapException $e ) {
			$this->app->enqueueMessage ( $e->getMessage (), $e->getErrorLevel () );
			return false;
		} catch ( Exception $e ) {
			$jmapException = new JMapException ( $e->getMessage (), 'error' );
			$this->app->enqueueMessage ( $jmapException->getMessage (), $jmapException->getErrorLevel () );
			return false;
		}
		
		return true;
	}
	
	/**
	 * Get visits
	 *
	 * @access private
	 * @return array
	 */
	private function getVisitsByCountry($service, $projectId, $from, $to, $params) {
		$metrics = 'ga:sessions';
		$dimensions = 'ga:country';
		try {
			$serial = 'gadash_qr7' . str_replace ( array (
					'ga:',
					',',
					'-',
					date ( 'Y' ) 
			), "", $projectId . $from . $to );
			$data = $service->data_ga->get ( 'ga:' . $projectId, $from, $to, $metrics, array (
					'dimensions' => $dimensions 
			) );
		} catch ( Exception $e ) {
			return "<br />&nbsp;&nbsp;Error: " . $e->getMessage ();
		}
		if (! $data ['rows']) {
			return 0;
		}
		
		$ga_dash_data = "";
		for($i = 0; $i < $data ['totalResults']; $i ++) {
			$ga_dash_data .= "['" . addslashes ( $data ['rows'] [$i] [0] ) . "'," . $data ['rows'] [$i] [1] . "],";
		}
		
		return $ga_dash_data;
	}
	
	/**
	 * Get Traffic Sources
	 *
	 * @access private
	 * @return array
	 */
	private function getTrafficSources($service, $projectId, $from, $to, $params) {
		$metrics = 'ga:sessions';
		$dimensions = 'ga:medium';
		try {
			$serial = 'gadash_qr8' . str_replace ( array (
					'ga:',
					',',
					'-',
					date ( 'Y' ) 
			), "", $projectId . $from . $to );
			$data = $service->data_ga->get ( 'ga:' . $projectId, $from, $to, $metrics, array (
					'dimensions' => $dimensions 
			) );
		} catch ( Exception $e ) {
			return "<br />&nbsp;&nbsp;Error: " . $e->getMessage ();
		}
		if (! $data ['rows']) {
			return 0;
		}
		
		$ga_dash_data = "";
		for($i = 0; $i < $data ['totalResults']; $i ++) {
			$ga_dash_data .= "['" . addslashes ( str_replace ( "(none)", "direct", $data ['rows'] [$i] [0] ) ) . "'," . $data ['rows'] [$i] [1] . "],";
		}
		
		return $ga_dash_data;
	}
	
	/**
	 * Get New vs. Returning
	 *
	 * @access private
	 * @return array
	 */
	private function getNewReturnVisitors($service, $projectId, $from, $to, $params) {
		$metrics = 'ga:sessions';
		$dimensions = 'ga:userType';
		try {
			$serial = 'gadash_qr9' . str_replace ( array (
					'ga:',
					',',
					'-',
					date ( 'Y' ) 
			), "", $projectId . $from . $to );
			$data = $service->data_ga->get ( 'ga:' . $projectId, $from, $to, $metrics, array (
					'dimensions' => $dimensions 
			) );
		} catch ( Exception $e ) {
			return "<br />&nbsp;&nbsp;Error: " . $e->getMessage ();
		}
		
		if (! $data ['rows']) {
			return 0;
		}
		
		$ga_dash_data = "";
		for($i = 0; $i < $data ['totalResults']; $i ++) {
			$ga_dash_data .= "['" . addslashes ( $data ['rows'] [$i] [0] ) . "'," . $data ['rows'] [$i] [1] . "],";
		}
		
		return $ga_dash_data;
	}
	
	/**
	 * Get Top Pages
	 *
	 * @access private
	 * @return array
	 */
	private function getTopPages($service, $projectId, $from, $to, $params) {
		$metrics = 'ga:pageviews';
		$dimensions = 'ga:pageTitle';
		try {
			$serial = 'gadash_qr4' . str_replace ( array (
					'ga:',
					',',
					'-',
					date ( 'Y' ) 
			), "", $projectId . $from . $to );
			$data = $service->data_ga->get ( 'ga:' . $projectId, $from, $to, $metrics, array (
					'dimensions' => $dimensions,
					'sort' => '-ga:pageviews',
					'max-results' => $params->get('ga_num_results', 24),
					'filters' => 'ga:pagePath!=/' 
			) );
		} catch ( Exception $e ) {
			return "<br />&nbsp;&nbsp;Error: " . $e->getMessage ();
		}
		if (! $data ['rows']) {
			return 0;
		}
		
		$ga_dash_data = "";
		$i = 0;
		while ( isset ( $data ['rows'] [$i] [0] ) ) {
			$ga_dash_data .= "['" . addslashes ( $data ['rows'] [$i] [0] ) . "'," . $data ['rows'] [$i] [1] . "],";
			$i ++;
		}
		
		return $ga_dash_data;
	}
	
	/**
	 * Get Top referrers
	 *
	 * @access private
	 * @return array
	 */
	private function getTopReferrers($service, $projectId, $from, $to, $params) {
		$metrics = 'ga:sessions';
		$dimensions = 'ga:source,ga:medium';
		try {
			$serial = 'gadash_qr5' . str_replace ( array (
					'ga:',
					',',
					'-',
					date ( 'Y' ) 
			), "", $projectId . $from . $to );
			$data = $service->data_ga->get ( 'ga:' . $projectId, $from, $to, $metrics, array (
					'dimensions' => $dimensions,
					'sort' => '-ga:sessions',
					'max-results' => $params->get('ga_num_results', 24),
					'filters' => 'ga:medium==referral' 
			) );
		} catch ( Exception $e ) {
			return "<br />&nbsp;&nbsp;Error: " . $e->getMessage ();
		}
		if (! $data ['rows']) {
			return 0;
		}
		
		$ga_dash_data = "";
		$i = 0;
		while ( isset ( $data ['rows'] [$i] [0] ) ) {
			$ga_dash_data .= "['" . addslashes ( $data ['rows'] [$i] [0] ) . "'," . $data ['rows'] [$i] [2] . "],";
			$i ++;
		}
		
		return $ga_dash_data;
	}
	
	/**
	 * Get Top searches
	 *
	 * @access private
	 * @return array
	 */
	private function getTopSearches($service, $projectId, $from, $to, $params) {
		$metrics = 'ga:sessions';
		$dimensions = 'ga:keyword';
		try {
			$serial = 'gadash_qr6' . str_replace ( array (
					'ga:',
					',',
					'-',
					date ( 'Y' ) 
			), "", $projectId . $from . $to );
			$data = $service->data_ga->get ( 'ga:' . $projectId, $from, $to, $metrics, array (
					'dimensions' => $dimensions,
					'sort' => '-ga:sessions',
					'max-results' => $params->get('ga_num_results', 24),
					'filters' => 'ga:keyword!=(not provided);ga:keyword!=(not set)' 
			) );
		} catch ( Exception $e ) {
			return "<br />&nbsp;&nbsp;Error: " . $e->getMessage ();
		}
		if (! $data ['rows']) {
			return 0;
		}
		
		$ga_dash_data = "";
		$i = 0;
		while ( isset ( $data ['rows'] [$i] [0] ) ) {
			$ga_dash_data .= "['" . addslashes ( $data ['rows'] [$i] [0] ) . "'," . $data ['rows'] [$i] [1] . "],";
			$i ++;
		}
		
		return $ga_dash_data;
	}

	/**
	 * Return Google profile identifier object
	 *
	 * @access public
	 * @param string
	 * @return array
	 */
	private function getSitesProfiles($service, $client, $params) {
		try {
			$profile_switch = "";
			$serial = 'gadash_qr1';
			$profiles = $service->management_profiles->listManagementProfiles ( '~all', '~all' );
		} catch ( Exception $e ) {
			return $e;
		}

		$debugBuffer = null;
		$items = $profiles->getItems ();
		if (count ( $items ) != 0) {
			foreach ( $items as &$profile ) {
				$profileid = $profile->getId ();
				$this->currentProfile = $profile;
				$currentProfileUrl = $profile->getWebsiteUrl ();
				if($params->get('enable_debug', 0)) {
					$debugBuffer .= '<li>' . $currentProfileUrl . '</li>';
				}
				if ($this->purifyDomain ( $currentProfileUrl ) == $this->purifyDomain ( $params->get ( 'ga_domain', JUri::root () ) )) {
					return $profileid;
				}
			}
			// Fallback on the latest added domain to Google Analytics if no match found, with domain dumping if debug is enabled
			if($params->get('enable_debug', 0)) {
				echo JText::sprintf('COM_JMAP_GOOGLE_ANALYTICS_DEBUGINFO', $debugBuffer);
			}
			return $profileid;
		}
	}
	
	/**
	 * Main get data method
	 *
	 * @access public
	 * @return mixed Returns a data string if success or boolean if exceptions are trigged
	 */
	public function getData() {
		$params = $this->getComponentParams ();
		
		// Perform the authentication management before going on
		$authenticationData = $this->authentication ( $params );
		if($authenticationData) {
			return $authenticationData;
		}
		
		// New Service instance for the API, Google_Service_Analytics
		$service = new Google_Service_Analytics ( $this->client );
		
		$projectId = $this->getSitesProfiles ( $service, $this->client, $params );
		
		if ( $projectId instanceof Exception ) {
			$this->deleteToken();
			$this->app->enqueueMessage ( $projectId->getMessage (), 'warning' );
			return '<a class="btn btn-primary" href="index.php?option=com_jmap&amp;task=google.display">' . JText::_ ( 'COM_JMAP_GOBACK' ) . '</a>';
		}
		
		if ($this->app->input->get('gaquery')) {
			$gaquery = $this->app->input->get('gaquery');
		} else {
			$gaquery = "sessions";
		}
		
		if ($this->app->input->get('gaperiod')) {
			$gaperiod = $this->app->input->get('gaperiod');
		} else {
			$gaperiod = "last30days";
		}
		
		switch ($gaperiod) {
			
			case 'today' :
				$from = date ( 'Y-m-d' );
				$to = date ( 'Y-m-d' );
				$showevery = 5;
				break;
			
			case 'yesterday' :
				$from = date ( 'Y-m-d', time () - 24 * 60 * 60 );
				$to = date ( 'Y-m-d', time () - 24 * 60 * 60 );
				$showevery = 5;
				break;
			
			case 'last7days' :
				$from = date ( 'Y-m-d', time () - 7 * 24 * 60 * 60 );
				$to = date ( 'Y-m-d' );
				$showevery = 3;
				break;
			
			case 'last14days' :
				$from = date ( 'Y-m-d', time () - 14 * 24 * 60 * 60 );
				$to = date ( 'Y-m-d' );
				$showevery = 4;
				break;
				
			case 'last3months' :
				$from = date ( 'Y-m-d', time () - 90 * 24 * 60 * 60 );
				$to = date ( 'Y-m-d' );
				$showevery = 4;
				break;
			
			case 'last6months' :
				$from = date ( 'Y-m-d', time () - 180 * 24 * 60 * 60 );
				$to = date ( 'Y-m-d' );
				$showevery = 4;
				break;
				
			case 'last12months' :
				$from = date ( 'Y-m-d', time () - 365 * 24 * 60 * 60 );
				$to = date ( 'Y-m-d' );
				$showevery = 4;
				break;
			
			default :
				$from = date ( 'Y-m-d', time () - 30 * 24 * 60 * 60 );
				$to = date ( 'Y-m-d' );
				$showevery = 6;
				break;
		}
		
		switch ($gaquery) {
			
			case 'users' :
				$title = JText::_ ( 'COM_JMAP_GOOGLE_VISITORS' );
				break;
			
			case 'pageviews' :
				$title = JText::_ ( 'COM_JMAP_GOOGLE_PAGE_VIEWS' );
				break;
			
			case 'bounceRate' :
				$title = JText::_ ( 'COM_JMAP_GOOGLE_BOUNCE_RATE' );
				break;
			
			case 'organicSearches' :
				$title = JText::_ ( 'COM_JMAP_GOOGLE_ORGANIC_SEARCHES' );
				break;
			
			default :
				$title = JText::_ ( 'COM_JMAP_GOOGLE_VISITS' );
		}
		
		$metrics = 'ga:' . $gaquery;
		$dimensions = 'ga:year,ga:month,ga:day';
		
		if ($gaperiod == "today" or $gaperiod == "yesterday") {
			$dimensions = 'ga:hour';
		} else {
			$dimensions = 'ga:year,ga:month,ga:day';
		}
		
		try {
			$serial = 'gadash_qr2' . str_replace ( array (
					'ga:',
					',',
					'-',
					date ( 'Y' ) 
			), "", $projectId . $from . $to . $metrics );
			$data = $service->data_ga->get ( 'ga:' . $projectId, $from, $to, $metrics, array (
					'dimensions' => $dimensions 
			) );
		} catch ( Exception $e ) {
			return "<br />&nbsp;&nbsp;Error: " . $e->getMessage ();
		}
		$gadash_data = "";
		for($i = 0; $i < $data ['totalResults']; $i ++) {
			if ($gaperiod == "today" or $gaperiod == "yesterday") {
				$gadash_data .= "['" . $data ['rows'] [$i] [0] . ":00'," . round ( $data ['rows'] [$i] [1], 2 ) . "],";
			} else {
				$gadash_data .= "['" . $data ['rows'] [$i] [0] . "-" . $data ['rows'] [$i] [1] . "-" . $data ['rows'] [$i] [2] . "'," . round ( $data ['rows'] [$i] [3], 2 ) . "],";
			}
		}
		// Avoid errors in the drawing phase of the visits map
		if(!$gadash_data) {
			$gadash_data = "['" . date('Y-m-d') . "',0]";
		}
		
		$metrics = 'ga:sessions,ga:users,ga:pageviews,ga:bounceRate,ga:organicSearches,ga:sessionDuration';
		$dimensions = 'ga:year';
		try {
			$serial = 'gadash_qr3' . str_replace ( array (
					'ga:',
					',',
					'-',
					date ( 'Y' ) 
			), "", $projectId . $from . $to );
			$data = $service->data_ga->get ( 'ga:' . $projectId, $from, $to, $metrics, array (
					'dimensions' => $dimensions 
			) );
		} catch ( Exception $e ) {
			return "<br />&nbsp;&nbsp;Error: " . $e->getMessage ();
		}
		
		$code = '<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
	  google.load("maps", "3", { other_params : "key=AIzaSyC1xyDpvjdNwCcKmZ3StzX2oH8PfRKW_aI"});
      google.setOnLoadCallback(ga_dash_callback);
	
	  function ga_dash_callback(){
			ga_dash_drawstats();
			if(typeof ga_dash_drawmap == "function"){
				ga_dash_drawmap();
			}
			if(typeof ga_dash_drawpgd == "function"){
				ga_dash_drawpgd();
			}
			if(typeof ga_dash_drawrd == "function"){
				ga_dash_drawrd();
			}
			if(typeof ga_dash_drawsd == "function"){
				ga_dash_drawsd();
			}
			if(typeof ga_dash_drawtraffic == "function"){
				ga_dash_drawtraffic();
			}
	  }
	
      function ga_dash_drawstats() {
        var data = google.visualization.arrayToDataTable([' . "
          ['" . JText::_ ( 'COM_JMAP_GOOGLE_DATE' ) . "', '" . $title . "']," . $gadash_data . "
        ]);
	
        var options = {
		  legend: {position: 'none'},
		  " . "colors:['#3366CC','#2B56AD']," . "
		  pointSize: 3,
          title: '" . $title . "',
		  chartArea: {width: '95%'},
          hAxis: { title: '" . JText::_ ( 'COM_JMAP_GOOGLE_DATE' ) . "',  titleTextStyle: {color: 'black'}, showTextEvery: " . $showevery . "},
		  vAxis: { textPosition: 'none', minValue: 0}
		};
	
        var chart = new google.visualization.AreaChart(document.getElementById('gadash_div'));
		chart.draw(data, options);
	
      }";
		
		$getVisitsByCountry = $this->getVisitsByCountry ( $service, $projectId, $from, $to, $params );
		if ($getVisitsByCountry) {
			$code .= '
		google.load("visualization", "1", {packages:["geochart"]})
		function ga_dash_drawmap() {
		var data = google.visualization.arrayToDataTable([' . "
		  ['Country', 'Visits']," . $getVisitsByCountry . "
		]);
	
		var options = {
			colors: ['white', '" . "blue" . "']
		};
	
		var chart = new google.visualization.GeoChart(document.getElementById('ga_dash_mapdata'));
		chart.draw(data, options);
	
	  }";
		}
		
		$getTrafficSources = $this->getTrafficSources ( $service, $projectId, $from, $to, $params );
		$getNewReturnVisitors = $this->getNewReturnVisitors ( $service, $projectId, $from, $to, $params );
		if ($getTrafficSources && $getNewReturnVisitors) {
			$code .= '
		google.load("visualization", "1", {packages:["corechart"]})
		function ga_dash_drawtraffic() {
		var data = google.visualization.arrayToDataTable([' . "
		  ['Source', 'Visits']," . $getTrafficSources . '
		]);

		var datanvr = google.visualization.arrayToDataTable([' . "
		  ['Type', 'Visits']," . $getNewReturnVisitors . "
		]);
	
		var chart = new google.visualization.PieChart(document.getElementById('ga_dash_trafficdata'));
		chart.draw(data, {
			is3D: false,
			tooltipText: 'percentage',
			legend: 'none',
			title: 'Traffic Sources',
			colors: ['" . "#001BB5" . "', '" . "#2D41AF" . "', '" . "#00137F" . "', '" . "blue" . "', '" . "#425AE5" . "']
		});
	
		var gadash = new google.visualization.PieChart(document.getElementById('ga_dash_nvrdata'));
		gadash.draw(datanvr,  {
			is3D: false,
			tooltipText: 'percentage',
			legend: 'none',
			title: 'New vs. Returning',
			colors: ['" . "#001BB5" . "', '" . "#2D41AF" . "', '" . "#00137F" . "', '" . "blue" . "', '" . "#425AE5" . "']
		});
	
	  }";
		}
		
		$getTopPages = $this->getTopPages ( $service, $projectId, $from, $to, $params );
		if ($getTopPages) {
			$code .= '
		google.load("visualization", "1", {packages:["table"]})
		function ga_dash_drawpgd() {
		var data = google.visualization.arrayToDataTable([' . "
		  ['Top Pages', 'Visits']," . $getTopPages . "
		]);
	
		var options = {
			page: 'enable',
			pageSize: 6,
			width: '100%'
		};
	
		var chart = new google.visualization.Table(document.getElementById('ga_dash_pgddata'));
		chart.draw(data, options);
	
	  }";
		}
		
		$getTopReferrers = $this->getTopReferrers ( $service, $projectId, $from, $to, $params );
		if ($getTopReferrers) {
			$code .= '
		google.load("visualization", "1", {packages:["table"]})
		function ga_dash_drawrd() {
		var datar = google.visualization.arrayToDataTable([' . "
		  ['Top Referrers', 'Visits']," . $getTopReferrers . "
		]);
	
		var options = {
			page: 'enable',
			pageSize: 6,
			width: '100%'
		};
	
		var chart = new google.visualization.Table(document.getElementById('ga_dash_rdata'));
		chart.draw(datar, options);
	
	  }";
		}
		
		$getTopSearches = $this->getTopSearches ( $service, $projectId, $from, $to, $params );
		if ($getTopSearches) {
			$code .= '
		google.load("visualization", "1", {packages:["table"]})
		function ga_dash_drawsd() {
	
		var datas = google.visualization.arrayToDataTable([' . "
		  ['Top Searches', 'Visits']," . $getTopSearches . "
		]);
	
		var options = {
			page: 'enable',
			pageSize: 6,
			width: '100%'
		};
	
		var chart = new google.visualization.Table(document.getElementById('ga_dash_sdata'));
		chart.draw(datas, options);
	
	  }";
		}
		
		$code .= "
	
	jQuery(window).resize(function(){
		if(typeof ga_dash_drawstats == 'function'){
			ga_dash_drawstats();
		}
		if(typeof ga_dash_drawmap == 'function'){
			ga_dash_drawmap();
		}
		if(typeof ga_dash_drawpgd == 'function'){
			ga_dash_drawpgd();
		}
		if(typeof ga_dash_drawrd == 'function'){
			ga_dash_drawrd();
		}
		if(typeof ga_dash_drawsd == 'function'){
			ga_dash_drawsd();
		}
		if(typeof ga_dash_drawtraffic == 'function'){
			ga_dash_drawtraffic();
		}
	});
	
	</script>" . 
	($this->currentProfile->getWebsiteUrl() ? "<span class='label label-primary label-large'>" . $this->currentProfile->getWebsiteUrl() . "</span>" : null) .
	($this->hasOwnCredentials ? null : "<span data-content='" . JText::_('COM_JMAP_GOOGLE_APP_NOTSET_DESC') . "' class='label label-warning hasPopover google pull-right'>" . JText::_('COM_JMAP_GOOGLE_APP_NOTSET') . "</span>") .
	'<div id="ga-dash">
		<div class="btn-toolbar">
			<div class="btn-wrapper"><button class="btn btn-default' . ($gaperiod == "today" ? ' active' : '') . '" onclick="document.getElementById(\'gaperiod\').value=\'today\'">' . JText::_ ( 'COM_JMAP_GOOGLE_TODAY' ) . '</button></div>
			<div class="btn-wrapper"><button class="btn btn-default' . ($gaperiod == "yesterday" ? ' active' : '') . '" onclick="document.getElementById(\'gaperiod\').value=\'yesterday\'">' . JText::_ ( 'COM_JMAP_GOOGLE_YESTERDAY' ) . '</button></div>
			<div class="btn-wrapper"><button class="btn btn-default' . ($gaperiod == "last7days" ? ' active' : '') . '" onclick="document.getElementById(\'gaperiod\').value=\'last7days\'">' . JText::_ ( 'COM_JMAP_GOOGLE_LAST7DAYS' ) . '</button></div>
			<div class="btn-wrapper"><button class="btn btn-default' . ($gaperiod == "last14days" ? ' active' : '') . '" onclick="document.getElementById(\'gaperiod\').value=\'last14days\'">' . JText::_ ( 'COM_JMAP_GOOGLE_LAST14DAYS' ) . '</button></div>
			<div class="btn-wrapper"><button class="btn btn-default' . ($gaperiod == "last30days" ? ' active' : '') . '" onclick="document.getElementById(\'gaperiod\').value=\'last30days\'">' . JText::_ ( 'COM_JMAP_GOOGLE_LAST30DAYS' ) . '</button></div>
			<div class="btn-wrapper"><button class="btn btn-default' . ($gaperiod == "last3months" ? ' active' : '') . '" onclick="document.getElementById(\'gaperiod\').value=\'last3months\'">' . JText::_ ( 'COM_JMAP_GOOGLE_LAST3MONTHS' ) . '</button></div>
			<div class="btn-wrapper"><button class="btn btn-default' . ($gaperiod == "last6months" ? ' active' : '') . '" onclick="document.getElementById(\'gaperiod\').value=\'last6months\'">' . JText::_ ( 'COM_JMAP_GOOGLE_LAST6MONTHS' ) . '</button></div>
			<div class="btn-wrapper"><button class="btn btn-default' . ($gaperiod == "last12months" ? ' active' : '') . '" onclick="document.getElementById(\'gaperiod\').value=\'last12months\'">' . JText::_ ( 'COM_JMAP_GOOGLE_LAST12MONTHS' ) . '</button></div>
		</div>
	
		<div class="panel panel-info panel-group panel-group-google" id="jmap_googlegraph_accordion">
			<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_googlestats_graph">
				<h4><span class="glyphicon glyphicon-stats"></span> ' . JText::_ ('COM_JMAP_GOOGLE_STATS' ) . '</h4>
			</div>
			<div id="jmap_googlestats_graph" class="panel-body panel-collapse  collapse" >
				<div class="btn-toolbar">
					<div class="btn-wrapper"><button class="btn btn-default' . ($gaquery == "users" ? ' active' : '') . '" onclick="document.getElementById(\'gaquery\').value=\'users\'">' . JText::_ ( 'COM_JMAP_GOOGLE_METRIC_VISITORS' ) . '</button></div>
					<div class="btn-wrapper"><button class="btn btn-default' . ($gaquery == "pageviews" ? ' active' : '') . '" onclick="document.getElementById(\'gaquery\').value=\'pageviews\'">' . JText::_ ( 'COM_JMAP_GOOGLE_METRIC_PAGEVIEWS' ) . '</button></div>
					<div class="btn-wrapper"><button class="btn btn-default' . ($gaquery == "bounceRate" ? ' active' : '') . '" onclick="document.getElementById(\'gaquery\').value=\'bounceRate\'">' . JText::_ ( 'COM_JMAP_GOOGLE_METRIC_BOUNCERATE' ) . '</button></div>
					<div class="btn-wrapper"><button class="btn btn-default' . ($gaquery == "organicSearches" ? ' active' : '') . '" onclick="document.getElementById(\'gaquery\').value=\'organicSearches\'">' . JText::_ ( 'COM_JMAP_GOOGLE_METRIC_ORGANICSEARCHES' ) . '</button></div>
					<div class="btn-wrapper"><button class="btn btn-default' . ($gaquery == "sessions" ? ' active' : '') . '" onclick="document.getElementById(\'gaquery\').value=\'sessions\'">' . JText::_ ( 'COM_JMAP_GOOGLE_METRIC_VISITS' ) . '</button></div>
				</div>
				<div id="gadash_div" style="height:350px;"></div>
				<table class="gatable" cellpadding="4" width="100%" align="center">
					<tr>
						<td width="24%">' . JText::_ ( 'COM_JMAP_GOOGLE_VISITS' ) . ':</td>
						<td width="12%" class="gavalue"><a href="javascript:void(0);" class="gatable">' . $data ['rows'] [0] [1] . '</td>
						<td width="24%">' . JText::_ ( 'COM_JMAP_GOOGLE_VISITORS' ) . ':</td>
						<td width="12%" class="gavalue"><a href="javascript:void(0);" class="gatable">' . $data ['rows'] [0] [2] . '</a></td>
						<td width="24%">' . JText::_ ( 'COM_JMAP_GOOGLE_PAGE_VIEWS' ) . ':</td>
						<td width="12%" class="gavalue"><a href="javascript:void(0);" class="gatable">' . $data ['rows'] [0] [3] . '</a></td>
					</tr>
					<tr>
						<td>' . JText::_ ( 'COM_JMAP_GOOGLE_BOUNCE_RATE' ) . ':</td>
						<td class="gavalue"><a href="javascript:void(0);" class="gatable">' . round ( $data ['rows'] [0] [4], 2 ) . '%</a></td>
						<td>' . JText::_ ( 'COM_JMAP_GOOGLE_ORGANIC_SEARCHES' ) . ':</td>
						<td class="gavalue"><a href="javascript:void(0);" class="gatable">' . $data ['rows'] [0] [5] . '</a></td>
						<td>' . JText::_ ( 'COM_JMAP_GOOGLE_PAGES_VISIT' ) . ':</td>
						<td class="gavalue"><a href="javascript:void(0);" class="gatable">' . (($data ['rows'] [0] [1]) ? round ( $data ['rows'] [0] [3] / $data ['rows'] [0] [1], 2 ) : '0') . '</a></td>
					</tr>
				</table>
			</div>
		</div>';
		
		$JText = 'JText';
		$multiReports = <<<MULTIREPORTS
						<div class="panel panel-info panel-group panel-group-google" id="jmap_googlegeo_accordion">
							<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_googlestats_geo">
								<h4><span class="glyphicon glyphicon-picture"></span> {$JText::_ ('COM_JMAP_GOOGLE_MAP' )}</h4>
							</div>
							<div id="jmap_googlestats_geo" class="panel-body panel-collapse  collapse">
								<div id="ga_dash_mapdata"></div>
							</div>
						</div>
						
						<div class="panel panel-info panel-group panel-group-google" id="jmap_googletraffic_accordion">
							<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_googlestats_traffic">
								<h4><span class="glyphicon glyphicon-sort"></span> {$JText::_ ('COM_JMAP_GOOGLE_TRAFFIC' )}</h4>
							</div>
							<div id="jmap_googlestats_traffic" class="panel-body panel-collapse  collapse">
								<div id="ga_dash_trafficdata"></div><div id="ga_dash_nvrdata"></div>
							</div>
						</div>
						
						<div class="panel panel-info panel-group panel-group-google" id="jmap_googlereferrer_accordion">
							<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_googlestats_referrers">
								<h4><span class="glyphicon glyphicon-log-in"></span> {$JText::_ ('COM_JMAP_GOOGLE_REFERRERS' )}</h4>
							</div>
							<div id="jmap_googlestats_referrers" class="panel-body panel-collapse  collapse">
								<div id="ga_dash_rdata"></div>
							</div>
						</div>
						
						<div class="panel panel-info panel-group panel-group-google" id="jmap_googlesearches_accordion">
							<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_googlestats_searches">
								<h4><span class="glyphicon glyphicon-search"></span> {$JText::_ ('COM_JMAP_GOOGLE_SEARCHES' )}</h4>
							</div>
							<div id="jmap_googlestats_searches" class="panel-body panel-collapse  collapse">
								<div id="ga_dash_sdata"></div>
							</div>
						</div>
						
						<div class="panel panel-info panel-group panel-group-google" id="jmap_googlepages_accordion">
							<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_googlestats_pages">
								<h4><span class="glyphicon glyphicon-file"></span> {$JText::_ ('COM_JMAP_GOOGLE_PAGES' )}</h4>
							</div>
							<div id="jmap_googlestats_pages" class="panel-body panel-collapse  collapse">
								<div id="ga_dash_pgddata"></div>
							</div>
						</div>
MULTIREPORTS;
		
		$code .= $multiReports;
		$code .= '</div>';
		
		return $code;
	}
	
	/**
	 * Get data method for webmasters tools stats
	 *
	 * @access public
	 * @return mixed Returns a data string if success or boolean if exceptions are trigged
	 */
	public function getDataWebmasters() {
		$params = $this->getComponentParams ();
	
		// Perform the authentication management before going on
		$authenticationData = $this->authentication ( $params );
		if($authenticationData) {
			$this->state->set('loggedout', true);
			$authenticationData .= '<input type="hidden" name="googlestats" value="webmasters" />';
			return $authenticationData;
		}

		// Set the analyzed domain in the model state
		$webmastersStatsDomain = $this->purifyWebmastersDomain( $params->get ( 'wm_domain', JUri::root() )) ;
		$this->state->set('stats_domain', $webmastersStatsDomain);
		$this->state->set('has_own_credentials', $this->hasOwnCredentials);

		// New Service instance for the API, Google_Service_Webmasters
		$service = new Google_Service_Webmasters ( $this->client );

		$results = array();

		try {
			// Fetch sitemaps stats
			$results['sitemaps'] = $service->sitemaps->listSitemaps($webmastersStatsDomain);

			// New query request post body object
			$postBody = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
			$postBody->setStartDate($this->getState('fromPeriod'));
			$postBody->setEndDate($this->getState('toPeriod'));

			// Fetch data metric
			$postBody->setDimensions(array('query'));
			$results['results_query'] = $service->searchanalytics->query($webmastersStatsDomain, $postBody);

			// Fetch data metric
			$postBody->setDimensions(array('page'));
			$results['results_page'] = $service->searchanalytics->query($webmastersStatsDomain, $postBody);
			
			// Fetch data metric
			$postBody->setDimensions(array('device'));
			$results['results_device'] = $service->searchanalytics->query($webmastersStatsDomain, $postBody);
			
			// Fetch data metric
			$postBody->setDimensions(array('country'));
			$results['results_country'] = $service->searchanalytics->query($webmastersStatsDomain, $postBody);
			
			// Fetch data metric
			$postBody->setDimensions(array('searchAppearance'));
			$results['results_search'] = $service->searchanalytics->query($webmastersStatsDomain, $postBody);
		} catch ( Google_Exception $e ) {
			$jmapException = new JMapException ( $e->getMessage (), 'error' );
			$this->app->enqueueMessage ( $jmapException->getMessage (), $jmapException->getErrorLevel () );
			$result = array();
		} catch ( Exception $e ) {
			$jmapException = new JMapException ( $e->getMessage (), 'error' );
			$this->app->enqueueMessage ( $jmapException->getMessage (), $jmapException->getErrorLevel () );
			$result = array();
		}

		return $results;
	}

	/**
	 * Get data method for free Alexa stats by scraping even manipulating them for anonymization
	 *
	 * @access public
	 * @return mixed string
	 */
	public function getDataAlexa() {
		$cParams = $this->getComponentParams ();
		
		// Build the purified domain to scrape using the host only
		$domain = $cParams->get ( 'ga_domain', JUri::root () );
		$hostDomain = $this->getHost ( $domain );
		$url = "https://www.alexa.com/siteinfo/$hostDomain";
		
		try {
			// Fetch remote data to scrape
			$httpTransport = $cParams->get('analytics_service_http_transport', 'curl') == 'socket' ? new JMapHttpTransportSocket () : new JMapHttpTransportCurl ();
			$connectionAdapter = new JMapHttp ( $httpTransport, $cParams );
			$httpResponse = $connectionAdapter->get ( $url );
			
			// Check if HTTP status code is 200 OK
			if ($httpResponse->code != 200 || !$httpResponse->body) {
				throw new RuntimeException ( JText::sprintf( 'COM_JMAP_ERROR_RETRIEVING_STATS', $httpResponse->code) );
			}
			
			// Process result, all assets must be canonicalized to point to the native website https://www.alexa.com
			$httpResponse->body = preg_replace ( '/src="\//i', 'src="https://www.alexa.com/', $httpResponse->body );
			$httpResponse->body = preg_replace ( '/href="\/([^\/])/i', 'href="https://www.alexa.com/$1', $httpResponse->body );
			
			// Remove all wrong mobile classes
			$httpResponse->body = preg_replace ( '/table fancymobile/i', 'table', $httpResponse->body );
			
			// Process DOM html
			require_once JPATH_ROOT . '/plugins/system/jmap/simplehtmldom.php';
			$simpleHtmlDomInstance = new JMapSimpleHtmlDom ();
			$simpleHtmlDomInstance->load ( $httpResponse->body );
			
			// Find and remove inline script tags
			foreach ( $simpleHtmlDomInstance->find ( 'script' ) as $element ) {
				if (! $element->hasAttribute ( 'src' ) && ! $element->hasAttribute ( 'type' )) {
					$element->outertext = '';
				}
				
				// Check for inner mixpanel scripts and remove element if any
				$innerText = $element->innertext;
				if (stripos ( $innerText, 'mixpanel' ) !== false) {
					$element->outertext = '';
				}
				
				// Remove singleSite.js script
				if (stripos ( $element->getAttribute ( 'src' ), 'singleSite.js' ) !== false) {
					$element->outertext = '';
				}
			}
			
			// Find and remove undesired Alexa native elements
			foreach ( $simpleHtmlDomInstance->find ( 'div.StartingState,div.smalltitle,div.EnterSite,a.Button.FancyScroll,.MarketingPanel,.PromoPanel,#section_MarketingCard,#card_visitors,a.Locked,section.sources,section.ACard.Marketing,div.transparency,div.ALightbox,#ProfileMenu' ) as $element ) {
				$element->outertext = '';
			}
			
			// Neutralize the header and footer elements
			foreach ( $simpleHtmlDomInstance->find ( '#alx-header' ) as $element ) {
				$element->setAttribute ( 'style', 'display:none' );
				$element->setAttribute ( 'class', 'alx-header' );
				$element->innertext = '';
			}
			foreach ( $simpleHtmlDomInstance->find ( '#alx-footer' ) as $element ) {
				$element->setAttribute ( 'style', 'display:none' );
				$element->setAttribute ( 'class', 'alx-footer' );
				$element->innertext = '';
			}
			
			// Remove price button lock buttons and all alexa.com links
			foreach ( $simpleHtmlDomInstance->find ( 'a.Button.Outline,a.block.btn-small' ) as $element ) {
				if (stripos ( $element->getAttribute ( 'href' ), 'price' ) !== false || stripos ( $element->getAttribute ( 'href' ), 'alexa.com' ) !== false) {
					$element->outertext = '';
				}
			}
			
			// Remove any link that could be wrong
			foreach ( $simpleHtmlDomInstance->find ( 'a.truncation' ) as $element ) {
				$element->removeAttribute ( 'href' );
			}
			
			// Create a style element to have full control of the custom styling of inner contents
			$newElement = $simpleHtmlDomInstance->createElement ( 'style', 'div.row-fluid.FullState{display:block !important}div.MainPage>div.flex{border:1px solid #bce8f1;box-sizing:border-box}#alx-content div.rest{max-width:100%}h2.TemplateTitle{font-size:24px;color:#3a87ad;background-color:#d9edf7;margin-bottom:0;border-top-right-radius:5px;border-top-left-radius:5px;padding-left:10px;box-shadow:0 1px 5px #E0E0E0;border:1px solid #bce8f1;border-bottom:none}span.Desktop,span.Apopovertrigger{display:inline-block!important}span.CompoundTooltips{display:none!important}' );
			$simpleHtmlDomInstance->getElementByTagName ( 'head' )->appendChild ( $newElement );
			
			// Save and return the manipulated DOM structure
			$httpResponse->body = $simpleHtmlDomInstance->save ();
		} catch ( RuntimeException $e ) {
			return $e->getMessage ();
		} catch ( Exception $e ) {
			return $e->getMessage ();
		}
		
		return $httpResponse->body;
	}
	
	/**
	 * Get data method for free HypeStat stats by scraping even manipulating them for anonymization
	 *
	 * @access public
	 * @return mixed string
	 */
	public function getDataHypeStat() {
		$cParams = $this->getComponentParams ();
	
		// Build the purified domain to scrape using the host only
		$domain = $cParams->get ( 'ga_domain', JUri::root () );
		$hostDomain = $this->getHost ( $domain );
		$url = "https://hypestat.com/info/$hostDomain";
	
		try {
			// Fetch remote data to scrape
			$httpTransport = $cParams->get('analytics_service_http_transport', 'curl') == 'socket' ? new JMapHttpTransportSocket () : new JMapHttpTransportCurl ();
			$connectionAdapter = new JMapHttp ( $httpTransport, $cParams );
			$httpResponse = $connectionAdapter->get ( $url );
				
			// Check if HTTP status code is 200 OK
			if ($httpResponse->code != 200 || !$httpResponse->body) {
				throw new RuntimeException ( JText::sprintf( 'COM_JMAP_ERROR_RETRIEVING_STATS', $httpResponse->code) );
			}
			
			// Fix for wrong height of the Alexa graphs
			$httpResponse->body = preg_replace ( '/height="170"/i', '', $httpResponse->body );
			
			// Process DOM html
			require_once JPATH_ROOT . '/plugins/system/jmap/simplehtmldom.php';
			$simpleHtmlDomInstance = new JMapSimpleHtmlDom ();
			$simpleHtmlDomInstance->load ( $httpResponse->body );
				
			// Find and remove inline script tags
			foreach ( $simpleHtmlDomInstance->find ( 'script' ) as $element ) {
				// Remove adsbygoogle.js script
				if (stripos ( $element->getAttribute ( 'src' ), 'adsbygoogle' ) !== false) {
					$element->outertext = '';
				}
				// Remove gtag script
				if (stripos ( $element->getAttribute ( 'src' ), 'gtag' ) !== false) {
					$element->outertext = '';
				}
				
				if (stripos ( $element->getAttribute ( 'src' ), 'scrollmenu' ) !== false) {
					$element->outertext = '';
				}
				
				if (stripos ( $element->getAttribute ( 'src' ), 'main' ) !== false) {
					$element->outertext = '';
				}
				
				// Check for inner ads scripts
				$innerText = $element->innertext;
				if (stripos ( $innerText, 'gtag' ) !== false || 
					stripos ( $innerText, 'adsbygoogle' ) !== false ||
					stripos ( $innerText, 'scrollmenu' ) !== false ||
					stripos ( $innerText, 'HypeStat' ) !== false) {
					$element->outertext = '';
				}
			}

			// Find and remove undesired HypeStat native elements
			foreach ( $simpleHtmlDomInstance->find ( 'div.menu,div.menu-nav,div.header,#info,div.website_about,div.website_profile,div.index_main>h2,div.index_main>div.sections_sep,div.index_main>div.line,div.sem_banner,#whois,#dnslookup,#httpheader,#server,#footer,#update_m,div.lnote_m,#http2_button,#ssl_button' ) as $element ) {
				$element->outertext = '';
			}
			
			// Remove any link that could be wrong
			foreach ( $simpleHtmlDomInstance->find ( 'a' ) as $element ) {
				$element->removeAttribute ( 'href' );
			}
				
			// Create a style element to have full control of the custom styling of inner contents
			$newElement = $simpleHtmlDomInstance->createElement ( 'style', 'div.index_main>h1{float:none}div.index_main{padding:0}div.staticb{margin-top:0}div.pagespeed_r:nth-child(11){height:120px}div.right_side,div.pagespeed_r > *:not(#chart_div),#at-expanding-share-button{display:none}div.wrap{max-width:100%}div.index_main{width:100%}body,div.index_main,dt:hover,dl.site_report_sem dd,section dl dd{border: none;color:#000;background-color:#ffffff !important}dt,dd{color:#000}.alexa_subd dd{border-bottom:1px solid #e5e5e5}h1 span,h2 span,h3 span{color: #E98645}svg rect{fill: #FFF}g text{fill:#000}.traffic_sources_report dd span{opacity:.7}div span,section a,li a,dl.alexa_countries dd{color:#000 !important}dl.traffic_sources_report dd span{color:#FFF !important}');
			$simpleHtmlDomInstance->getElementByTagName ( 'head' )->appendChild ( $newElement );
			
			// Save and return the manipulated DOM structure
			$httpResponse->body = $simpleHtmlDomInstance->save ();
		} catch ( RuntimeException $e ) {
			return $e->getMessage ();
		} catch ( Exception $e ) {
			return $e->getMessage ();
		}
	
		return $httpResponse->body;
	}
	
	/**
	 * Get data method for free SearchMetrics stats by scraping even manipulating them for anonymization
	 * Additionally this method must use caching to prevent captcha locking
	 *
	 * @access public
	 * @return mixed string
	 */
	public function getDataSearchMetrics() {
		$cParams = $this->getComponentParams ();
		$outputCache = $this->getExtensionOutputCache ();
		
		// Build the purified domain to scrape using the host only
		$domain = $cParams->get ( 'ga_domain', JUri::root () );
		$hostDomain = $this->getHost ( $domain );
		$url = "https://www.similarweb.com/website/$hostDomain";
		
		// Check if data are available in the cache output storage building the ID based on the domain requested
		$cacheData = $outputCache->get ( 'jmap_searchmetrics_analytics_' . $hostDomain );
		if ($cacheData) {
			return $cacheData;
		}
		
		try {
			// Fetch remote data to scrape
			$httpTransport = $cParams->get ( 'analytics_service_http_transport', 'curl' ) == 'socket' ? 'file_get_contents' : new JMapHttpTransportCurl ();
			
			// CURL lib
			if (is_object ( $httpTransport )) {
				$connectionAdapter = new JMapHttp ( $httpTransport, $cParams );
				
				// Init headers
				$headers = array (
						'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
						'Accept-Encoding' => 'identity',
						'Accept-Language' => 'en,it;q=0.9,en-US;q=0.8,de;q=0.7,es;q=0.6,fr;q=0.5,ru;q=0.4,ja;q=0.3,el;q=0.2,sk;q=0.1,nl;q=0.1,ar;q=0.1,sv;q=0.1,da;q=0.1',
						'Cache-Control' => 'no-cache',
						'Connection' => 'keep-alive',
						'Host' => 'www.similarweb.com',
						'Pragma' => 'no-cache',
						'Referer' => 'https://www.similarweb.com/',
						'Upgrade-Insecure-Requests' => '1',
						'User-Agent' => 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36' 
				);
				
				$httpResponse = $connectionAdapter->get ( $url, $headers );
			} else {
				// file_get_contents case
				$opts = array (
						'http' => array (
								'method' => "GET",
								'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3\r\n" . "Accept-Encoding: identity\r\n" . "Accept-Language: en,it;q=0.9,en-US;q=0.8,de;q=0.7,es;q=0.6,fr;q=0.5,ru;q=0.4,ja;q=0.3,el;q=0.2,sk;q=0.1,nl;q=0.1,ar;q=0.1,sv;q=0.1,da;q=0.1\r\n" . "Cache-Control: no-cache\r\n" . "Connection: keep-alive\r\n" . "Host: www.similarweb.com\r\n" . "Pragma: no-cache\r\n" . "Referer: https://www.similarweb.com/\r\n" . "Upgrade-Insecure-Requests: 1\r\n" . "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36" 
						) 
				);
				$context = stream_context_create ( $opts );
				$response = @file_get_contents ( $url, false, $context );
				
				if ($response) {
					$httpResponse = new JMapHttpResponse ();
					$httpResponse->code = 200;
					$httpResponse->body = $response;
				} else {
					throw new RuntimeException ( JText::sprintf ( 'COM_JMAP_ERROR_RETRIEVING_STATS', 409 ) );
				}
			}
			
			// Check if HTTP status code is 200 OK
			if ($httpResponse->code != 200 || ! $httpResponse->body) {
				throw new RuntimeException ( JText::sprintf ( 'COM_JMAP_ERROR_RETRIEVING_STATS', $httpResponse->code ) );
			}
			
			// Process result, all assets must be canonicalized to point to the native website https://www.alexa.com
			$httpResponse->body = preg_replace ( '/src="\/\/www/i', 'src="https://www', $httpResponse->body );
			$httpResponse->body = preg_replace ( '/src="\//i', 'src="https://www.similarweb.com/', $httpResponse->body );
			$httpResponse->body = preg_replace ( '/href="\/([^\/])/i', 'href="https://www.similarweb.com/$1', $httpResponse->body );
			
			// Process DOM html
			require_once JPATH_ROOT . '/plugins/system/jmap/simplehtmldom.php';
			$simpleHtmlDomInstance = new JMapSimpleHtmlDom ();
			$simpleHtmlDomInstance->load ( $httpResponse->body );
			
			foreach ( $simpleHtmlDomInstance->find ( 'svg' ) as $element ) {
				$element->outertext = '';
			}
			
			// Find and remove inline script tags
			foreach ( $simpleHtmlDomInstance->find ( 'script' ) as $element ) {
				if (stripos ( $element->getAttribute ( 'src' ), 'smlrdstl.js' ) !== false) {
					$element->outertext = '';
				}
				
				// Check for inner mixpanel scripts and remove element if any
				$innerText = $element->innertext;
				if (stripos ( $innerText, 'mixpanel' ) !== false || 
					stripos ( $innerText, 'smlrdstl.js' ) !== false || 
					stripos ( $innerText, 'webengage' ) !== false || 
					stripos ( $innerText, 'connect.facebook.net' ) !== false) {
					$element->outertext = '';
				}
			}
			
			// Find and remove undesired Alexa native elements
			foreach ( $simpleHtmlDomInstance->find ( 'header,section.websiteHeader,div.websitePage-websiteMobileApps,div.websitePage-similarSites,div.analysisPage-pro,div.analysisPage-sectionFeedback,div.u-textCenter,div.js-proPopup' ) as $element ) {
				$element->outertext = '';
			}
			
			// Remove any link that could be wrong
			foreach ( $simpleHtmlDomInstance->find ( 'a' ) as $element ) {
				$element->removeAttribute ( 'href' );
			}
			
			// Create a style element to have full control of the custom styling of inner contents
			$newElement = $simpleHtmlDomInstance->createElement ( 'style', 'body.view-website.sw-notification-parent{padding:10px}div.analysisPage{max-width:100%}footer,div.js-overviewButton,div.sw-notification,div.cookies-notification,div.scroll-navbar-container,#intercom-css-container,#intercom-container,[class*="icon-"]::before,[class*="tooltip"]::before,span.naviTitle-icon,button.js-verifyButton{display:none!important}div.analysisPage-sectionContent{max-height:unset}h2.analysisPage-title{padding-left:0}div.js-navTitle{box-shadow: 0 2px 5px -2px grey;margin-bottom:20px}' );
			$simpleHtmlDomInstance->getElementByTagName ( 'head' )->appendChild ( $newElement );
			
			$newElementScript = $simpleHtmlDomInstance->createElement ( 'script', 'jQuery(function($){setTimeout(function(){$("html, body").animate({scrollTop: 20});$("body").css("padding", 0);}, 1500)});' );
			$simpleHtmlDomInstance->getElementByTagName ( 'head' )->appendChild ( $newElementScript );
			
			// Save and return the manipulated DOM structure
			$httpResponse->body = $simpleHtmlDomInstance->save ();
		} catch ( RuntimeException $e ) {
			return $e->getMessage ();
		} catch ( Exception $e ) {
			return $e->getMessage ();
		}
		
		// Cache store here building the ID based on the domain requested
		$outputCache->store ( $httpResponse->body, 'jmap_searchmetrics_analytics_' . $hostDomain );
		
		return $httpResponse->body;
	}
	
	/**
	 * Return the google token
	 *
	 * @access public
	 * @return string
	 */
	public function getToken() {
		$clientID = (int)$this->app->getClientId();
		try {
			$query = "SELECT token FROM #__jmap_google WHERE id = " . $clientID;
			$this->_db->setQuery ( $query );
			$result = $this->_db->loadResult ();
		} catch ( JMapException $e ) {
			$this->app->enqueueMessage ( $e->getMessage (), $e->getErrorLevel () );
			$result = null;
		} catch ( Exception $e ) {
			$jmapException = new JMapException ( $e->getMessage (), 'error' );
			$this->app->enqueueMessage ( $jmapException->getMessage (), $jmapException->getErrorLevel () );
			$result = null;
		}
		return $result;
	}

	/**
	 * Return select lists used as filter for editEntity
	 *
	 * @access public
	 * @param Object $record
	 * @return array
	 */
	public function getLists($record = null) {
		$lists = array ();
		return $lists;
	}
	
	/**
	 * Return select lists used as filter for listEntities
	 *
	 * @access public
	 * @return array
	 */
	public function getFilters() {
		$filters = array ();
		return $filters;
	}
	
	/**
	 * Delete entity
	 *
	 * @param array $ids
	 * @access public
	 * @return boolean
	 */
	public function deleteEntity($ids) {
		return $this->deleteToken();
	}
	
	/**
	 * Submit a sitemap link using the GWT API
	 *
	 * @access public
	 * @param string $sitemapUri
	 * @return boolean
	 */
	public function submitSitemap($sitemapUri) {
		$params = $this->getComponentParams ();
	
		// Perform the authentication management before going on
		$authenticationData = $this->authentication ( $params );
		if($authenticationData) {
			return $authenticationData;
		}
	
		// Set the analyzed domain in the model state
		$webmastersStatsDomain = $this->purifyWebmastersDomain( $params->get ( 'wm_domain', JUri::root() )) ;
	
		// New Service instance for the API, Google_Service_Webmasters
		$service = new Google_Service_Webmasters ( $this->client );
	
		try {
			$service->sitemaps->submit($webmastersStatsDomain, $sitemapUri);
		} catch ( Google_Exception $e ) {
			$jmapException = new JMapException ( $e->getMessage (), 'error' );
			$this->setError ( $jmapException );
			return false;
		} catch ( Exception $e ) {
			$jmapException = new JMapException ( $e->getMessage (), 'error' );
			$this->setError ( $jmapException );
			return false;
		}

		return true;
	}
	
	/**
	 * Delete a sitemap link using the GWT API
	 *
	 * @access public
	 * @param string $sitemapUri
	 * @return boolean
	 */
	public function deleteSitemap($sitemapUri) {
		$params = $this->getComponentParams ();
	
		// Perform the authentication management before going on
		$authenticationData = $this->authentication ( $params );
		if($authenticationData) {
			return $authenticationData;
		}
	
		// Set the analyzed domain in the model state
		$webmastersStatsDomain = $this->purifyWebmastersDomain( $params->get ( 'wm_domain', JUri::root() )) ;
	
		// New Service instance for the API, Google_Service_Webmasters
		$service = new Google_Service_Webmasters ( $this->client );
	
		try {
			$service->sitemaps->delete($webmastersStatsDomain, $sitemapUri);
		} catch ( Google_Exception $e ) {
			$jmapException = new JMapException ( $e->getMessage (), 'error' );
			$this->setError ( $jmapException );
			return false;
		} catch ( Exception $e ) {
			$jmapException = new JMapException ( $e->getMessage (), 'error' );
			$this->setError ( $jmapException );
			return false;
		}
	
		return true;
	}
}