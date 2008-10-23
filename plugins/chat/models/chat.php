<?php
class Chat extends ChatAppModel {
  var $name = 'Chat';
  var $validate = array(
                    'key' => array(
                             'rule' => array('minLength', '1')
                           ),
                    'name' => array(
                              'rule' => array('minLength', '1')
                            ),
                    'message' => array(
                                 'rule' => array('minLength', '1')
                               )
                  );

  function find($type, $options = array()) {
    switch ($type) {
      case "latest":
        $options = array('conditions' => array('key' => $options),
                         'order' => array('Chat.created' => 'desc'),
                         'limit' => 10);
        return parent::find('all', $options);
      default:
        return parent::find($type, $options);
    }
  }
}
?>