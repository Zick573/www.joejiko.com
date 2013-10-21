define(["jquery"], function($){
  function show(evt){
    $this = $(this);
    $tooltipWrap = $('<div class="tooltip-wrap" />')
      .css(
        {
          position: "relative",
          display: "block",
          "min-width": "100%",
          height: "0"
        }
      );
    $tooltip = $('<span class="tooltip" />')
      .append($this.prop('title'))
      .css(
        {
          position: "absolute",
          left: "0",
          top: "-"+$this.height(),
          background: "#000",
          color: "#fff",
          padding: ".5rem",
          "font-size": ".8rem",
          "border-radius": "3px"
        }
      );
    $tooltip.appendTo($tooltipWrap);
    $tooltipWrap.appendTo($this);
    // console.log($this.prop('title'));
  }

  function hide(evt)
  {
    var $this;
    $(document).find($('.tooltip-wrap')).each(function(i, e){
      $this = $(this);
      $this.fadeOut('fast', function(){
        $this.remove();
      });
    });
  }

  return {
    start: function() {
      $(document).on('mouseenter', '[data-tooltip=true]', show);
      $(document).on('mouseleave', '[data-tooltip=true]', hide);
    }
  };
});