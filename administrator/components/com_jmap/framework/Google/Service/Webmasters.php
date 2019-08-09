<?php
// namespace administrator\components\com_jmap\framework\google;
/**
 *
 * @package JMAP::FRAMEWORK::administrator::components::com_jmap
 * @subpackage framework
 * @subpackage google
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ();
class Google_Service_Webmasters extends Google_Service
{
  /** View and modify Webmaster Tools data for your verified sites. */
  const WEBMASTERS =
      "https://www.googleapis.com/auth/webmasters";
  /** View Webmaster Tools data for your verified sites. */
  const WEBMASTERS_READONLY =
      "https://www.googleapis.com/auth/webmasters.readonly";

  public $searchanalytics;
  public $sitemaps;
  public $sites;
  

  /**
   * Constructs the internal representation of the Webmasters service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client)
  {
    parent::__construct($client);
    $this->rootUrl = 'https://www.googleapis.com/';
    $this->servicePath = 'webmasters/v3/';
    $this->version = 'v3';
    $this->serviceName = 'webmasters';

    $this->searchanalytics = new Google_Service_Webmasters_Searchanalytics_Resource(
        $this,
        $this->serviceName,
        'searchanalytics',
        array(
          'methods' => array(
            'query' => array(
              'path' => 'sites/{siteUrl}/searchAnalytics/query',
              'httpMethod' => 'POST',
              'parameters' => array(
                'siteUrl' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),
          )
        )
    );
    $this->sitemaps = new Google_Service_Webmasters_Sitemaps_Resource(
        $this,
        $this->serviceName,
        'sitemaps',
        array(
          'methods' => array(
            'delete' => array(
              'path' => 'sites/{siteUrl}/sitemaps/{feedpath}',
              'httpMethod' => 'DELETE',
              'parameters' => array(
                'siteUrl' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'feedpath' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'get' => array(
              'path' => 'sites/{siteUrl}/sitemaps/{feedpath}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'siteUrl' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'feedpath' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => 'sites/{siteUrl}/sitemaps',
              'httpMethod' => 'GET',
              'parameters' => array(
                'siteUrl' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'sitemapIndex' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
              ),
            ),'submit' => array(
              'path' => 'sites/{siteUrl}/sitemaps/{feedpath}',
              'httpMethod' => 'PUT',
              'parameters' => array(
                'siteUrl' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'feedpath' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),
          )
        )
    );
    $this->sites = new Google_Service_Webmasters_Sites_Resource(
        $this,
        $this->serviceName,
        'sites',
        array(
          'methods' => array(
            'add' => array(
              'path' => 'sites/{siteUrl}',
              'httpMethod' => 'PUT',
              'parameters' => array(
                'siteUrl' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'delete' => array(
              'path' => 'sites/{siteUrl}',
              'httpMethod' => 'DELETE',
              'parameters' => array(
                'siteUrl' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'get' => array(
              'path' => 'sites/{siteUrl}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'siteUrl' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => 'sites',
              'httpMethod' => 'GET',
              'parameters' => array(),
            ),
          )
        )
    );
  }
}


/**
 * The "searchanalytics" collection of methods.
 * Typical usage is:
 *  <code>
 *   $webmastersService = new Google_Service_Webmasters(...);
 *   $searchanalytics = $webmastersService->searchanalytics;
 *  </code>
 */
class Google_Service_Webmasters_Searchanalytics_Resource extends Google_Service_Resource
{

  /**
   * [LIMITED ACCESS]
   *
   * Query your data with filters and parameters that you define. Returns zero or
   * more rows grouped by the row keys that you define. You must define a date
   * range of one or more days.
   *
   * When date is one of the group by values, any days without data are omitted
   * from the result list. If you need to know which days have data, issue a broad
   * date range query grouped by date for any metric, and see which day rows are
   * returned. (searchanalytics.query)
   *
   * @param string $siteUrl The site's URL, including protocol. For example:
   * http://www.example.com/
   * @param Google_SearchAnalyticsQueryRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_Webmasters_SearchAnalyticsQueryResponse
   */
  public function query($siteUrl, Google_Service_Webmasters_SearchAnalyticsQueryRequest $postBody, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('query', array($params), "Google_Service_Webmasters_SearchAnalyticsQueryResponse");
  }
}

