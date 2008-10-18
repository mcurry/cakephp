<?php
/*
 * Layout Switcher CakePHP Component
 * Copyright (c) 2007 Matt Curry
 * www.PseudoCoder.com
 *
 * @author      mattc <matt@pseudocoder.com>
 * @version     1.0
 * @license     MIT
 *
 */

class LayoutSwitcherComponent extends Object {
  var $controller;
  var $components = array('RequestHandler');

  function startup(&$controller) {
    uses('Folder');

    $this->controller = $controller;

    //get the domain used
    $domain = $_SERVER['HTTP_HOST'];

    //remove any www.
    $domain = str_replace('www.', '', $domain);

    //check if a layout exists for this server
    $folder =& new Folder(LAYOUTS);
    $files = $folder->find($domain . '.(thtml|ctp)');

    //should only be one match
    if (count($files) != 1) {
      return;
    }

    //set the layout
    //only if not ajax
    if(!$this->RequestHandler->isAjax()) {
      $this->controller->layout = $domain;
    }
  }
}
?>
