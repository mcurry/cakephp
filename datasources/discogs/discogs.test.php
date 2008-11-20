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

class DiscogsTestCase extends CakeTestCase {
	var $Discogs = null;

	function setUp() {
		$this->Discogs = ConnectionManager::getDataSource('music');
	}

	function testDiscogsInstance() {
		$this->assertTrue(is_a($this->Discogs, 'DiscogsSource'));
	}
  
  function testSearchFound() {
    $result = $this->Discogs->search('get up kids');

    $this->assertEqual(array('num' => 1,
                             'type' => 'artist',
                             'title' => 'Get Up Kids, The',
                             'uri' => 'http://www.discogs.com/artist/Get+Up+Kids,+The'),
                       $result);
  }
  
  function testSearchFoundMultiple() {
    $result = $this->Discogs->search('Living End');
    $this->assertEqual(array('num' => 1,
                             'type' => 'artist',
                             'title' => 'Living End',
                             'uri' => 'http://www.discogs.com/artist/Living+End'),
                       $result);
  }
  
  function testSearchThe() {
    $result = $this->Discogs->search('The Living End');
    $this->assertEqual(array('num' => 1,
                             'type' => 'artist',
                             'title' => 'Living End, The',
                             'uri' => 'http://www.discogs.com/artist/Living+End,+The'),
                       $result);
  }
  
  function testSearchMissing() {
    $result = $this->Discogs->search('abcxyz');
    $this->assertEqual(false, $result);
  }
  
  function testArtist() {
    $uri = "http://www.discogs.com/artist/Get+Up+Kids,+The";
    $results = $this->Discogs->artist($uri);

    $this->assertEqual($results['name'], 'Get Up Kids, The');
    $this->assertEqual(count($results['Members']['Name']), 5);
  }
  
  function testRelease() {
    $results = $this->Discogs->release('456382');

    $this->assertEqual($results['Artists']['Artist']['name'], 'Get Up Kids, The');
    $this->assertEqual(count($results['Tracklist']['Track']), 5);
  }
}
?>