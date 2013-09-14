/*
 *  Project:
 *  Description:
 *  Author:
 *  License:
 */

// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ($, window, document, undefined) {
    // Create the defaults once
    var pluginName = "defaultPluginName";
    var defaults = {
        propertyName: "value"
    };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.options = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype = {
        init: function () {
            // Place initialization logic here
            // You already have access to the DOM element and
            // the options via the instance, e.g. this.element
            // and this.options
            // you can add more functions like the one below and
            // call them like so: this.yourOtherFunction(this.element, this.options).
        },
        yourOtherFunction: function () {
            // some logic
        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);










(function($) {
	var jiko = {
			// properties
			preload: function(){
			},
			
			initialize: function(){
			},
			
			handleSignIn: function(service){
				window.open(
					"http://joejiko.com/login/"+service, 'oauth', 'toolbar=0,location=0,directories=0,status=yes,menubar=0,scrollbars=yes,resizable=yes,width=960,height=600,titlebar=yes'
				);
			},
			
			handleSignInResponse: function(service){
				
			},
			
			handleOAuth: function(){
			}
			
			// methods
	}
	$(document).ready(function() {
	})
})(jQuery);

(function( $ ) {
		
		var methods = {
			init: function( options ) {
								
				return this.each(function(){
					var $this = $(this),
							data = $this.data('jiko'),
							jiko = $('<jiko />', { 
								text: $this.attr('title')
							});
					// plugin not initialized yet
					if( ! data ){
						
					 // do more setup stuff
					 
					 $(this).data('jiko', {
						 target: $this,
						 jiko: jiko
					 });
					}
					
					$(window).bind('resize.jiko', methods.reposition);
				});
								
			},
			destroy: function() {
				
				return this.each(function(){
					
					var $this = $(this),
							data = $this.data('jiko');
					
					// namespacing
					$(window).unbind('.jiko');
					data.jiko.remove();
					$this.removeData('jiko');
					
				})
				
			},
			reposition: function() {
				// ...
			},
			show: function() {
				// ...
			},
			hide: function() {
				// ...
			},
			update: function( content ) {
				// ...
			}
		};
		
		$.fn.jiko = function( method ) {
			
			if( methods[method] ) {
				return methods[ method ].apply( this, Array.prototype.slice.call( arguements, 1 ));
			}
			else if( typeof method === 'object' || ! method ) {
				return methods.init.apply( this, arguments );
			}
			else
			{
				$.error( 'Method ' + method + ' does not exist on jQuery.jiko' );
			}
		};
})( jQuery );