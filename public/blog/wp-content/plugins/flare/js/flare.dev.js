(function($, window, undefined){
    /**
     * Flare JavaScript Class
     * 
     * Assigns the window scroll event to adjust visibility and positioning of the
     * Flare widget.
     */
    var Flare = function(){
        // Object to house any jQuery extended elements used by this plugin
        var elems = {},
        
        // The namespace to work in for IDs and Classes
        ns = "flare",
        
        // The offset of the horizontal anchor element
        offset = -1,
        
        // The base offset to base calculations off of = offset.top - buffer
        baseOffset = -1,
        
        // The threshold around the offset to start fading in the widget
        threshold = 20,
        
        // The buffer to use to determine when the fading in starts, how many pixels above the offset.top
        buffer = 40,
        
        // Should counters be shown?
        enableCounters = true,
        
        // Use Humble Flare?
        enableHumbleFlare = false,
        
        // Humble Flare Count
        humbleFlareCount = 0;
        
        // Assign events to elements for interaction
        function assignEvents(){
            // Assign scroll watcher to determine visibility of Flare widget
            elems.window.bind('scroll.' + ns, function(){
                rePosition();
            }).bind('resize.' + ns, function(){
                // Only enable the vertical ShareBar if this is a single post page and not a listing
                if(elems.horizontal.length){
                    var topHorizontal = elems.horizontal.first();
                    var offset = topHorizontal.offset();
                    var baseOffset = offset.top - buffer;
                    var verticalLeftOffset = (0 - elems.window.width()/2) + offset.left - elems.vertical.width() - 40;
                    
                    if(elems.vertical.hasClass(ns + '-right')){
                        verticalLeftOffset = (offset.left + topHorizontal.width()) - elems.window.width()/2 + 40;
                    }
                    
                    // Position the vertical ShareBar horizontally
                    elems.vertical.css({
                        left: '50%',
                        marginLeft: verticalLeftOffset
                    });
                    
                    elems.horizontal.find('.' + ns + '-flyout').each(function(){
                        var $this = $(this);
                        var $button = $this.prevAll('.' + ns + '-button').eq(0);
                        var $count = $this.prev('.' + ns + '-button-count');
                        
                        $this.css({
                            left: $button.position().left
                        });
                        
                        $count.css({
                            left: $button.position().left
                        });
                    });
                }
            });
            
            elems.horizontal.add(elems.vertical).find('.' + ns + '-button').bind('mouseenter mouseleave', function(event){
                var $this = $.data(this, '$this'),
                    $flyout = $.data(this, '$flyout');
                
                if(!$this){
                    $this = $(this);
                    $.data(this, '$this');
                }
                
                if(!$flyout){
                    $flyout = $this.nextAll('.' + ns + '-flyout').eq(0);
                    $.data(this, '$flyout');
                }
                
                if($this.closest('.' + ns + '-vertical').length){
                    $flyout.css({
                        top: $this.position().top
                    });
                }
                
                if(event.type == "mouseenter"){
                    $flyout.addClass('hover');
                } else if(event.type == "mouseleave") {
                    $flyout.removeClass('hover');
                }
            });
            
            elems.horizontal.add(elems.vertical).find('.' + ns + '-flyout').bind('mouseenter mouseleave', function(event){
                var $this = $.data(this, '$this') || $.data(this, '$this', $(this)),
                    $button = $.data(this, '$button') || $.data(this, '$button', $this.prevAll('.' + ns + '-button').eq(0));
                
                if($this.closest('.' + ns + '-vertical').length){
                    $this.css({
                        top: $button.position().top
                    });
                }
                
                if(event.type == "mouseenter"){
                    $this.addClass('hover');
                } else if(event.type == "mouseleave") {
                    $this.removeClass('hover');
                }
            });
            
            elems.close.bind('click', function(event){
                event.preventDefault();
                
                elems.vertical.css({
                    height: elems.vertical.height(),
                    overflow: 'hidden'
                });
                setTimeout(function(){
                    elems.vertical.animate({
                        height: 0,
                        paddingTop: 0,
                        paddingBottom: 0,
                        opacity: 0
                    }, 500);
                }, 10);
                
                var d = new Date(),
                    year = d.getFullYear(),
                    expires = d.toString().replace(year, year + 1);
                
                document.cookie = "hide_vertical_flare=1";
            });
        }
        
        function getCounts(){
            $.ajax({
                url: window["__" + ns + "_count_url"],
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    var totalCount = 0;
                    var showCounters = true;
                    
                    for(var b in data){
                        var count = data[b];
                        
                        if(count === false){
                            count = "--";
                        }
                        if( count > 1000 && count < 1000000 ) {
                            count = Math.round( count / 1000, 1 ) + "K";
                        } else if( count >= 1000000 ) {
                            count = Math.round( count / 1000000, 1 ) + "M";
                        }
                        
                        $('.' + ns + '-button.button-type-' + b + ' .' + ns + '-button-count').text(count);
                    }
                    
                    if(!enableCounters) showCounters = false;
                    if(enableHumbleFlare && humbleFlareCount > totalCount) showCounters = false;
                    
                    if( showCounters ) {
                        elems.horizontal.add(elems.vertical).addClass('countloaded');
                        rePosition();
                    }
                    elems.horizontal.add(elems.vertical).addClass('countloadfinished');
                }
            });
        }
        
        // Initiate the Class, gather elements, assign events, etc.
        function initialize(){
            elems.horizontal = $('.' + ns + '-horizontal');
            elems.vertical = $('.' + ns + '-vertical');
            elems.window = $(window);
            elems.body = $(document.body);
            elems.document = $(document);
            elems.close = elems.vertical.find('.close a');
            
            enableCounters = elems.horizontal.hasClass('enablecounters');
            humbleFlareCount = elems.horizontal.data('humbleflarecount') || 0;
            enableHumbleFlare = elems.horizontal.hasClass('enablehumbleflare');
            
            if(document.cookie.match(/hide_vertical_flare/) && elems.vertical.hasClass('closablevertical')){
                elems.vertical.addClass(ns + '-closed');
            }
            
            var topHorizontal = elems.horizontal.first();

            offset = topHorizontal.offset();
            
            baseOffset = offset.top - buffer;
            
            elems.window.load(function(){
                baseOffset = topHorizontal.offset().top - buffer;
            });
            
            // Only enable the vertical ShareBar if this is a single post page and not a listing
            if(elems.horizontal.length){
                var verticalLeftOffset = (0 - elems.window.width()/2) + offset.left - elems.vertical.width() - 40;
                if(elems.vertical.hasClass(ns + '-right')){
                    verticalLeftOffset = (offset.left + topHorizontal.width()) - elems.window.width()/2 + 40;
                }
                
                // Position the vertical ShareBar horizontally
                elems.vertical.css({
                    left: '50%',
                    marginLeft: verticalLeftOffset
                });
                
                // Position the vertical ShareBar vertically
                rePosition();
                
                // Determine initial visibility based off scroll offset
                if(elems.window.scrollTop() > baseOffset + threshold){
                    elems.vertical.fadeIn(500);
                } else {
                    elems.vertical.css({
                        opacity: 0
                    });
                }
                
                assignEvents();
            }
            
            if(elems.horizontal.length){
                elems.horizontal.find('.' + ns + '-flyout').each(function(){
                    var $this = $(this);
                    var $button = $this.prevAll('.' + ns + '-button').eq(0);
                    var $count = $this.prev('.' + ns + '-button-count');
                    
                    $this.css({
                        left: $button.position().left
                    });
                    
                    $count.css({
                        left: $button.position().left
                    });
                });
            }

            elems.horizontal.add(elems.vertical).find('.' + ns + '-iframe-wrapper').each(function(){
                var $this = $(this);
                $this.html($this.data('code-snippet'));
            });
            
            getCounts();
        }
        
        // Change the opacity based off the position of the page's scroll and the offset values
        function rePosition(){
            var newOpacity = 0;
            var windowScrollY = elems.window.scrollTop();
            var windowHeight = elems.window.height();
            var currentOpacity = parseFloat(elems.vertical.css('opacity'));
            
            clearTimeout(window.__topTimeout);
            
            window.__topTimeout = setTimeout(function(){
                var flareHeight = elems.vertical.outerHeight();
                var flarePageHeight = windowScrollY + flareHeight;
                var windowPageHeight = elems.document.height() - 40;
                var top = Math.min(40, parseInt(windowPageHeight - flarePageHeight) );
                
                elems.vertical.animate({
                    top: top
                }, 150);
            }, 25);
            
            // Redefine opacity if it has scrolled down far enough
            if(windowScrollY > baseOffset - threshold){
                // Define as 100% opacity if scrolled past the threshold
                if(windowScrollY > baseOffset + threshold){
                    newOpacity = 1;
                    
                    // If the opacity is already 100%, just return false, no need to reapply CSS property
                    if(currentOpacity === 1){
                        return false;
                    }
                } else {
                    // Define opacity on a gradient scale if it is between the threshold values
                    if(windowScrollY < baseOffset){
                        newOpacity = (1 - ((baseOffset - windowScrollY) / threshold)) / 2;
                    } else {
                        newOpacity = (0.5 + ((windowScrollY - baseOffset) / threshold)) / 2;
                    }
                }
            } else {
                // If the opacity is already at 0%, just return false, no need to reapply CSS property
                if(currentOpacity === 0){
                    return false;
                }
            }
            
            var cssProperties = {
                opacity: newOpacity
            };
            
            elems.vertical.css(cssProperties);
        }
        
        initialize();
    };
    
    $(document).ready(function(){
        new Flare();
    });
})(jQuery, window, null);
