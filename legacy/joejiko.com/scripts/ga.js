/*!
 * PostcardMania GA Functions v3.0.1
 */
$(document).ready(function(){
	/* SITE VARS ======================================== */
	var config = { 
		debug : true,
		id : 'UA-620903-1',
		names : ["form1","form2","reports","samples","request","contacts","frmMain"]
	}
	var dataMap, pageTracker;	
	/* setup
	 * @todo declutter var namespace 
	 */
	var x = config.site;
	var extras = false;
	var extraName = ["ga_campaign", "ga_keyword", "ga_ad_content", "ga_adgroup"]; // extra cookie names
	var queryName = ["cpca", "kw", "ga_ad_content", "cpag"];	// query parameter names
	var utmName = ["source", "medium", "campaign", "keyword", "ad content", "search query"];	// fieldname list
	var utmValue = [];		// store field values here
	var formListAfter = ["","_header","_main","_main2"];
	var formListBefore = ["","custom ga_","ga "];
	
	/* PLUGINS ======================================== */
	/*!
	 * jQuery Cookie Plugin v1.3.1 (minified)
	 * https://github.com/carhartl/jquery-cookie
	 *
	 * Copyright 2013 Klaus Hartl
	 * Released under the MIT license
	 */
	(function(factory){if(typeof define==="function"&&define.amd&&define.amd.jQuery){define(["jquery"],factory);}else{factory(jQuery);}}(function($){var pluses=/\+/g;function raw(s){return s;}function decoded(s){return decodeURIComponent(s.replace(pluses," "));}function converted(s){if(s.indexOf('"')===0){s=s.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\");}try{return config.json?JSON.parse(s):s;}catch(er){}}var config=$.cookie=function(key,value,options){if(value!==undefined){options=$.extend({},config.defaults,options);if(typeof options.expires==="number"){var days=options.expires,t=options.expires=new Date();t.setDate(t.getDate()+days);}value=config.json?JSON.stringify(value):String(value);return(document.cookie=[encodeURIComponent(key),"=",config.raw?value:encodeURIComponent(value),options.expires?"; expires="+options.expires.toUTCString():"",options.path?"; path="+options.path:"",options.domain?"; domain="+options.domain:"",options.secure?"; secure":""].join(""));}var decode=config.raw?raw:decoded;var cookies=document.cookie.split("; ");var result=key?undefined:{};for(var i=0,l=cookies.length;i<l;i++){var parts=cookies[i].split("=");var name=decode(parts.shift());var cookie=decode(parts.join("="));if(key&&key===name){result=converted(cookie);break;}if(!key){result[name]=converted(cookie);}}return result;};config.defaults={};$.removeCookie=function(key,options){if($.cookie(key)!==undefined){$.cookie(key,"",$.extend(options,{expires:-1}));return true;}return false;};}));
	// usage: log('inside coolFunc', this, arguments);
	// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
	window.log = function f(){ log.history = log.history || []; log.history.push(arguments); if(this.console) { var args = arguments, newarr; args.callee = args.callee.caller; newarr = [].slice.call(args); if (typeof console.log === 'object') log.apply.call(console.log, console, newarr); else console.log.apply(console, newarr);}};
	// make it safe to use console.log always
	(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
	(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

	/* DEBUG ======================================== */
	if(config.debug){ console.log("debugging active"); }// @debug init
	
	/* Cookie functions ======================================== */
	function createCookie(name,value) {
		var date = new Date();
		date.setTime(date.getTime()+2628000000); // 3 months
		var expires = "; expires="+date.toGMTString();
		document.cookie = name+"="+value+expires+"; path=/";
	}
	function readCookie(name) {		
		if(config.debug){ console.log("read cookie name: "+name); }// @debug
		var nameRegex = RegExp("(?:;\\s|^)" + name + "=([^;]+)");	// match name of cookie and store value in $1
		nameValue = nameRegex.exec(document.cookie);				
		if(nameValue) {	
			if(config.debug){ console.log("readcookie namevalue: "+nameValue); }
			return unescape(nameValue[1]);
		} else {
			return null;
		}
	}
	/* read passed value of __utmz */
	function readSubCookie(name, vari) {
		if(config.debug){ console.log("readsubcookie name: "+name); }// @debug
		var nameRegex = RegExp("(?:\\||\\.)" + name + "=([^|]+)");
		nameValue = nameRegex.exec(vari);
		if(nameValue) {
			return nameValue[1];
		} else {
			return " ";
		}
	}
	/* read query parameters of string */
	function readQuery(name) {
		var nameRegex = RegExp("(?:\\?|&)" + name + "=([^&]+)");
		nameValue = nameRegex.exec(location.search);
		if(nameValue) {
			return nameValue[1];
		} else {
			return null;
		}
	}
	/* creates and renews supplemental cookies */
	function extraCookies() {	
		for(i=0;i<extraName.length;i++) {
			var oldCookieValue = readCookie(extraName[i]);
			var newCookieValue = readQuery(queryName[i]);
			if(newCookieValue || (oldCookieValue && !(readCookie("__utmb") || readCookie("__utmc")))) {
					createCookie(extraName[i], newCookieValue);
			}
		}
		extras = true;
	}
	function parseCookies() {
		var c2 = readCookie("__utmz"); 			// This gets the cookie
		var gclid = readSubCookie("utmgclid",c2);	// read utmgclid subcookie
		if(gclid!=" ") {		// read values for adwords
			
			utmValue[0] = "google";
			utmValue[1] = "cpc";
			utmValue[2] = "google";
			utmValue[3] = " ";
			utmValue[4] = " ";
			
			if(extras) {	// when supplemental cookies are enabled
				for(i=0;i<3;i++) { 
					var extraValue = readCookie(extraName[i]) // check for each cookie
					if(extraValue) {
						utmValue[i+2] = extraValue;
					}
				}
			} 
		} else {	// read values for non-adwords
			utmValue[0] = readSubCookie("utmcsr", c2);
			utmValue[1] = readSubCookie("utmcmd", c2);
			utmValue[2] = readSubCookie("utmccn", c2);
			utmValue[3] = readSubCookie("utmctr", c2);
			utmValue[4] = readSubCookie("utmcct", c2);
		}
		
		utmValue[5] = " ";
		if(extras) {
			utmName[6] = "adgroup";
			utmValue[6] = " ";
		}
		
		if(utmValue[1]=="cpc") {
			searchQuery = readCookie("__utmv");
			if(searchQuery) {
				searchValue = /^[0-9]+\.(.+)/.exec(searchQuery);
				if(searchValue) {
					utmValue[5] = searchValue[1];
				}
			}
			
			if(extras) {
				var adgroup = readCookie("ga_adgroup");
				if(adgroup) {
					utmValue[6] = adgroup;
				}
			}
		}
		
		/* data mapping */
		/* added: 2013-01-16 @JJ */
		currentDataMap = ga_createNewDataMap(utmName, utmValue);
		previousDataMap = ga_readCookie();
		
		return _setDataMap(currentDataMap, previousDataMap);
		/* */
	}
	
	/* DATA MAPPING ======================================== */	
	/* added: 2013-01-16 @JJ */
	function _setDataMap(current, previous)
	{
		if(config.debug){ // @debug
			console.log("start.function._setDataMap");
			console.log("current.length = "+current.length);
			console.log("previous.length = "+previous.length);
		}
		
		if(previous.length>0)
		{
			dataMap=previous;
		} 
		else if(current.length>0)
		{
			dataMap = current;
			ga_createCookie(current); // make cookie
		}
		else
		{
			dataMap=false;
		}
	}
	function ga_createNewDataMap(name, value)
	{
		if(config.debug){ // @debug
			console.log("start.function.ga_createNewDataMap");
			console.log("name: "+ name +" value: "+ value);
		}
		newDataMap = [];
		
		// add new input fields to DOM
		for(i=0;i<name.length;i++) {
			newDataMap.push({ 'name' : name[i], 'value' : value[i] });
		}
		
		return newDataMap;
	}
	
	/* GA ======================================== */	
	function ga_createCookie(dataMap)
	{
		$.cookie("gadata", JSON.stringify(dataMap), { expires: 30, path: '/' });
		if(config.debug){ console.log( "current data map: "+JSON.stringify(DataMap) ); } // @debug
	}
	function ga_readCookie()
	{
		return $.parseJSON($.cookie("gadata"));
	}
	/* string formatting */
	function noPercent(x){
			x = unescape(x);var xreplace = x.replace(/\+/g," ").replace(/^\s\s*/, '').replace(/\s\s*$/, '');
			return xreplace;
	}	
	/* read search data from URI */
	function sleuth(sleuthTracker)
	{
	  if(config.debug){ console.log("sleuth("+JSON.stringify(sleuthTracker)+") "); } //
		if(document.location.search.indexOf("gclid")!=-1||document.location.search.indexOf("cpc")!=-1) {	
		
			if(config.debug){	console.log("found gclid or cpc"); }
			
			var supercookie = 'cpc - ';
			var userdef = 'undefined';
			var stopfunction = false;
			var cpcregexp = new RegExp(/cpc/);
			ref = document.referrer;
			
			// DEBUG
			if(config.debug){ console.log("referrer: "+ref); }
			
			re = /(\?|&)(q|p|query|encquery|qt|terms|rdata|qs|wd|text|szukaj|k|searchExpr|search_for|string|search_query|searchfor)=([^&]+)/;
			searchq = re.exec(ref);
			
			if(document.cookie.indexOf('__utmv') != -1){
				userdef = readCookie('__utmv');
				if(cpcregexp.exec(userdef)){
					stopfunction = true;
				}
			}
			if(searchq && stopfunction == false) {
				searchq[3] = noPercent(searchq[3]);
				supercookie += searchq[3];
				sleuthTracker._setVar(supercookie);
			}
			else if(stopfunction == false){
				supercookie = supercookie + 'Paid Referral:' + document.referrer;
				sleuthTracker._setVar(supercookie);
			}
		}
	}
	
	/* DOM ======================================== */	
	function modifyForm() {
		if(config.debug){ console.log("start.function.modifyform("+name+")"); }
		
		if($("form.trackform").length>0)
		{
			frmId = $("form.trackform").attr('id');
			if(config.debug){ console.log("form found on page.. #"+frmId); } // @debug
			// read cookies and set values
			parseCookies();			
			if(config.debug){ //
				console.log( "datamap size: "+dataMap.length);
				console.log("datamap = "+JSON.stringify(dataMap));
			}
			
			directDataMap = [{"name":"source","value":"(direct)"},{"name":"medium","value":"(none)"},{"name":"campaign","value":"(direct)"},{"name":"keyword","value":" "},{"name":"ad content","value":" "},{"name":"search query","value":" "}];
					
			if(dataMap !== false)
			{
				if(config.debug){ console.log( "datamap found." ); } //
				// add new input fields to DOM
				if(dataMap.length>0)
				{
					if(config.debug){ console.log( "loop data map: " + JSON.stringify(dataMap) ); }
					for(i=0;i<dataMap.length;i++) {
						gaField = $("<input>");
						gaField.attr({
							name: "ga_"+dataMap[i].name.replace(/\s/g,"_"),
							id: "ga_"+dataMap[i].name.replace(/\s/g,"_"),
							value: dataMap[i].value,
							type: "hidden"
						});
						$("form.trackform").append(gaField);
					}
				} 
				else 
				{
					if(config.debug){ console.log('empty data map'); } // @debug
					$("form.trackfield").append($('<input type="hidden" name="error" value="problem setting GA data map" />'));
				}
			} 
			else 
			{
			  if(config.debug){ console.log( "start.appendFields.default" ); }
				// append default fields
				defaults = {
					names : ["ga_campaign", "ga_medium", "ga_source", "ga_adgroup", "ga_keyword", "ga_ad_content", "ga_search_query"],
					values : ["(direct)", "(none)", "(direct)", "", "", "", ""]
				};
				if(config.debug){ console.log("defaults: "+JSON.stringify(defaults)); }
				for(i=0;i<defaults.names.length;i++) {
					gaField = $("<input>");
					gaField.attr({
							name: defaults.names[i],
							id: defaults.names[i],
							value: defaults.values[i],
							type: "hidden"
					});
					$("form.trackform").append(gaField);
				}
			}
		}
		
		if(config.debug){ console.log("end.function.modifyform("+name+")"); }
	}
	
	/* MAGIC HAPPENS HERE ======================================== */	
	function runGA() {  
		if(config.debug){console.log("runGA()");}          
		if(typeof(_gat)=='object') {
	
			try
			{
				pageTracker = _gat._getTracker(config.id);	
				pageTracker._setAllowHash(false);
				pageTracker._setAllowLinker(true);
				
				if (!location.search.match(/gclid/)){
					if (location.search.match(/cpca=/)){
						pageTracker._setCampNameKey('cpca');
					}
					if (location.search.match(/cpag=/)){
						pageTracker._setCampContentUKey('cpag');
					}
					if (location.search.match(/kw=/)){
						pageTracker._setCampTermKey('kw');
					}						  
				}
				
				pageTracker._trackPageview();
				sleuth(pageTracker);
				
				// requires jquery	
				// loop through form names -@JJ
				if(config.names.length>0){
					if(config.debug){console.log("there are "+config.names.length+" names in the config list");} 
					for(i=0; i<config.names.length; i++){
//						if(config.debug){console.log("looping.. "+i+" current: "+config.names[i]);} 
						// add tracker class to forms
						$("form[name="+config.names[i]+"]").addClass("trackform");
					}
				}
				// add inputs to forms
				if($("form.trackform").length){modifyForm();}
	
			}
			catch(err)
			{
				if(config.debug) { console.log(JSON.stringify(err)); }
			}
		} 
		else
		{	
			if(config.debug){ console.log("typeof _gat != object"); }
			setTimeout("runGA();",100);	
		}
	}
	
	/* GO DOG, GO ======================================== */	
	runGA();
});