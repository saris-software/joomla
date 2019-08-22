<?php
// namespace administrator\components\com_jmap\framework\seostats\services\websiteinformer;
/**
 *
 * @package JMAP::SEOSTATS::administrator::components::com_jmap
 * @subpackage seostats
 * @subpackage services
 * @subpackage websiteinformer
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Alexa stats service
 *
 * @package JMAP::SEOSTATS::administrator::components::com_jmap
 * @subpackage seostats
 * @subpackage services
 * @subpackage websiteinformer
 * @since 4.6.2
 */
class JMapSeostatsServicesWebsiteinformer extends JMapSeostatsServicesAlexa {
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
		$html = static::_getWebsiteinformerPage ( $url );
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
	protected static function _getWebsiteinformerPage($url) {
		$domain = JMapSeostatsHelperUrl::parseHost ( $url );
		$dataUrl = sprintf ( JMapSeostatsServices::$WEBSITEINFORMER_SITEINFO_URL, $domain );
		$html = static::_getPage ( $dataUrl );
		return $html;
	}
	
	/**
	 * Get the website rank
	 *
	 * @access public
	 * @static
	 * @return int
	 */
	public static function getGlobalRank($url = false) {
		$xpath = self::_getXPath ( $url );
	
		$xpathQueryList = array (
				"//div[@id='alexa_rank']/b"
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
				"//div[@id='visitors']/b"
		);
	
		return static::parseDomByXpathsToIntegerWithoutTags ( $xpath, $xpathQueryList );
	}
	
	/**
	 * Get daily pageviews
	 *
	 * @access public
	 * @static
	 * @return int
	 */
	public static function getDailyPageviews($url = false) {
		$xpath = self::_getXPath ( $url );
	
		$xpathQueryList = array (
				"//div[@id='pageviews']/b"
		);
	
		return static::parseDomByXpathsToIntegerWithoutTags ( $xpath, $xpathQueryList );
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
				"//div[@class='domenTitle']"
		);
	
		return static::parseDomByXpathsGetValue ( $xpath, $xpathQueryList );
	}
	
	/**
	 * Get website screen
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	public static function getWebsiteScreen($url = false) {
		$imgNode = '';
		$xpath = self::_getXPath ( $url );
	
		$xpathQueryList = array (
				"//div[@class='contStatsRight']/img[contains(@title,'thumbnail')]"
		);
	
		$nodes = static::parseDomByXpaths ( $xpath, $xpathQueryList );
		
		if($nodes) {
			$dom = self::_getDOMObject();
			
			$originalNode = $nodes->item(0);
			$imgNode = $dom->saveHTML($originalNode);
		}
		
		return $imgNode;
	}
}
