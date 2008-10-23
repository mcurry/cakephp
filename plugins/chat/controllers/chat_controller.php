<?php
/*
 * CakePHP Ajax Chat Plugin (using jQuery);
 * Copyright (c) 2008 Matt Curry
 * www.PseudoCoder.com
 * http://github.com/mcurry/cakephp/tree/master/plugins/chat
 * http://sandbox2.pseudocoder.com/demo/chat
 *
 * @author      Matt Curry <matt@pseudocoder.com>
 * @license     MIT
 *
 */

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