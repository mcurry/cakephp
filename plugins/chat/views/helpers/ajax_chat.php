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

if (!defined('CHAT_FREQUENCY')) {
	define('CHAT_FREQUENCY', 5);
}

class AjaxChatHelper extends Helper {
	var $helpers = array('Html', 'Javascript', 'Form');

	/*
	key is an identifier for the chat.
	You can have multiple chats by using different keys.
	*/
	function generate($key) {
    $id = sprintf('chat_%s', $key);

    echo $this->Javascript->codeBlock('
          $(document).ready(function(){
            $("#' . $id . '").chat();
          });
    ');    
    
		$html = array();
		$html[] = '<div id="' . $id. '" class="chat" name="' . $key . '">';
		$html[] = '<div class="chat_window"><p>Loading...</p></div>';
		$html[] = $this->Form->create('Chat', array('id' => $key . 'ChatForm',
                                                'url' => array('controller' => 'chat', 'action' => 'post')));
		$html[] = $this->Form->input('key', array('type' => 'hidden', 'value' => $key));
  
    $html[] = $this->Form->input('name', array('id' => $key . 'ChatName'));
    $html[] = $this->Form->input('message', array('id' => $key . 'ChatMessage',
                                                  'type' => 'textarea'));
    
  	$html[] = $this->Form->end(__('Send', true));
		$html[] = '</form>';
		$html[] = '</div>';

		return "\n" . implode("\n", $html);
	}
}
?>