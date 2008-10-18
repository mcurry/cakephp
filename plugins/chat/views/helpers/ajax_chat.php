<?php
if (!defined('CHAT_FREQUENCY')) {
	define('CHAT_FREQUENCY', 5);
}

class AjaxChatHelper extends Helper {
	var $helpers = array('Html', 'Javascript', 'Ajax', 'Form');

	/*
	key is an identifier for the chat.
	You can have multiple chats by using different keys.
	*/
	function generate($key) {
		$js = '';
		$js .= 'function add_msg() {';
		$js .= 'if (document.getElementById("ChatText").value != "") {';
		$js .= 'var msgContainer = document.createElement("p");';
		$js .= 'msgContainer.setAttribute("class", "new");';
		$js .= 'var nameContainer = document.createElement("strong");';


		$js .= 'var nameNode = document.createTextNode(document.getElementById("ChatHandle").value + " (now): ");';
		$js .= 'var msgNode = document.createTextNode(document.getElementById("ChatText").value);';

		$js .= 'nameContainer.appendChild(nameNode);';
		$js .= 'msgContainer.appendChild(nameContainer);';
		$js .= 'msgContainer.appendChild(msgNode);';
		$js .= 'document.getElementById("chat_' . $key . '").insertBefore(msgContainer, document.getElementById("chat_' . $key . '").firstChild);';
		$js .= '}';
		$js .= '}';

		$output = '';

		$output .= '<div class="chat">';

		//setup the timer that will update the chat window
		$output .= $this->Ajax->remoteTimer(array('frequency' => CHAT_FREQUENCY, 'url' => '/chat/update/' . $key, 'update' => 'chat_' . $key));

		$output .= '<div class="chat_window" id="chat_' . $key . '"><p>Loading...</p></div>';

		$output .= $this->Ajax->form('/chat/post/' . $key, null, array('before' => 'add_msg()', 'after' => 'document.getElementById("ChatText").value=""'));
		$output .= $this->Form->hidden('Chat/key', array('value' => $key));
		$output .= '<label for="ChatHandle">Name:</label>' . $this->Form->input('Chat/handle', array('size' => '20', 'maxlength' => 20));
		$output .= '<label for="ChatText">Message:</label>' .  $this->Form->input('Chat/text', array('size' => '45', 'maxlength' => 100));
		$output .= $this->Form->submit('Send');
		$output .= '</form>';
		$output .= '</div>';

		$output .= $this->Javascript->codeBlock($js);
		//initial chat window update
		$output .= $this->Javascript->codeBlock($this->Ajax->remoteFunction(array('url' => '/chat/init/' . $key, 'update' => 'chat_' . $key)));

		return $output;
	}
}
?>