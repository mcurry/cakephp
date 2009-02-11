<?php

class SearchController extends YahooBossAppController {

  var $paginate = array(
    'limit' => 10,
    'page' => 1
  );
 
  function index() {
    if (!empty($this->data['Search']['term'])) {
      $this->redirect(array('action' => 'results', 'term' => $this->data['Search']['term']));
    }

    $term = '';
    if (!empty($this->params['term'])) {
      $term = $this->params['term'];
    } else if(!empty($this->params['named']['term'])) {
      $term = $this->params['named']['term'];
    }

    if (isset($this->passedArgs['show'])) {
      $this->paginate['limit'] = $this->passedArgs['show'];
    }

    if (isset($this->passedArgs['page'])) {
      $this->paginate['page'] = $this->passedArgs['page'];
    }

    $this->Search->paginate = $this->paginate;

    $results = $this->paginate('Search', array('term' => $term));

    $spellingSuggestion = $this->Search->spellingSuggestion($term);

    $this->set(compact('results', 'term', 'spellingSuggestion'));
  }

  function results() {
    $this->index();
    $this->render('index');
  }
}

?>