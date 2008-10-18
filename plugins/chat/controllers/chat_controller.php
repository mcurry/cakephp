<?php

class ChatController extends ChatAppController {
	var $name = 'Chat';
	var $components = array('RequestHandler');

	function init($key) {
		//clear out old messages
		$this->Chat->purge(strtotime('-1 day'));

		//get the latest 10 messages
		$this->set('messages', $this->Chat->findAll(array('Chat.key' => $key), null, array('Chat.created' => 'DESC'), 10));

		$this->render('update');
	}

	function update($key) {
		//get the latest 10 messages
		$this->set('messages', $this->Chat->findAll(array('Chat.key' => $key), null, array('Chat.created' => 'DESC'), 10));
	}

	function post() {
		$this->params['data']['Chat']['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$this->params['data']['Chat']['text'] = strip_tags($this->params['data']['Chat']['text']);
		$this->Chat->save($this->params['data']);
	}


}

?>