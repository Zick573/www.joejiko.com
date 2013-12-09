define(["jquery"], function($){
  function show(data) {
    // load tracks into feed
    console.log(JSON.stringify(data));
  }
  function getTracks(callback) {
    $.getJSON('/api/music/tracks', callback);
  }

  return {
    start: function() {
      this.getTracks(this.show);
    }
  };
});