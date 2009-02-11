<?php

class Search extends YahooBossAppModel {

  /**
   * Select DataSource for site search
   *
   * @var string
   */
  var $useDbConfig = 'yahooBoss';

  /**
   * Controller should set Search::paginate property with keys
   * for limit and page before calling SearchController::paginate()
   * because Search::paginateCount() calls DataSource::webSearch
   * before the limit and page params are set in the controller's
   * paginate method.
   *
   * @var array
   */
  var $paginate;

  /**
   * Stores DataSource for sharign between paginateCount and
   * paginate calls
   *
   * @var DataSource
   */
  var $SearchEngine = null;
  
  function __construct() {
    App::import(array('type' => 'File', 'name' => 'YahooBoss.YAHOO_BOSS_CONFIG', 'file' => 'config'.DS.'yahoo_boss.php'));
    App::import(array('type' => 'File', 'name' => 'YahooBoss.YahooBossSource', 'file' => 'models'.DS.'datasources'.DS.'yahoo_boss_source.php'));
    $config =& new YAHOO_BOSS_CONFIG();
    ConnectionManager::create('yahooBoss', $config->yahooBoss);
    
    parent::__construct();
  }

  function schema() {
    return array();
  }

  function paginateCount($conditions, $recursive = 1, $extra = array()) {

    $this->SearchEngine = ConnectionManager::getDataSource($this->useDbConfig);

    $this->SearchEngine->webSearch($conditions, $this->paginate['limit'], $this->paginate['page']);

    return $this->SearchEngine->numResults;

  }

  function paginate($conditions, $fields = null, $order = null, $limit = null, $page = 1, $recursive = null, $extra = array()) {

    return $this->SearchEngine->results;

  }

  function spellingSuggestion($term) {

    return $this->SearchEngine->spellingSuggestion($term);

  }

}

?>