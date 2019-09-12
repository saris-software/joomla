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
	public $jMapGoogleSearchArray = array(
			'RICHCARD'=>'COM_JMAP_GOOGLE_WEBMASTERS_SEARCH_RICHCARD',
			'AMP_TOP_STORIES'=>'COM_JMAP_GOOGLE_WEBMASTERS_SEARCH_AMP_TOP_STORIES',
			'AMP_BLUE_LINK'=>'COM_JMAP_GOOGLE_WEBMASTERS_SEARCH_AMP_BLUE_LINK',
			'WEBLITE'=>'COM_JMAP_GOOGLE_WEBMASTERS_SEARCH_WEBLITE',
	);
	
	public $jMapGoogleIsoArray = array(
			'ABW'=>'Aruba',
			'AFG'=>'Afghanistan',
			'AGO'=>'Angola',
			'AIA'=>'Anguilla',
			'ALA'=>'Åland Islands',
			'ALB'=>'Albania',
			'AND'=>'Andorra',
			'ARE'=>'United Arab Emirates',
			'ARG'=>'Argentina',
			'ARM'=>'Armenia',
			'ASM'=>'American Samoa',
			'ATA'=>'Antarctica',
			'ATF'=>'French Southern Territories',
			'ATG'=>'Antigua and Barbuda',
			'AUS'=>'Australia',
			'AUT'=>'Austria',
			'AZE'=>'Azerbaijan',
			'BDI'=>'Burundi',
			'BEL'=>'Belgium',
			'BEN'=>'Benin',
			'BES'=>'Bonaire, Sint Eustatius and Saba',
			'BFA'=>'Burkina Faso',
			'BGD'=>'Bangladesh',
			'BGR'=>'Bulgaria',
			'BHR'=>'Bahrain',
			'BHS'=>'Bahamas',
			'BIH'=>'Bosnia and Herzegovina',
			'BLM'=>'Saint Barthélemy',
			'BLR'=>'Belarus',
			'BLZ'=>'Belize',
			'BMU'=>'Bermuda',
			'BOL'=>'Bolivia, Plurinational State of',
			'BRA'=>'Brazil',
			'BRB'=>'Barbados',
			'BRN'=>'Brunei Darussalam',
			'BTN'=>'Bhutan',
			'BVT'=>'Bouvet Island',
			'BWA'=>'Botswana',
			'CAF'=>'Central African Republic',
			'CAN'=>'Canada',
			'CCK'=>'Cocos (Keeling) Islands',
			'CHE'=>'Switzerland',
			'CHL'=>'Chile',
			'CHN'=>'China',
			'CIV'=>'Côte d\'Ivoire',
			'CMR'=>'Cameroon',
			'COD'=>'Congo, the Democratic Republic of the',
			'COG'=>'Congo',
			'COK'=>'Cook Islands',
			'COL'=>'Colombia',
			'COM'=>'Comoros',
			'CPV'=>'Cape Verde',
			'CRI'=>'Costa Rica',
			'CUB'=>'Cuba',
			'CUW'=>'Curaçao',
			'CXR'=>'Christmas Island',
			'CYM'=>'Cayman Islands',
			'CYP'=>'Cyprus',
			'CZE'=>'Czech Republic',
			'DEU'=>'Germany',
			'DJI'=>'Djibouti',
			'DMA'=>'Dominica',
			'DNK'=>'Denmark',
			'DOM'=>'Dominican Republic',
			'DZA'=>'Algeria',
			'ECU'=>'Ecuador',
			'EGY'=>'Egypt',
			'ERI'=>'Eritrea',
			'ESH'=>'Western Sahara',
			'ESP'=>'Spain',
			'EST'=>'Estonia',
			'ETH'=>'Ethiopia',
			'FIN'=>'Finland',
			'FJI'=>'Fiji',
			'FLK'=>'Falkland Islands (Malvinas)',
			'FRA'=>'France',
			'FRO'=>'Faroe Islands',
			'FSM'=>'Micronesia, Federated States of',
			'GAB'=>'Gabon',
			'GBR'=>'United Kingdom',
			'GEO'=>'Georgia',
			'GGY'=>'Guernsey',
			'GHA'=>'Ghana',
			'GIB'=>'Gibraltar',
			'GIN'=>'Guinea',
			'GLP'=>'Guadeloupe',
			'GMB'=>'Gambia',
			'GNB'=>'Guinea-Bissau',
			'GNQ'=>'Equatorial Guinea',
			'GRC'=>'Greece',
			'GRD'=>'Grenada',
			'GRL'=>'Greenland',
			'GTM'=>'Guatemala',
			'GUF'=>'French Guiana',
			'GUM'=>'Guam',
			'GUY'=>'Guyana',
			'HKG'=>'Hong Kong',
			'HMD'=>'Heard Island and McDonald Islands',
			'HND'=>'Honduras',
			'HRV'=>'Croatia',
			'HTI'=>'Haiti',
			'HUN'=>'Hungary',
			'IDN'=>'Indonesia',
			'IMN'=>'Isle of Man',
			'IND'=>'India',
			'IOT'=>'British Indian Ocean Territory',
			'IRL'=>'Ireland',
			'IRN'=>'Iran, Islamic Republic of',
			'IRQ'=>'Iraq',
			'ISL'=>'Iceland',
			'ISR'=>'Israel',
			'ITA'=>'Italy',
			'JAM'=>'Jamaica',
			'JEY'=>'Jersey',
			'JOR'=>'Jordan',
			'JPN'=>'Japan',
			'KAZ'=>'Kazakhstan',
			'KEN'=>'Kenya',
			'KGZ'=>'Kyrgyzstan',
			'KHM'=>'Cambodia',
			'KIR'=>'Kiribati',
			'KNA'=>'Saint Kitts and Nevis',
			'KOR'=>'Korea, Republic of',
			'KWT'=>'Kuwait',
			'LAO'=>'Lao People\'s Democratic Republic',
			'LBN'=>'Lebanon',
			'LBR'=>'Liberia',
			'LBY'=>'Libya',
			'LCA'=>'Saint Lucia',
			'LIE'=>'Liechtenstein',
			'LKA'=>'Sri Lanka',
			'LSO'=>'Lesotho',
			'LTU'=>'Lithuania',
			'LUX'=>'Luxembourg',
			'LVA'=>'Latvia',
			'MAC'=>'Macao',
			'MAF'=>'Saint Martin (French part)',
			'MAR'=>'Morocco',
			'MCO'=>'Monaco',
			'MDA'=>'Moldova, Republic of',
			'MDG'=>'Madagascar',
			'MDV'=>'Maldives',
			'MEX'=>'Mexico',
			'MHL'=>'Marshall Islands',
			'MKD'=>'Macedonia, the former Yugoslav Republic of',
			'MLI'=>'Mali',
			'MLT'=>'Malta',
			'MMR'=>'Myanmar',
			'MNE'=>'Montenegro',
			'MNG'=>'Mongolia',
			'MNP'=>'Northern Mariana Islands',
			'MOZ'=>'Mozambique',
			'MRT'=>'Mauritania',
			'MSR'=>'Montserrat',
			'MTQ'=>'Martinique',
			'MUS'=>'Mauritius',
			'MWI'=>'Malawi',
			'MYS'=>'Malaysia',
			'MYT'=>'Mayotte',
			'NAM'=>'Namibia',
			'NCL'=>'New Caledonia',
			'NER'=>'Niger',
			'NFK'=>'Norfolk Island',
			'NGA'=>'Nigeria',
			'NIC'=>'Nicaragua',
			'NIU'=>'Niue',
			'NLD'=>'Netherlands',
			'NOR'=>'Norway',
			'NPL'=>'Nepal',
			'NRU'=>'Nauru',
			'NZL'=>'New Zealand',
			'OMN'=>'Oman',
			'PAK'=>'Pakistan',
			'PAN'=>'Panama',
			'PCN'=>'Pitcairn',
			'PER'=>'Peru',
			'PHL'=>'Philippines',
			'PLW'=>'Palau',
			'PNG'=>'Papua New Guinea',
			'POL'=>'Poland',
			'PRI'=>'Puerto Rico',
			'PRK'=>'Korea, Democratic People\'s Republic of',
			'PRT'=>'Portugal',
			'PRY'=>'Paraguay',
			'PSE'=>'Palestinian Territory, Occupied',
			'PYF'=>'French Polynesia',
			'QAT'=>'Qatar',
			'REU'=>'Réunion',
			'ROU'=>'Romania',
			'RUS'=>'Russian Federation',
			'RWA'=>'Rwanda',
			'SAU'=>'Saudi Arabia',
			'SDN'=>'Sudan',
			'SEN'=>'Senegal',
			'SGP'=>'Singapore',
			'SGS'=>'South Georgia and the South Sandwich Islands',
			'SHN'=>'Saint Helena, Ascension and Tristan da Cunha',
			'SJM'=>'Svalbard and Jan Mayen',
			'SLB'=>'Solomon Islands',
			'SLE'=>'Sierra Leone',
			'SLV'=>'El Salvador',
			'SMR'=>'San Marino',
			'SOM'=>'Somalia',
			'SPM'=>'Saint Pierre and Miquelon',
			'SRB'=>'Serbia',
			'SSD'=>'South Sudan',
			'STP'=>'Sao Tome and Principe',
			'SUR'=>'Suriname',
			'SVK'=>'Slovakia',
			'SVN'=>'Slovenia',
			'SWE'=>'Sweden',
			'SWZ'=>'Swaziland',
			'SXM'=>'Sint Maarten (Dutch part)',
			'SYC'=>'Seychelles',
			'SYR'=>'Syrian Arab Republic',
			'TCA'=>'Turks and Caicos Islands',
			'TCD'=>'Chad',
			'TGO'=>'Togo',
			'THA'=>'Thailand',
			'TJK'=>'Tajikistan',
			'TKL'=>'Tokelau',
			'TKM'=>'Turkmenistan',
			'TLS'=>'Timor-Leste',
			'TON'=>'Tonga',
			'TTO'=>'Trinidad and Tobago',
			'TUN'=>'Tunisia',
			'TUR'=>'Turkey',
			'TUV'=>'Tuvalu',
			'TWN'=>'Taiwan, Province of China',
			'TZA'=>'Tanzania, United Republic of',
			'UGA'=>'Uganda',
			'UKR'=>'Ukraine',
			'UMI'=>'United States Minor Outlying Islands',
			'URY'=>'Uruguay',
			'USA'=>'United States',
			'UZB'=>'Uzbekistan',
			'VAT'=>'Holy See (Vatican City State)',
			'VCT'=>'Saint Vincent and the Grenadines',
			'VEN'=>'Venezuela, Bolivarian Republic of',
			'VGB'=>'Virgin Islands, British',
			'VIR'=>'Virgin Islands, U.S.',
			'VNM'=>'Viet Nam',
			'VUT'=>'Vanuatu',
			'WLF'=>'Wallis and Futuna',
			'WSM'=>'Samoa',
			'YEM'=>'Yemen',
			'ZAF'=>'South Africa',
			'ZMB'=>'Zambia',
			'ZWE'=>'Zimbabwe'
	);
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$user = JFactory::getUser();
		$authenticationRequired = true;
		
		if($this->getModel()->getState('googlestats', 'analytics') == 'webmasters') {
			JToolBarHelper::title( JText::_( 'COM_JMAP_GOOGLE_WEBMASTERS_TOOLS' ), 'jmap' );
		} elseif($this->getModel()->getState('googlestats', 'analytics') == 'alexafetch') {
			JToolBarHelper::title( JText::_( 'COM_JMAP_ALEXA_ANALYTICS' ), 'jmap' );
			$authenticationRequired = false;
		} elseif($this->getModel()->getState('googlestats', 'analytics') == 'hypestatfetch') {
			JToolBarHelper::title( JText::_( 'COM_JMAP_HYPESTAT_ANALYTICS' ), 'jmap' );
			$authenticationRequired = false;
		} elseif($this->getModel()->getState('googlestats', 'analytics') == 'searchmetricsfetch') {
			JToolBarHelper::title( JText::_( 'COM_JMAP_SEARCHMETRICS_ANALYTICS' ), 'jmap' );
			$authenticationRequired = false;
		} else {
			JToolBarHelper::title( JText::_( 'COM_JMAP_GOOGLE_ANALYTICS' ), 'jmap' );
		}

		// Store logged in status in session
		if($this->isLoggedIn && $authenticationRequired) {
			JToolBarHelper::custom('google.deleteEntity', 'lock', 'lock', 'COM_JMAP_GOOGLE_LOGOUT', false);
		}
		
		if ($user->authorise('core.edit', 'com_jmap') && $this->getModel()->getState('googlestats', 'analytics') == 'webmasters' && $this->isLoggedIn) {
			JToolBarHelper::custom('google.submitSitemap', 'upload', 'upload', 'COM_JMAP_SUBMIT_SITEMAP', false);
		}
		
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_JMAP_CPANEL', false);
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
		$lists = $this->get ( 'Lists' );
		
		$this->loadJQuery($this->document);
		$this->loadBootstrap($this->document);
		$this->document->addScript ( JUri::root ( true ) . '/administrator/components/com_jmap/js/google.js' );
		
		// Check the Google stats type and retrieve stats data accordingly, supported types are 'analytics' and 'webmasters'
		$googleStatsState = $this->getModel()->getState('googlestats', 'analytics');
		if($googleStatsState == 'webmasters') {
			$googleData = $this->get ( 'DataWebmasters' );
			if(!$this->getModel()->getState('loggedout')) {
				$tpl = 'webmasters';
			}
			// Load resources
			$this->loadJQueryUI($this->document); // Required for calendar feature
			$this->document->addScriptDeclaration("jQuery(function(){jQuery('input[data-role=calendar]').datepicker({dateFormat : 'yy-mm-dd'}).prev('span').on('click', function(){jQuery(this).datepicker('show');});});");
			$this->document->addScript(JUri::root(true) . '/administrator/components/com_jmap/js/tablesorter/jquery.tablesorter.js');

			// Set dates
			$dates = array('from'=>$this->getModel()->getState('fromPeriod'), 'to'=>$this->getModel()->getState('toPeriod'));
			$this->dates = $dates;
		} elseif($googleStatsState == 'alexafetch' || 
				 $googleStatsState == 'hypestatfetch' || 
				 $googleStatsState == 'searchmetricsfetch') {
			// Load resources, iframe script used for frontend module and custom backend template styling for the iframed contents
			$this->document->addScript ( JUri::root ( true ) . '/modules/mod_jmap/tmpl/iframe.js' );
			$this->document->addStyleDeclaration('div.container-fluid{padding:0}div.subhead-collapse{margin:0}#toolbar{padding-left:20px}');
			
			// Setup the iframe container
			$onLoadIFrame = "jmapIFrameAutoHeight('jmap-analytics-frame')";
			$renderGoogleStatsState = str_ireplace('fetch', 'render', $googleStatsState);
			$googleData = '<iframe id="jmap-analytics-frame" src="' . JUri::root (false) . 'administrator/index.php?option=com_jmap&task=google.display&googlestats=' . $renderGoogleStatsState . '&format=raw" onload="' . $onLoadIFrame . '"></iframe>';
			
			// Set the model state for the current stats domain that is queried
			$this->getModel()->setState('stats_domain', $this->getModel()->getComponentParams()->get('ga_domain', JUri::root()));
			$tpl = 'framed';
		} else {
			$googleData = $this->get ( 'Data' );
		}
		
		// Inject js translations
		$translations = array(
				'COM_JMAP_REQUIRED',
				'COM_JMAP_ADDSITEMAP',
				'COM_JMAP_ADDSITEMAP_DESC',
				'COM_JMAP_SUBMIT',
				'COM_JMAP_CANCEL',
				'COM_JMAP_INVALID_URL_FORMAT',
				'COM_JMAP_WORKING'
		);
		$this->injectJsTranslations($translations, $this->document);
		
		$this->globalConfig = JFactory::getConfig();
		$this->timeZoneObject = new DateTimeZone($this->globalConfig->get('offset'));
		$this->document->addScriptDeclaration("var jmap_baseURI='" . JUri::root() . "';");
		$this->lists = $lists;
		$this->googleData = $googleData;
		$this->isLoggedIn = $this->getModel()->getToken();
		$this->statsDomain = $this->getModel()->getState('stats_domain', JUri::root());
		$this->hasOwnCredentials = $this->getModel()->getState('has_own_credentials', false);
		$this->option = $this->getModel ()->getState ( 'option' );
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		parent::display ($tpl);
	}
}