<?php
/**
 * Yahoo BOSS DataSource
 *
 * Communicates with Yahoo! BOSS open search web services platform and provides
 * site search functionality with results powered by Yahoo!
 *
 * @link http://developer.yahoo.com/search/boss/
 *
 * - Uses CakePHP's built-in HttpSocket class to make requests.
 * - Provides both web search and spelling suggestion functionality.
 * - Web search can be limited to one or more sites.
 * - Web search results contain key terms related to the result.
 * - Can be used in conjunction with custom paginateCount and paginate model
 * methods to make use of CakePHP's buit-in pagination controller logic and
 * helpers.
 *
 * Usage:
 *
 * Add to app/config/database.php
 *
 * var $yahooBoss = array(
 *   'datasource' => 'yahoo_boss',
 *   'sites' => 'http://your.site.here',
 *   'app_id' => 'your_app_id_here',
 * );
 *
 * In your model
 *
 * var $useDbConfig = 'yahooBoss';
 *
 * function search($conditions, $limit, $page) {
 *   $ds = ConnectionManager::getDataSource($this->useDbConfig);
 *   return $ds->webSearch($conditions, $limit, $page);
 * }
 *
 * For advanced usage see my blog
 *
 * Yahoo Application Id Key required, which you can get it from:
 * @link http://developer.yahoo.com/wsregapp/
 *
 * @author Neil Crookes <neil@neilcrookes.com>
 * @link http://www.neilcrookes.com
 * @copyright (c) 2008 Neil Crookes
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 * @link http://github.com/neilcrookes/cakephp/tree/master
 *
 */
class YahooBossSource extends DataSource {

  /*
   * Name of this DataSource
   *
   * @var string
   */
  var $description = "Yahoo BOSS Data Source";

  /**
   * Yahoo! BOSS Web Search service endpoint
   *
   * @var string
   */
  var $webSearchEndpoint = 'http://boss.yahooapis.com/ysearch/web/v1/';

  /**
   * Yahoo! BOSS Spelling service endpoint
   *
   * @var string
   */
  var $spellingSuggestionEndpoint = 'http://boss.yahooapis.com/ysearch/spelling/v1/';

  /**
   * BOSS Application Id
   *
   * @var string
   */
  var $appId = null;

  /**
   * Sites to restrict the search to
   *
   * @var mixed Array of multiple sites or string for single site
   */
  var $sites = null;

  /**
   * Stores HttpSocket object
   *
   * @var HttpSocket
   */
  var $Http = null;

  /**
   * Total number of results for the search term
   *
   * @var integer
   */
  var $numResults = 0;

  /**
   * Actual page of results returned from Yahoo!
   *
   * @var array
   */
  var $results = array();

  /**
   * Loads config, BOSS Application ID, sites to restrict search to, and loads
   * instance of HttpSocket class
   *
   * @param array $config As defined in DATABASE_CONFIG::yahooBoss
   */
  function __construct($config) {

    if (!isset($config['app_id'])) {
      trigger_error(__('Specify app_id in yahooBoss db config', true), E_USER_ERROR);
    }

    $this->appId = $config['app_id'];

    if (isset($config['sites'])) {
      $this->sites = $config['sites'];
    }

    parent::__construct($config);

    App::import('HttpSocket');
    $this->Http = new HttpSocket();

  }

  /**
   * Added to avoid having to add Model::useTable = false otherwise an warning
   * was triggered when calling DataSource::listSources
   *
   * @return false
   */
  function listSources() {
    return false;
  }

  /**
   * Makes the request to Yahoo! BOSS Search Web Service and stores total hit
   * count and the results in the returned page of the result set.
   *
   * @param array $conditions
   * @param integer $limit
   * @param integer $page
   * @return boolean
   */
  function webSearch($conditions, $limit, $page) {

    $url = $this->webSearchEndpoint;

    $term = '';

    // Adds term and sites conditions to url
    if (is_string($conditions)) {

      $term .= $conditions;

    } elseif (is_array($conditions)) {

      if (isset($conditions['term'])
      && is_string($conditions['term'])) {
        $term .= $conditions['term'];
      }

      if (isset($conditions['sites'])) {

        // Sites can be passed in the conditions array
        $sites = $conditions['sites'];

      } else {

        // ... or can be set up in the database config
        $sites = $this->sites;

      }

      if (is_array($sites)) {

        // Annoyingly site(s): param must have correct pluralisation
        if (count($sites) == 1) {
          $term .= ' site:';
        } else {
          $term .= ' sites:';
        }

        $term .= implode(',', $sites);

      } elseif (is_string($sites)) {

        $term .= ' site:' . $sites;

      }

    }

    $url .= urlencode($term);

    $url .= '?appid=' . $this->appId;

    // Fetch the key terms in the result set (array of terms related to search term)
    $url .= '&view=keyterms';

    if ($limit != 10) {
      $url .= '&count='.$limit;
    }

    if ($page != 1) {
      $url .= '&start='.$limit * $page;
    }

    if (!$response = $this->_request($url)) {
      return false;
    }

    if (isset($response['totalhits'])) {
      $this->numResults = $response['totalhits'];
    }

    if (isset($response['resultset_web'])) {
      $this->results = $response['resultset_web'];
    }

    return true;

  }

  /**
   * Gets a spelling suggestion for a given term
   *
   * @param string $term Term to get spelling suggestions for
   * @return mixed Suggestion string if found, or false on failure
   */
  function spellingSuggestion($term = null) {

    if (!$term) {
      return false;
    }

    $url = $this->spellingSuggestionEndpoint;

    $url .= urlencode($term);

    $url .= '?appid=' . $this->appId;

    if (!$response = $this->_request($url)) {
      return false;
    }

    if (!isset($response['resultset_spell'][0]['suggestion'])) {
      return false;
    }

    return $response['resultset_spell'][0]['suggestion'];

  }

  /**
   * Makes request using HttpSocket to a URL and returns the response from the
   * Yahoo Search web service.
   *
   * @param string $url Url of the request
   * @return mixed Response array on success / false on failure
   */
  function _request($url) {

    $this->Http->get($url);

    if ($this->Http->response['status']['code'] != 200) {
      return false;
    }

    $response = json_decode($this->Http->response['body'], true);

    return $response['ysearchresponse'];

  }

}

?>