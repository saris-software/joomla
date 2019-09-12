<?php
// namespace administrator\components\com_jmap\framework\seostats\services\zigstat;
/**
 *
 * @package JMAP::SEOSTATS::administrator::components::com_jmap
 * @subpackage seostats
 * @subpackage services
 * @subpackage zigstat
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Zigstat stats service
 *
 * @package JMAP::SEOSTATS::administrator::components::com_jmap
 * @subpackage seostats
 * @subpackage services
 * @subpackage zigstat
 * @since 4.6.2
 */
class JMapSeostatsServicesZigstat extends JMapSeostatsServicesAlexa {
	/**
	 * HTML source code scraped
	 * 
	 * @access public
	 * @var string
	 */
	protected static $htmlSource;
	
	/**
	 * @access protected
	 * @static
	 * @return DOMXPath
	 */
	protected static function _getXPath($url) {
		$url = parent::getUrl ( $url );
		if (stripos(parent::getLastLoadedUrl (), $url) !== false && self::$_xpath) {
			return self::$_xpath;
		}
		$html = static::_getZigstatPage ( $url );
		$doc = parent::_getDOMDocument ( $html );
		$xpath = parent::_getDOMXPath ( $doc );
	
		self::$_xpath = $xpath;
	
		return $xpath;
	}
	
	/**
	 * @access protected
	 * @static
	 * @return string
	 */
	protected static function _getZigstatPage($url) {
		$domain = JMapSeostatsHelperUrl::parseHost ( $url );
		$dataUrl = sprintf ( JMapSeostatsServices::$ZIGSTAT_SITEINFO_URL, $domain );
		$html = static::_getPage ( $dataUrl );
		self::$htmlSource = $html;
		return $html;
	}
	
	/**
	 * Get total backlinks
	 *
	 * @access public
	 * @static
	 * @return int
	 */
	public static function getBacklinks($url = false) {
		$xpath = self::_getXPath ( $url );
	
		$xpathQueryList = array (
				"//section[@id='section3']//table//tr[1]//td[2]"
		);
	
		return static::parseDomByXpathsToIntegerWithoutTags ( $xpath, $xpathQueryList );
	}
	
	/**
	 * Get the website Alexa rank
	 *
	 * @access public
	 * @static
	 * @return int
	 */
	public static function getAlexaRank($url = false) {
		$xpath = self::_getXPath ( $url );
	
		$xpathQueryList = array (
				"//section[@id='section2']//table//tr[1]//td[2]"
		);
	
		return static::parseDomByXpathsToIntegerWithoutTags ( $xpath, $xpathQueryList );
	}
	
	/**
	 * Get daily visitors
	 *
	 * @access public
	 * @static
	 * @return int
	 */
	public static function getDailyVisitors($url = false) {
		$xpath = self::_getXPath ( $url );
	
		$xpathQueryList = array (
				"//section[@id='section4']//table//tr[1]//td[2]"
		);
	
		return static::parseDomByXpathsToIntegerWithoutTags ( $xpath, $xpathQueryList );
	}
	
	/**
	 * Get daily page views
	 *
	 * @access public
	 * @static
	 * @return int
	 */
	public static function getDailyPageViews($url = false) {
		$xpath = self::_getXPath ( $url );
	
		$xpathQueryList = array (
				"//section[@id='section4']//table//tr[2]//td[2]"
		);
	
		return static::parseDomByXpathsToIntegerWithoutTags ( $xpath, $xpathQueryList );
	}
	
	/**
	 * Get the list of available backlink websites if available
	 *
	 * @access public
	 * @static
	 * @return array
	 */
	public static function getBacklinksList($url = false) {
		$xpath = self::_getXPath ( $url );
	
		$xpathQueryList = array (
				"//div[@class='col-md-8']/div[8]//table//tr[position()>1]"
		);
	
		return static::parseDomByXpathsGetObjectArray ( $xpath, $xpathQueryList, 'backlink' );
	}
	
	/**
	 * Get website report text
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	public static function getReportText($url = false) {
		$xpath = self::_getXPath ( $url );
	
		$xpathQueryList = array (
				"//div[@class='col-md-8']/div[3]/div[2]"
		);
	
		return static::parseDomByXpathsGetValue ( $xpath, $xpathQueryList );
	}
	
	/**
	 * Grab and compile the JS array of data
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function compileArray(&$pageRanksArray) {
		preg_match('/percentage\s=\s\[(\n.*)(\n.*)(\n.*)(\n.*)(\n.*)/im', self::$htmlSource, $matches);
		
		if(!empty($matches)) {
			$pageRanksArray['mozrank'] = $matches[1] ? str_replace(array(" ", "\n", '"'), '', trim($matches[1], ',')) : JText::_ ( 'COM_JMAP_NA' );
			$pageRanksArray['mozdomainauth'] = $matches[2] ? str_replace(array(" ", "\n", '"'), '', trim($matches[2], ',')) : JText::_ ( 'COM_JMAP_NA' );
			$pageRanksArray['mozpageauth'] = $matches[3] ? str_replace(array(" ", "\n", '"'), '', trim($matches[3], ',')) : JText::_ ( 'COM_JMAP_NA' );
			$pageRanksArray['pagespeed'] = $matches[4] ? str_replace(array(" ", "\n", '"'), '', trim($matches[4], ',')) : JText::_ ( 'COM_JMAP_NA' );
		}
	}
	
}
