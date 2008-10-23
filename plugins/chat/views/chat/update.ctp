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

	//flip the order
	if (isset($messages) && !empty($messages)) {
		foreach($messages as $i => $message) {
			$class = ($i % 2 == 0) ? 'odd':'even';
			printf('<p class="%s"><strong>%s (%s):</strong> %s</p>',
             $class,
             $message['Chat']['name'],
             $time->timeAgoInWords($message['Chat']['created']),
             $message['Chat']['message']);
		}
	} else {
		echo '<p>' . __('No Messages', true) . '</p>';
	}
?>