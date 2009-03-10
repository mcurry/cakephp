<?php
/*
 * SQL logger that writes to a file
 * Copyright (c) 2008 Matt Curry
 * www.PseudoCoder.com
 *
 * 90% of this code is taken from the DebugKit
 * http://thechaw.com/debug_kit
 *
 * @author      Matt Curry <matt@pseudocoder.com>
 * @license     MIT
 *
 */

App::import('Core', 'Xml');

class SqlLogComponent extends Object {
  function beforeRender(&$controller) {
    $queryLogs = array();
    if (!class_exists('ConnectionManager')) {
      return array();
    }
    $dbConfigs = ConnectionManager::sourceList();
    foreach ($dbConfigs as $configName) {
      $db =& ConnectionManager::getDataSource($configName);
      if ($db->isInterfaceSupported('showLog')) {
        ob_start();
        $db->showLog();
        $logs =  ob_get_clean();
        $Xml = new Xml($logs);
        $logs = $Xml->toArray();
        $logs = Set::classicExtract($logs, 'Table.Tbody.Tr.{n}.Td.1');
        
        foreach ($logs as $log) {
          $this->log($log);
        }
      }
    }
  }
}
?>