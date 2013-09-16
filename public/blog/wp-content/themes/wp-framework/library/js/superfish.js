// Both scripts: superfish & supersubs Copyright 2008 Joel Birch - Dual licensed under the MIT and GPL.

// Superfish v1.4.8 - jQuery menu widget
(function($){$.fn.superfish=function(op){var sf=$.fn.superfish,c=sf.c,$arrow=$(['<span class="',c.arrowClass,'"> &#187;</span>'].join("")),over=function(){var $$=$(this),menu=getMenu($$);clearTimeout(menu.sfTimer);$$.showSuperfishUl().siblings().hideSuperfishUl()},out=function(){var $$=$(this),menu=getMenu($$),o=sf.op;clearTimeout(menu.sfTimer);menu.sfTimer=setTimeout(function(){o.retainPath=($.inArray($$[0],o.$path)>-1);$$.hideSuperfishUl();if(o.$path.length&&$$.parents(["li.",o.hoverClass].join("")).length<1){over.call(o.$path)}},o.delay)},getMenu=function($menu){var menu=$menu.parents(["ul.",c.menuClass,":first"].join(""))[0];sf.op=sf.o[menu.serial];return menu},addArrow=function($a){$a.addClass(c.anchorClass).append($arrow.clone())};return this.each(function(){var s=this.serial=sf.o.length;var o=$.extend({},sf.defaults,op);o.$path=$("li."+o.pathClass,this).slice(0,o.pathLevels).each(function(){$(this).addClass([o.hoverClass,c.bcClass].join(" ")).filter("li:has(ul)").removeClass(o.pathClass)});sf.o[s]=sf.op=o;$("li:has(ul)",this)[($.fn.hoverIntent&&!o.disableHI)?"hoverIntent":"hover"](over,out).each(function(){if(o.autoArrows){addArrow($(">a:first-child",this))}}).not("."+c.bcClass).hideSuperfishUl();var $a=$("a",this);$a.each(function(i){var $li=$a.eq(i).parents("li");$a.eq(i).focus(function(){over.call($li)}).blur(function(){out.call($li)})});o.onInit.call(this)}).each(function(){var menuClasses=[c.menuClass];if(sf.op.dropShadows&&!($.browser.msie&&$.browser.version<7)){menuClasses.push(c.shadowClass)}$(this).addClass(menuClasses.join(" "))})};var sf=$.fn.superfish;sf.o=[];sf.op={};sf.IE7fix=function(){var o=sf.op;if($.browser.msie&&$.browser.version>6&&o.dropShadows&&o.animation.opacity!=undefined){this.toggleClass(sf.c.shadowClass+"-off")}};sf.c={bcClass:"sf-breadcrumb",menuClass:"sf-js-enabled",anchorClass:"sf-with-ul",arrowClass:"sf-sub-indicator",shadowClass:"sf-shadow"};sf.defaults={hoverClass:"sfHover",pathClass:"overideThisToUse",pathLevels:1,delay:800,animation:{opacity:"show"},speed:"normal",autoArrows:true,dropShadows:true,disableHI:false,onInit:function(){},onBeforeShow:function(){},onShow:function(){},onHide:function(){}};$.fn.extend({hideSuperfishUl:function(){var o=sf.op,not=(o.retainPath===true)?o.$path:"";o.retainPath=false;var $ul=$(["li.",o.hoverClass].join(""),this).add(this).not(not).removeClass(o.hoverClass).find(">ul").hide().css("visibility","hidden");o.onHide.call($ul);return this},showSuperfishUl:function(){var o=sf.op,sh=sf.c.shadowClass+"-off",$ul=this.addClass(o.hoverClass).find(">ul:hidden").css("visibility","visible");sf.IE7fix.call($ul);o.onBeforeShow.call($ul);$ul.animate(o.animation,o.speed,function(){sf.IE7fix.call($ul);o.onShow.call($ul)});return this}})})(jQuery);

// Supersubs v0.2b - jQuery plugin
(function($){$.fn.supersubs=function(options){var opts=$.extend({},$.fn.supersubs.defaults,options);return this.each(function(){var $$=$(this);var o=$.meta?$.extend({},opts,$$.data()):opts;var fontsize=$('<li id="menu-fontsize">&#8212;</li>').css({padding:0,position:"absolute",top:"-999em",width:"auto"}).appendTo($$).width();$("#menu-fontsize").remove();$ULs=$$.find("ul");$ULs.each(function(i){var $ul=$ULs.eq(i);var $LIs=$ul.children();var $As=$LIs.children("a");var liFloat=$LIs.css("white-space","nowrap").css("float");var emWidth=$ul.add($LIs).add($As).css({"float":"none",width:"auto"}).end().end()[0].clientWidth/fontsize;emWidth+=o.extraWidth;if(emWidth>o.maxWidth){emWidth=o.maxWidth}else{if(emWidth<o.minWidth){emWidth=o.minWidth}}emWidth+="em";$ul.css("width",emWidth);$LIs.css({"float":liFloat,width:"100%","white-space":"normal"}).each(function(){var $childUl=$(">ul",this);var offsetDirection=$childUl.css("left")!==undefined?"left":"right";$childUl.css(offsetDirection,emWidth)})})})};$.fn.supersubs.defaults={minWidth:9,maxWidth:25,extraWidth:0}})(jQuery);

// Configure the Superfish/Supersubs options.
(function($){
	$(document).ready(function(){
		$('.sf-menu').supersubs({
			minWidth:    	12,					// minimum width of sub-menus in em units.
			maxWidth:    	27,					// maximum width of sub-menus in em units. 
			extraWidth:  	1					// extra width can ensure lines don't sometimes turn over
												// due to slight rounding differences and font-family.
		}).superfish({
			hoverClass: 	'sfHover',			// the class applied to hovered list items.
			// pathClass: 		'current',			// the class you have applied to list items that lead to the current page.
			// pathLevels: 	1,					// the number of levels of submenus that remain open or are restored using pathClass.
			delay: 			800,				// the delay in milliseconds that the mouse can remain outside a submenu without it closing.
			animation: 		{ opacity: 'show', height: 'show' },	// an object equivalent to first parameter of jQuery’s .animate() method.
			speed: 			'fast',				// speed of the animation. Equivalent to second parameter of jQuery’s .animate() method.
			autoArrows: 	false,				// if true, arrow mark-up generated automatically = cleaner source code at expense of initialisation performance.
			dropShadows: 	false,				// completely disable drop shadows by setting this to false.
			disableHI: 		false,				// set to true to disable hoverIntent detection.
			onInit: 		function(){},		// callback function fires once Superfish is initialised – 'this' is the containing ul.
			onBeforeShow: 	function(){},		// callback function fires just before reveal animation begins – 'this' is the ul about to open.
			onShow: 		function(){},		// callback function fires once reveal animation completed – 'this' is the opened ul.
			onHide: 		function(){}		// callback function fires after a sub-menu has closed – 'this' is the ul that just closed.
		});
	});
})(jQuery);