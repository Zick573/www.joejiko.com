define(["jquery", "app/ui/_global/modal"], function($, Modal){

  function bind() {
    $(document).find('.btn-user-connect').on({
      'click': function(evt) {
        // open le modal
        Modal.open({
          className: "user-connect",
          callback: openConnectModal
        });

        evt.stopImmediatePropagation();
        evt.preventDefault();
      }
    });
  }

  function openUserEmailModal(context, data)
  {
    // @todo consolidate like functions
    var jqxhr = $.get('/api/ui', { "name": "ajax."+data.template }, function(html){
      context.empty().append(html);
    });
    jqxhr.done(function(){
      console.log("jqxhr is done! (content should be loaded)");
      context.trigger('loaded');
    });
    try {
      $.get('/api/session', {
        "method": "put",
        "values": {
          "connected_from_url": window.location.href
        }
      }, function(){ return; });
    } catch(err) {
      console.log(err.message);
    }
  }

  function openConnectModal(context)
  {
    // @todo replace with actual content
    var jqxhr = $.get('/api/ui', { "name": "ajax.user.connect"}, function(html){
      context.empty().append(html);
    });
    jqxhr.done(function(){
      console.log("jqxhr is done! (content should be loaded)");
      context.trigger('loaded');
      $(document).find('[data-modal-open="user.register-email"]').on({
          'click': function(evt) {
            // open le modal
            Modal.open({
              className: "user-connect-email",
              callback: openUserEmailModal,
              data: { template: $(this).attr('data-modal-open') }
            });

            evt.stopImmediatePropagation();
            evt.preventDefault();
          }
      })
      $(document).find('[data-modal-open="user.connect-email"]').on({
          'click': function(evt) {
            // open le modal
            Modal.open({
              className: "user-connect-email",
              callback: openUserEmailModal,
              data: { template: $(this).attr('data-modal-open') }
            });

            evt.stopImmediatePropagation();
            evt.preventDefault();
          }
      })
    });
    try {
      $.get('/api/session', {
        "method": "put",
        "values": {
          "connected_from_url": window.location.href
        }
      }, function(){ return; });
    } catch(err) {
      console.log(err.message);
    }
  }

  return {
    start: function() {
      // console.log("@search start");
      bind();
    }
  };
});