<?php

class ChatController extends ChatAppController {
	var $name = 'Chat';
	var $components = array('RequestHandler');
  var $helpers = array('Time');
  
	function update($key) {
		$this->set('messages', $this->Chat->find('latest', $key));
	}

	function post() {
    App::import('Sanitize');
		$this->data = Sanitize::clean($this->data);
    $this->data['Chat']['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$this->Chat->save($this->data);
    die;
	}
}

?>