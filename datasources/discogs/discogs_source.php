<?php
/*
 * Discogs Datasource for CakePHP
 * Copyright (c) 2008 Matt Curry
 * www.PseudoCoder.com
 * http://github.com/mcurry/cakephp/tree/master/datasources/discogs
 *
 * @author      mattc <matt@pseudocoder.com>
 * @license     MIT
 *
 */

App::import(array('HttpSocket', 'Xml'));

class DiscogsSource extends DataSource {
  var $description = "Discogs API";
  var $url = 'http://www.discogs.com';
  var $Http = null;
  
  function __construct($config) {
    parent::__construct($config);
    $this->Http =& new HttpSocket();
  }
  
  function search($artist) {
    $artist = strtolower($artist);
    
    //check for "The ...." pattern, switch to ..., The"
    if(stripos($artist, 'the ') === 0) {
      $artist = substr($artist, 4) . ', The';
    }
    
    $url = sprintf('%s/search', $this->url);
    $params = array('type' => 'artists',
                    'q' => $artist);
    
    $results = $this->__request($url, $params);

    if(!empty($results['Exactresults'])) {
      if(Set::numeric(array_keys($results['Exactresults']['Result']))) {
        return array_shift($results['Exactresults']['Result']);
      }
      return $results['Exactresults']['Result'];
    }
    
    return false;
  }
  
  function artist($uri) {
    $results = $this->__request($uri);
    
    return $results['Artist'];
  }
  
  function release($id) {
    $url = sprintf('%s/release/%d', $this->url, $id);
    $results = $this->__request($url);
    return $results['Release'];
  }
  
  function __request($url, $params=array()) {
    $params = array_merge(array('api_key' => $this->config['apiKey'],
                                'f' => 'xml'),
                          $params);
    $request['header']['Accept-Encoding'] = 'gzip';
    
    $response = $this->Http->get($url, $params, $request);
    if(substr($response, 0, 1) !== '<') {
      $response = gzdecode($response);
    }
    
    $xml = new Xml($response);
    $xml = $xml->toArray();
    $return = $xml['Resp'];
    
    return $return;
  }
}
?>