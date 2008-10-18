<?php
	class Chat extends ChatAppModel {
	 	var $name = 'Chat';
		var $validate = array(
			'key' => VALID_NOT_EMPTY,
			'handle' => VALID_NOT_EMPTY,
			'text' => VALID_NOT_EMPTY
		);


		function purge($timestamp) {
			return $this->query(sprintf('DELETE FROM chats WHERE created <= "%s"', date('Y-m-d H:i:s', $timestamp)));
		}
	}
?>