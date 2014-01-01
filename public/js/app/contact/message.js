define(['jquery'], function($){
  var contact = {
    data: {},
    endpoint: "/contact/message"
  };

  return {
    send: function(data) {
      $wrap = $(this).parents('.contact-message-wrap');
      var xhr = $.post('/contact/message', {data: data}, function(resp){
        $wrap.find('.message').empty().append('Message sent!');
        console.log(JSON.stringify(data));
        console.log(resp);
      });
    }
  };
});