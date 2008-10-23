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

(function($) {
  var opts = {};

  $.fn.chat = function(options) {
    opts = $.extend({}, $.fn.chat.defaults, options);
  
    return this.each(function() {
      var $this = $(this);
      update($this);
      setInterval(function() { update($this); }, opts.interval);
      $(this).find("form").bind('submit', function() { post($(this)); return false});
    });
  };

  function update($obj) {
    $.ajax({
      url: opts.update + "/" + $obj.attr("name"),
      success: function(ret) {
        $obj.find(".chat_window").html(ret);
      }
    });
  };
  
  function post($obj) {
    var $name =  $obj.find("input[name='data[Chat][name]']");
    var $message =  $obj.find("textarea[name='data[Chat][message]']");
    var $submit = $obj.find("input[type='submit']");
    
    if( ($.trim($name.val()) == "") || ($.trim($message.val()) == "") ) {
      return;
    }
    
    var form = $obj.serialize();
    $message.attr('disabled', true);
    $submit.attr('disabled', true);  

    $.ajax({
      type: "POST",
      url: $obj.attr("action"),
      data: form,
      success: function() {
        $message.val("");
      },
      complete: function() {
        $message.attr('disabled', false);
        $submit.attr('disabled', false);
      }
    });
  };

  //
  // plugin defaults
  //
  $.fn.chat.defaults = {
    update: '/chat/update',
    interval: 5000
  };
})(jQuery);