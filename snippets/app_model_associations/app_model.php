<?php
/*
 * App Model
 * Copyright (c) 2009 Matt Curry
 * www.PseudoCoder.com
 *
 * @author      Matt Curry <matt@pseudocoder.com>
 * @license     MIT
 *
 */
 
class AppModel extends Model {
  var $__definedAssociations = array();
  var $__loadAssociations = array('Aro', 'Aco', 'Permission');

  function __construct($id = false, $table = null, $ds = null) {
    if (!in_array(get_class($this), $this->__loadAssociations)) {
      foreach($this->__associations as $association) {
        foreach($this->{$association} as $key => $value) {
          $assocName = $key;

          if (is_numeric($key)) {
            $assocName = $value;
            $value = array();
          }

          $value['type'] = $association;
          $this->__definedAssociations[$assocName] = $value;
          if (!empty($value['with'])) {
            $this->__definedAssociations[$value['with']] = array('type' => 'hasMany');
          }
        }

        $this->{$association} = array();
      }
    }

    parent::__construct($id, $table, $ds);
  }

  function __isset($name) {
    return $this->__connect($name); 
  }
  
  function __get($name) {
    return $this->__connect($name); 
  }
    
  function __connect($name) {
    if (empty($this->__definedAssociations[$name])) {
      return false;
    }

    $this->bind($name, $this->__definedAssociations[$name]);
    return $this->{$name};
  }
}
?>