/**
 * The "sitemaps" collection of methods.
 * Typical usage is:
 *  <code>
 *   $webmastersService = new Google_Service_Webmasters(...);
 *   $sitemaps = $webmastersService->sitemaps;
 *  </code>
 */
class Google_Service_Webmasters_Sitemaps_Resource extends Google_Service_Resource
{

  /**
   * Deletes a sitemap from this site. (sitemaps.delete)
   *
   * @param string $siteUrl The site's URL, including protocol. For example:
   * http://www.example.com/
   * @param string $feedpath The URL of the actual sitemap. For example:
   * http://www.example.com/sitemap.xml
   * @param array $optParams Optional parameters.
   */
  public function delete($siteUrl, $feedpath, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl, 'feedpath' => $feedpath);
    $params = array_merge($params, $optParams);
    return $this->call('delete', array($params));
  }

  /**
   * Retrieves information about a specific sitemap. (sitemaps.get)
   *
   * @param string $siteUrl The site's URL, including protocol. For example:
   * http://www.example.com/
   * @param string $feedpath The URL of the actual sitemap. For example:
   * http://www.example.com/sitemap.xml
   * @param array $optParams Optional parameters.
   * @return Google_Service_Webmasters_WmxSitemap
   */
  public function get($siteUrl, $feedpath, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl, 'feedpath' => $feedpath);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_Webmasters_WmxSitemap");
  }

  /**
   * Lists the sitemaps-entries submitted for this site, or included in the
   * sitemap index file (if sitemapIndex is specified in the request).
   * (sitemaps.listSitemaps)
   *
   * @param string $siteUrl The site's URL, including protocol. For example:
   * http://www.example.com/
   * @param array $optParams Optional parameters.
   *
   * @opt_param string sitemapIndex A URL of a site's sitemap index. For example:
   * http://www.example.com/sitemapindex.xml
   * @return Google_Service_Webmasters_SitemapsListResponse
   */
  public function listSitemaps($siteUrl, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_Webmasters_SitemapsListResponse");
  }

  /**
   * Submits a sitemap for a site. (sitemaps.submit)
   *
   * @param string $siteUrl The site's URL, including protocol. For example:
   * http://www.example.com/
   * @param string $feedpath The URL of the sitemap to add. For example:
   * http://www.example.com/sitemap.xml
   * @param array $optParams Optional parameters.
   */
  public function submit($siteUrl, $feedpath, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl, 'feedpath' => $feedpath);
    $params = array_merge($params, $optParams);
    return $this->call('submit', array($params));
  }
}

/**
 * The "sites" collection of methods.
 * Typical usage is:
 *  <code>
 *   $webmastersService = new Google_Service_Webmasters(...);
 *   $sites = $webmastersService->sites;
 *  </code>
 */
class Google_Service_Webmasters_Sites_Resource extends Google_Service_Resource
{

  /**
   * Adds a site to the set of the user's sites in Webmaster Tools. (sites.add)
   *
   * @param string $siteUrl The URL of the site to add.
   * @param array $optParams Optional parameters.
   */
  public function add($siteUrl, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl);
    $params = array_merge($params, $optParams);
    return $this->call('add', array($params));
  }

  /**
   * Removes a site from the set of the user's Webmaster Tools sites.
   * (sites.delete)
   *
   * @param string $siteUrl The URI of the property as defined in Search Console.
   * Examples: http://www.example.com/ or android-app://com.example/
   * @param array $optParams Optional parameters.
   */
  public function delete($siteUrl, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl);
    $params = array_merge($params, $optParams);
    return $this->call('delete', array($params));
  }

  /**
   * Retrieves information about specific site. (sites.get)
   *
   * @param string $siteUrl The URI of the property as defined in Search Console.
   * Examples: http://www.example.com/ or android-app://com.example/
   * @param array $optParams Optional parameters.
   * @return Google_Service_Webmasters_WmxSite
   */
  public function get($siteUrl, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_Webmasters_WmxSite");
  }

  /**
   * Lists the user's Webmaster Tools sites. (sites.listSites)
   *
   * @param array $optParams Optional parameters.
   * @return Google_Service_Webmasters_SitesListResponse
   */
  public function listSites($optParams = array())
  {
    $params = array();
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_Webmasters_SitesListResponse");
  }
}

