<?php
class PostViewTestCase extends CakeTestCase {
  var $View;

  function startTest() {
    $Controller = new Controller();
    $this->View = new View($Controller);
    $this->View->layout = null;
    $this->View->viewPath = 'posts';
  }

  function testPostInstance() {
    $this->assertTrue(is_a($this->View, 'View'));
  }

  function testPostIndex() {
    $this->View->helpers = array('Html', 'Paginator');
    $this->View->params['paging'] = array(
                                      'Posts' => array
                                               (
                                                 'count' => 1,
                                                 'pageCount' => 1,
                                                 'page' => 1,
                                                 'current' => 1,
                                                 'prevPage' => false,
                                                 'nextPage' => false,
                                                 'options' => array('limit' => 20),
                                                 'defaults' => array()
                                               )
                                    );

    $this->View->viewVars['posts'] = array (
                                       array (
                                         'Post' => array
                                                 (
                                                   'id' => 1,
                                                   'title' => 'Lorem ipsum dolor sit amet',
                                                   'body' => 'Lorem ipsum dolor sit amet...',
                                                   'created' => '2009-01-23 16:51:40',
                                                   'updated' => '2009-01-23 16:51:40'
                                                 )
                                       )
                                     );

    $out = $this->View->render('index');

    $this->assertNotEqual(false, strpos($out, 'Page 1 of 1, showing 1 records out of 1 total, starting on record 1, ending on 1'));

    App::import('Core', 'Xml');
    $Xml = new Xml($out);
    $page = $Xml->first()->toArray();

    $this->assertEqual(count(Set::extract('/Table/Tr', $page)), 2);
    $this->assertEqual(Set::extract('/Table/Tr/Th/a/value', $page),
                       array('Id', 'Title', 'Body', 'Created', 'Updated'));
    $this->assertEqual(trim(array_shift(Set::extract('/Table/Tr[2]/Td/3', $page))), '2009-01-23 16:51:40');

  }
}
?>