// extend jquery $.loading
// create overlay with loading animation
// like $.mask
/*
 * JJCOM namespace
 */
(function( JJCOM, $, undefined ){
  // private properties
  var  _error;
  var _readyInterval = window.setInterval(_jjcomReady, 500);
  var _ready = {
  	artboard: false,
  	media: false,
  	about: false
  }

  // private methods
  function _jjcomReady()
  {
    // Check for presence of required DOM elements or other JS dependencies
    // @todo create function _readyInterval
    if(jQuery !== undefined)
    {
    	// it's ready!
      window.$ = jQuery;
      window.clearInterval(_readyInterval);
      JJCOM.initialize();
    }
  }

  function _startLoading(select)
  {
  	//$(select).loading();
  }

  function _doneLoading(select)
  {
  	//$(select).loading().close;
  }

  function _artboard()
  {
  	// get artboard contents
  	// append to an empty, hidden div
  	// show artboard when all loaded
  }

  function _media()
  {

  }

  function _about()
  {

  }

  // public methods
  JJCOM.initialize = function() {
    $ = jQuery;
    _artboard();
    _media();
    _about();
    console.log("home loaded.");
  }

}( window.JJCOM = window.JJCOM || {}, jQuery ));