class Google_Service_Webmasters_ApiDataRow extends Google_Collection
{
  protected $collection_key = 'keys';
  protected $internal_gapi_mappings = array(
  );
  public $clicks;
  public $ctr;
  public $impressions;
  public $keys;
  public $position;


  public function setClicks($clicks)
  {
    $this->clicks = $clicks;
  }
  public function getClicks()
  {
    return $this->clicks;
  }
  public function setCtr($ctr)
  {
    $this->ctr = $ctr;
  }
  public function getCtr()
  {
    return $this->ctr;
  }
  public function setImpressions($impressions)
  {
    $this->impressions = $impressions;
  }
  public function getImpressions()
  {
    return $this->impressions;
  }
  public function setKeys($keys)
  {
    $this->keys = $keys;
  }
  public function getKeys()
  {
    return $this->keys;
  }
  public function setPosition($position)
  {
    $this->position = $position;
  }
  public function getPosition()
  {
    return $this->position;
  }
}

class Google_Service_Webmasters_ApiDimensionFilter extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $dimension;
  public $expression;
  public $operator;


  public function setDimension($dimension)
  {
    $this->dimension = $dimension;
  }
  public function getDimension()
  {
    return $this->dimension;
  }
  public function setExpression($expression)
  {
    $this->expression = $expression;
  }
  public function getExpression()
  {
    return $this->expression;
  }
  public function setOperator($operator)
  {
    $this->operator = $operator;
  }
  public function getOperator()
  {
    return $this->operator;
  }
}

class Google_Service_Webmasters_ApiDimensionFilterGroup extends Google_Collection
{
  protected $collection_key = 'filters';
  protected $internal_gapi_mappings = array(
  );
  protected $filtersType = 'Google_Service_Webmasters_ApiDimensionFilter';
  protected $filtersDataType = 'array';
  public $groupType;


  public function setFilters($filters)
  {
    $this->filters = $filters;
  }
  public function getFilters()
  {
    return $this->filters;
  }
  public function setGroupType($groupType)
  {
    $this->groupType = $groupType;
  }
  public function getGroupType()
  {
    return $this->groupType;
  }
}

class Google_Service_Webmasters_SearchAnalyticsQueryRequest extends Google_Collection
{
  protected $collection_key = 'dimensions';
  protected $internal_gapi_mappings = array(
  );
  public $aggregationType;
  protected $dimensionFilterGroupsType = 'Google_Service_Webmasters_ApiDimensionFilterGroup';
  protected $dimensionFilterGroupsDataType = 'array';
  public $dimensions;
  public $endDate;
  public $rowLimit;
  public $searchType;
  public $startDate;


  public function setAggregationType($aggregationType)
  {
    $this->aggregationType = $aggregationType;
  }
  public function getAggregationType()
  {
    return $this->aggregationType;
  }
  public function setDimensionFilterGroups($dimensionFilterGroups)
  {
    $this->dimensionFilterGroups = $dimensionFilterGroups;
  }
  public function getDimensionFilterGroups()
  {
    return $this->dimensionFilterGroups;
  }
  public function setDimensions($dimensions)
  {
    $this->dimensions = $dimensions;
  }
  public function getDimensions()
  {
    return $this->dimensions;
  }
  public function setEndDate($endDate)
  {
    $this->endDate = $endDate;
  }
  public function getEndDate()
  {
    return $this->endDate;
  }
  public function setRowLimit($rowLimit)
  {
    $this->rowLimit = $rowLimit;
  }
  public function getRowLimit()
  {
    return $this->rowLimit;
  }
  public function setSearchType($searchType)
  {
    $this->searchType = $searchType;
  }
  public function getSearchType()
  {
    return $this->searchType;
  }
  public function setStartDate($startDate)
  {
    $this->startDate = $startDate;
  }
  public function getStartDate()
  {
    return $this->startDate;
  }
}

class Google_Service_Webmasters_SearchAnalyticsQueryResponse extends Google_Collection
{
  protected $collection_key = 'rows';
  protected $internal_gapi_mappings = array(
  );
  public $responseAggregationType;
  protected $rowsType = 'Google_Service_Webmasters_ApiDataRow';
  protected $rowsDataType = 'array';


  public function setResponseAggregationType($responseAggregationType)
  {
    $this->responseAggregationType = $responseAggregationType;
  }
  public function getResponseAggregationType()
  {
    return $this->responseAggregationType;
  }
  public function setRows($rows)
  {
    $this->rows = $rows;
  }
  public function getRows()
  {
    return $this->rows;
  }
}

class Google_Service_Webmasters_SitemapsListResponse extends Google_Collection
{
  protected $collection_key = 'sitemap';
  protected $internal_gapi_mappings = array(
  );
  protected $sitemapType = 'Google_Service_Webmasters_WmxSitemap';
  protected $sitemapDataType = 'array';


  public function setSitemap($sitemap)
  {
    $this->sitemap = $sitemap;
  }
  public function getSitemap()
  {
    return $this->sitemap;
  }
}

class Google_Service_Webmasters_SitesListResponse extends Google_Collection
{
  protected $collection_key = 'siteEntry';
  protected $internal_gapi_mappings = array(
  );
  protected $siteEntryType = 'Google_Service_Webmasters_WmxSite';
  protected $siteEntryDataType = 'array';


  public function setSiteEntry($siteEntry)
  {
    $this->siteEntry = $siteEntry;
  }
  public function getSiteEntry()
  {
    return $this->siteEntry;
  }
}

class Google_Service_Webmasters_WmxSite extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $permissionLevel;
  public $siteUrl;


  public function setPermissionLevel($permissionLevel)
  {
    $this->permissionLevel = $permissionLevel;
  }
  public function getPermissionLevel()
  {
    return $this->permissionLevel;
  }
  public function setSiteUrl($siteUrl)
  {
    $this->siteUrl = $siteUrl;
  }
  public function getSiteUrl()
  {
    return $this->siteUrl;
  }
}

class Google_Service_Webmasters_WmxSitemap extends Google_Collection
{
  protected $collection_key = 'contents';
  protected $internal_gapi_mappings = array(
  );
  protected $contentsType = 'Google_Service_Webmasters_WmxSitemapContent';
  protected $contentsDataType = 'array';
  public $errors;
  public $isPending;
  public $isSitemapsIndex;
  public $lastDownloaded;
  public $lastSubmitted;
  public $path;
  public $type;
  public $warnings;


  public function setContents($contents)
  {
    $this->contents = $contents;
  }
  public function getContents()
  {
    return $this->contents;
  }
  public function setErrors($errors)
  {
    $this->errors = $errors;
  }
  public function getErrors()
  {
    return $this->errors;
  }
  public function setIsPending($isPending)
  {
    $this->isPending = $isPending;
  }
  public function getIsPending()
  {
    return $this->isPending;
  }
  public function setIsSitemapsIndex($isSitemapsIndex)
  {
    $this->isSitemapsIndex = $isSitemapsIndex;
  }
  public function getIsSitemapsIndex()
  {
    return $this->isSitemapsIndex;
  }
  public function setLastDownloaded($lastDownloaded)
  {
    $this->lastDownloaded = $lastDownloaded;
  }
  public function getLastDownloaded()
  {
    return $this->lastDownloaded;
  }
  public function setLastSubmitted($lastSubmitted)
  {
    $this->lastSubmitted = $lastSubmitted;
  }
  public function getLastSubmitted()
  {
    return $this->lastSubmitted;
  }
  public function setPath($path)
  {
    $this->path = $path;
  }
  public function getPath()
  {
    return $this->path;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
  public function setWarnings($warnings)
  {
    $this->warnings = $warnings;
  }
  public function getWarnings()
  {
    return $this->warnings;
  }
}

class Google_Service_Webmasters_WmxSitemapContent extends Google_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $indexed;
  public $submitted;
  public $type;


  public function setIndexed($indexed)
  {
    $this->indexed = $indexed;
  }
  public function getIndexed()
  {
    return $this->indexed;
  }
  public function setSubmitted($submitted)
  {
    $this->submitted = $submitted;
  }
  public function getSubmitted()
  {
    return $this->submitted;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
}
