/* 
	MP3-jPlayer 1.8.3
   	www.sjward.org 
*/

var MP3_JPLAYER = {
	
	tID:			'',
	state:			'',	
	pl_info:		[],	
	load_pc:		0,	
	played_t:		0,	
	dl_dialogs:		[],
	timeoutIDs:		[],
	intervalIDs:	[],
	dl_domain:		'',
	jp_audio: 		{},
	jp_seekable:	0,
	sliding:		false,
	launched_ID:	null,
	jpID:			'#mp3_jplayer_1_8',
	plugin_path:	'',

	vars: {
		play_f:				false,		
		stylesheet_url:		'',
		dload_text:			'DOWNLOAD MP3',
		pp_width:			280,
		pp_maxheight:		350,
		pp_bodycolour:		'#fff',
		pp_bodyimg:			'',
		pp_fixedcss:		false,
		pp_windowheight:	600,
		pp_playerheight:	100+142,
		force_dload:		false,
		message_interval:	'<h2>Download MP3</h2><p style="margin-top:34px !important;">Your download should start in a second!</p>',
		message_ok:			'',
		message_indark:		'<h2>Download MP3</h2><p>Your download should start in a second!</p>',
		message_promtlink:	'<h2>Download MP3</h2><p>Link to the file:</p><h3><a target="_blank" href="#1">#2</a></h3><p>Depending on your browser settings, you may need to right click the link to save it.</p>',
		message_fail:		'<h2>Download Failed</h2><p>Sorry, something went wrong!</p>',
		message_timeout:	'<h2>Download<br />Unavailable</h2><p>please try again later!</p>',	
		dl_remote_path:		''
	},

	eID: {
		play:		'#playpause_mp3j_',
		playW:		'#playpause_wrap_mp3j_',
		stp: 		'#stop_mp3j_',
		prev: 		'#Prev_mp3j_',
		next: 		'#Next_mp3j_',
		vol: 		'#vol_mp3j_',
		loader:		'#load_mp3j_',
		pos: 		'#posbar_mp3j_',
		poscol: 	'#poscol_mp3j_',
		title: 		'#T_mp3j_',
		caption:	'#C_mp3j_',
		pT: 		'#P-Time-MI_',
		tT: 		'#T-Time-MI_',
		dload: 		'#download_mp3j_',
		plwrap: 	'#L_mp3j_',
		ul:			'#UL_mp3j_',
		a:			'mp3j_A_', //No hash!
		indiM:		'#statusMI_',
		toglist:	'#playlist-toggle_',
		lPP:		'#lpp_mp3j_',
		pplink:		'#mp3j_popout_',
		img:		'#MI_image_'
	},

	init: function () {
		var plpath, that = this;
				
		plpath = this.plugin_path.split('/');
		this.dl_domain = plpath[2].replace(/^www./i, "");
		
		this.unwrap();
		this.write_controls();
		jQuery(this.jpID).jPlayer({
			ready: function () {
				that.startup();
			},
			swfPath: that.plugin_path + '/js',
			volume: 1,
			supplied: "mp3",
			wmode: "window",
			solution:"html, flash",
			preload: "none"
		});
		jQuery(this.jpID).bind(jQuery.jPlayer.event.ended, function(event) {
			that.E_complete(that.tID);
		});	
		jQuery(this.jpID).bind(jQuery.jPlayer.event.timeupdate, function(event) {
			var lp = that.get_loaded(event);
			var ppA = event.jPlayer.status.currentPercentAbsolute;
			var pt = event.jPlayer.status.currentTime;
			var tt = event.jPlayer.status.duration;
			that.E_update(that.tID, lp, ppA, pt, tt);
		});
		jQuery(this.jpID).bind(jQuery.jPlayer.event.ready, function(event) {
			if(event.jPlayer.html.used && event.jPlayer.html.audio.available) {
				that.jp_audio = jQuery(that.jpID).data("jPlayer").htmlElement.audio;
			} else {
				that.jp_audio = 'flash';
			}
		});
		jQuery(this.jpID).bind(jQuery.jPlayer.event.progress, function(event) {
			var lp = that.get_loaded(event);
			var pt = event.jPlayer.status.currentTime;
			var tt = event.jPlayer.status.duration;
			that.E_loading( that.tID, lp, tt, pt );
		});
	},
	
	get_loaded: function (event) {
		var lp;
		if ( typeof this.jp_audio.buffered === "object" ) {
			if( this.jp_audio.buffered.length > 0 && this.jp_audio.duration > 0 ) {
					lp = 100 * this.jp_audio.buffered.end(this.jp_audio.buffered.length-1) / this.jp_audio.duration;
			} else {
				lp = 0; 
			}
		} else {
			lp = event.jPlayer.status.seekPercent;
		}
		this.jp_seekable = event.jPlayer.status.seekPercent; //use this for slider calcs for both html/flash solution 
		this.load_pc = lp;
		return lp;
	},
	
	Tformat: function ( sec ) { 
		var t = sec,
			s = Math.floor((t)%60),
			m = Math.floor((t/60)%60),
			h = Math.floor(t/3600);
		return ((h > 0) ? h+':' : '') + ((m > 9) ? m : '0'+m) + ':' + ((s > 9) ? s : '0'+s);
	},

	E_loading: function ( j, lp, tt, pt ) {
		if (j !== '') {		
			jQuery(this.eID.loader + j).css( "width", lp + '%' );
			if (this.pl_info[j].type === 'MI') {
				if (tt > 0 && this.played_t > 0) { 
					jQuery(this.eID.tT + j).text(this.Tformat(tt)); 
				}
			}
			if ( this.jp_audio !== 'flash' && lp < 100 ) {
				if ( pt === this.played_t && this.state === 'playing' && pt > 0 && !this.sliding ) {
					if (this.pl_info[j].type === 'MI') {
						jQuery(this.eID.indiM + j).empty().append('<span class="mp3-finding"></span><span class="mp3-tint"></span>Buffering');
					}
					if (this.pl_info[j].type === 'single' ) {
						jQuery(this.eID.indiM + j).empty().append('<span class="Smp3-finding"></span><span class="mp3-gtint"></span> ' + this.Tformat(pt));
					}
				}
				this.played_t = pt;
			}
		}
	},
	
	E_update: function (j, lp, ppA, pt, tt) {
		if (j !== '') {		
			jQuery(this.eID.loader + j).css( "width", lp + '%' );
			jQuery(this.eID.poscol + j).css( "width", ppA + '%' );
			if ( jQuery(this.eID.pos + j + ' div.ui-widget-header').length > 0 ) {
				jQuery(this.eID.pos + j).slider('option', 'value', 10*ppA);
			}
			if (pt > 0) { 
				jQuery(this.eID.pos + j).css( 'visibility', 'visible' ); 
			}
			if (this.pl_info[j].type === 'MI') {
				jQuery(this.eID.pT + j).text(this.Tformat(pt));
			}
			if ('playing' === this.state) {
				if ('MI' === this.pl_info[j].type) {
					if (tt > 0 && this.played_t === pt && lp < 100 && !this.sliding ) {
						jQuery(this.eID.indiM + j).empty().append('<span class="mp3-finding"></span><span class="mp3-tint"></span>Buffering');
						jQuery(this.eID.tT + j).text(this.Tformat(tt));
					} else if (pt > 0) {
						jQuery(this.eID.indiM + j).empty().append('Playing');
						jQuery(this.eID.tT + j).text(this.Tformat(tt));
					}
				}
				if ('single' === this.pl_info[j].type){
					if (pt > 0 ) {
						if (this.played_t === pt && lp < 100 && !this.sliding ) {
							jQuery(this.eID.indiM + j).empty().append('<span class="Smp3-finding"></span><span class="mp3-gtint"></span> ' + this.Tformat(pt));
						} else {
							jQuery(this.eID.indiM + j).empty().append('<span class="mp3-tint tintmarg"></span> ' + this.Tformat(pt));
						}
					}
				}
			}
			this.played_t = pt;
		}
	},
	
	E_complete: function (j) {
		var p = this.pl_info[j];
		if ('MI' === p.type) {
			if (p.loop || p.tr+1 < p.list.length) {
				this.E_change_track(j, 'next');
			} else {
				this.E_dblstop(j);
				this.startup();
			}
		}
		if ('single' === p.type) {
			if (p.loop) {
				this.E_change_track(j, 'next');
			} else {
				this.E_stop(j);
				this.startup();
			}
		}
	},
	
	write_controls: function () {
		var j;
		for (j = 0; j < this.pl_info.length; j += 1) {
			this.setup_a_player(j);
		}
	},
	
	startup: function () {
		var j;
		for (j = 0; j < this.pl_info.length; j += 1) {
			if (this.pl_info[j].autoplay) {
				this.pl_info[j].autoplay = false;
				this.E_change_track(j, this.pl_info[j].tr);
				return;
			}
		}
	},
	
	setup_a_player: function (j) {
		var i, li, sel, that = this, p = this.pl_info[j];
		
	//PLAYLISTERS and SINGLES
		if ('MI' === p.type || 'single' === p.type) {
			
			jQuery(this.eID.vol + j).slider({ 
				value : p.vol,
				max: 100,
				range: 'min',
				animate: false,
				slide: function (event, ui) {
					p.vol = ui.value;
					if (j === that.tID) {
						jQuery(that.jpID).jPlayer("volume", ui.value/100);
					}
				}
			});
			
			jQuery(this.eID.pos + j).mouseup(function (e) { //for posbar
				that.sliding = false;
			});
			
			sel = ('MI' === p.type) ? this.eID.play : this.eID.playW;
			jQuery(sel + j).click(function () { //play-pause click
				that.E_change_track(j, p.tr);
				jQuery(this).blur();
			});
			jQuery(sel + j).dblclick(function () { //play-pause dbl click
				if (that.state !== "playing") {
					that.E_change_track(j, p.tr);
				}
				jQuery(this).blur();
			});
				
			this.titles(j, p.tr);
		}
		
	//PLAYLISTERS
		if ('MI' === p.type) {
			jQuery(this.eID.pT + j).text('00:00');
			jQuery(this.eID.indiM + j).text('Ready');
			jQuery(this.eID.stp + j).click(function () {
				that.E_stop(j);
			});
			jQuery(this.eID.stp + j).dblclick(function () {
				that.E_dblstop(j);
			});
			
			jQuery(this.eID.plwrap + j).hide();
			if (p.list.length > 1) {
				jQuery(this.eID.next + j).click(function () {
					that.E_change_track(j, 'next');
				});
				jQuery(this.eID.prev + j).click(function () {
					that.E_change_track(j, 'prev');
				});
				jQuery(this.eID.ul + j).empty();
				for (i = 0; i < p.list.length; i += 1) {
					li = '<li>';
					li += '<a href="#" id="' + this.eID.a + j + '_' + i + '">' + p.list[i].name + '</a></li>';
					jQuery(this.eID.ul + j).append(li);
					this.add_ul_click(j, i);
				}
				jQuery('#' + this.eID.a + j + '_' + p.tr).addClass('mp3j_A_current');
				jQuery(this.eID.toglist + j).click(function () {
					that.togglelist(j);
				});
				if (p.lstate === true) {
					jQuery(this.eID.plwrap + j).show();
				}	
			}
			
			this.writedownload(j, p.tr);	
			if ( this.vars.force_dload === true ) {
				this.dl_closeinfo_click(j);
			}
			
			jQuery(this.eID.lPP + j).click(function () {
				return that.E_launchPP(j);
			});
		}
		
	//POPOUT LINKS
		if ('popout' === p.type) {
			jQuery(this.eID.pplink + j).click(function () {
				return that.E_launchPP(j);
			});
		}
	},
	
	add_ul_click: function (j, i) { //playlist item click
		var that = this;
		jQuery('#' + this.eID.a + j + "_" + i).click(function (e) {
			that.E_change_track(j, i);
			e.preventDefault();
		});
	},
		
	unwrap: function () {
		var i, j, arr;
		if (this.vars.play_f === true && typeof this.lists !== "undefined" && this.lists.length > 0) {
			for (i = 0; i < this.lists.length; i += 1) {
				arr = this.lists[i];
				for (j = 0; j < arr.length; j += 1) { 
					arr[j].mp3 = this.f_undo.f_con(arr[j].mp3);
				}
			}
		}
	},
	
	f_undo: {
		keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
		f_con : function (input) {
			var output = "", i = 0, chr1, chr2, chr3, enc1, enc2, enc3, enc4;
			input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
			while (i < input.length) {
				enc1 = this.keyStr.indexOf(input.charAt(i++)); enc2 = this.keyStr.indexOf(input.charAt(i++));
				enc3 = this.keyStr.indexOf(input.charAt(i++)); enc4 = this.keyStr.indexOf(input.charAt(i++));
				chr1 = (enc1 << 2) | (enc2 >> 4); chr2 = ((enc2 & 15) << 4) | (enc3 >> 2); chr3 = ((enc3 & 3) << 6) | enc4;
				output = output + String.fromCharCode(chr1);
				if (enc3 !== 64) { output = output + String.fromCharCode(chr2); }
				if (enc4 !== 64) { output = output + String.fromCharCode(chr3); }
			}
			output = this.utf8_f_con(output);
			return output;
		},
		utf8_f_con : function (utftext) {
			var string = "", i = 0, c, c1, c2, c3;
			while (i < utftext.length) {
				c = utftext.charCodeAt(i);
				if (c < 128) {
					string += String.fromCharCode(c); i++;
				} else if ((c > 191) && (c < 224)) {
					c2 = utftext.charCodeAt(i + 1); string += String.fromCharCode(((c & 31) << 6) | (c2 & 63)); i += 2;
				} else {
					c2 = utftext.charCodeAt(i + 1); c3 = utftext.charCodeAt(i + 2); string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63)); i += 3;
				}
			}
			return string;
		}
	},
	

	E_stop: function (j) {
		if (j === this.tID && j !== '') {
			this.clearit();
			if ( jQuery(this.eID.pos + j + ' div.ui-widget-header').length > 0 ) {
				jQuery(this.eID.pos + j).slider('destroy');
			}
			jQuery(this.eID.loader + j).css( "width", '0%' );
			this.button(j, 'play');
			if (this.pl_info[j].type === 'MI') {
				jQuery(this.eID.poscol + j).css( "width", '0%' );
				jQuery(this.eID.tT + j).empty();
				jQuery(this.eID.indiM + j).text('Stopped');
				jQuery(this.eID.pT + j).text(this.Tformat(0));
			} else {
				jQuery(this.eID.indiM + j).empty();
			}
			this.load_pc = 0;
			this.played_t = 0;
		}
	},
	
	E_dblstop: function (j) {
		this.listclass(j, this.pl_info[j].tr, 0);
		if ( this.pl_info[j].tr !== 0 ) {
			this.titles(j, 0);
		}
		this.writedownload(j, 0);
		this.E_stop(j);
		jQuery(this.eID.indiM + j).text('Ready');
		this.pl_info[j].tr = 0;
	},
	
	E_change_track: function (j, change) {
		var track, txt, p = this.pl_info[j];
		if (j === this.tID && change === p.tr) {
			if ('playing' === this.state) {
				if (this.load_pc === 0) {
					this.E_stop(j);
				} else {
					this.pauseit();
					this.button(j, 'play');
					if ('MI' === p.type) {
						jQuery(this.eID.indiM + j).text('Paused');
					}
				}
				return;
			} else if ('paused' === this.state || 'set' === this.state) {
				this.playit();
				this.button(j, 'pause');
				return;
			}
		}
		this.E_stop(this.tID);
		if ('prev' === change) {
			track = (p.tr-1 < 0) ? p.list.length-1 : p.tr-1;
		} else if ('next' === change) {
			track = (p.tr+1 < p.list.length) ? p.tr+1 : 0;
		} else {
			track = change;
		}
		jQuery(this.jpID).jPlayer("volume", 1 ); //Vol scaling fix
		this.setit(p.list[track].mp3);
		this.playit();
		jQuery(this.jpID).jPlayer("volume", p.vol/100 ); //Reset to correct vol
		txt = ('MI' === p.type) ? '<span class="mp3-finding"></span><span class="mp3-tint"></span>Connecting' : '<span class="Smp3-finding"></span><span class="mp3-gtint"></span>';
		jQuery(this.eID.indiM + j).empty().append(txt);
		this.button(j, 'pause');
		this.makeslider(j);
		if ('MI' === p.type) {
			this.listclass(j, p.tr, track);
			if ( p.tr !== track ) {
				this.titles(j, track);
			}
			if (p.download) {
				this.writedownload(j, track);
				jQuery(this.eID.dload + j).hide().addClass('whilelinks').fadeIn(400);
			}
		}
		p.tr = track;
		this.tID = j;
	},
	
	E_launchPP: function (j) {
		var li_height = 28;
		if ( this.pl_info[j].height !== false ) {
			this.vars.pp_playerheight = 100 + this.pl_info[j].height;
		}
		this.vars.pp_windowheight = ( this.pl_info[j].list.length > 1 ) ? this.vars.pp_playerheight + ( this.pl_info[j].list.length * li_height) : this.vars.pp_playerheight;
		if ( this.vars.pp_windowheight > this.vars.pp_maxheight ) {
			this.vars.pp_windowheight = this.vars.pp_maxheight;
		}
		this.launched_ID = j;
		if ( this.state === "playing" ) {
			this.pl_info[j].autoplay = true;	
		}
		this.E_stop(this.tID);
		this.setit(this.plugin_path + '/mp3/silence.mp3'); 
		this.playit(); //make chrome let go of last track (incase it didn't finish loading)
		this.clearit();
		
		var newwindow = window.open(this.plugin_path + '/popout.php', 'mp3jpopout', 'height=300, width=600, location=1, status=1, scrollbars=1, resizable=1, left=25, top=25');
		if ( this.pl_info[j].lstate === true ) {
			newwindow.resizeTo( this.vars.pp_width, this.vars.pp_windowheight );
		} else {
			newwindow.resizeTo( this.vars.pp_width, this.vars.pp_playerheight );
		}
		if (window.focus) { 
			newwindow.focus(); 
		}
		return false;
	},
	
	setit: function (file) {
		this.state = 'set';
		jQuery(this.jpID).jPlayer("setMedia", {mp3: file});
	},
	playit: function () {
		this.state = 'playing';
		jQuery(this.jpID).jPlayer("play");
	},
	pauseit: function () {
		this.state = 'paused';
		jQuery(this.jpID).jPlayer("pause");
	},
	clearit: function () {
		this.state = '';
		jQuery(this.jpID).jPlayer("clearMedia");
	},
			
	button: function (j, type) {
		if (j === '') { return; }
		if ('pause' === type) {
			if (this.pl_info[j].play_txt === '#USE_G#') { 
				jQuery(this.eID.play + j).removeClass('buttons_mp3j').addClass('buttons_mp3jpause');
			} else {
				jQuery(this.eID.play + j).text(this.pl_info[j].pause_txt);
			}
		}
		if ('play' === type) {
			if (this.pl_info[j].play_txt === '#USE_G#') {
				jQuery(this.eID.play + j).removeClass('buttons_mp3jpause').addClass('buttons_mp3j');
			} else {
				jQuery(this.eID.play + j).text(this.pl_info[j].play_txt);
			}
		}
	},
	
	listclass: function ( j, rem, add ) {
		jQuery('#'+ this.eID.a + j +'_'+ rem).removeClass('mp3j_A_current');
		jQuery('#'+ this.eID.a + j +'_'+ add).addClass('mp3j_A_current');
	},
	
	titles: function ( j, track ) {
		var p = this.pl_info[j], Olink = '', Clink = '';	
		if (p.type === "MI") {
			jQuery(this.eID.title + j).empty().append(p.list[track].name).append('<br /><span>' + p.list[track].artist + '</span>');
			if (p.list[track].image !== '') {
				if (p.list[track].imgurl !== '') {
					Olink = '<a href="' + p.list[track].imgurl + '">';
					Clink = '</a>';
				}
				jQuery(this.eID.img + j).empty().hide().append(Olink + '<img src="' + p.list[track].image + '" />' + Clink).fadeIn(300);
			}
		}
	},
	
	writedownload: function ( j, track ) {
		var p = this.pl_info[j];
		if (p.download) {
			jQuery(this.eID.dload + j).empty().removeClass('whilelinks').append('<a id="mp3j_dlanchor_' + j + '" href="' + p.list[track].mp3 + '" target="_blank">' + this.vars.dload_text + '</a>');
			if ( this.vars.force_dload === true ) {
				this.dl_button_click( j );
			}
		}
	},
	
	togglelist: function ( j ) {
		if (this.pl_info[j].lstate === true) {
			jQuery(this.eID.plwrap + j).fadeOut(300);
			jQuery(this.eID.toglist + j).text('SHOW');
			this.pl_info[j].lstate = false;
		} else if (this.pl_info[j].lstate === false) {
			jQuery(this.eID.plwrap + j).fadeIn("slow");
			jQuery(this.eID.toglist + j).text('HIDE');
			this.pl_info[j].lstate = true;
		}
	},
		
	makeslider: function (j) {
		var phmove, cssmove, that = this;
		jQuery(this.eID.pos + j).css( 'visibility', 'hidden' );
		jQuery(this.eID.pos + j).slider({
			max: 1000,
			range: 'min',
			animate: false,
			slide: function (event, ui) { 
				if ((ui.value/10) < that.load_pc) {
					cssmove = ui.value/10;
					phmove = ui.value*(10.0/that.jp_seekable);
				} else {
					cssmove = 0.99*that.load_pc;
					phmove = (9.9*that.load_pc)*(10.0/that.jp_seekable);
				}
				jQuery(that.eID.poscol + j).css('width', cssmove + '%');
				jQuery(that.jpID).jPlayer("playHead", phmove );
				if (that.state === 'paused') { 
					that.button(j, 'pause');
					that.playit();
				}
				that.state = 'playing';
				that.sliding = true;
			}
		});
	}
	
};

// Force browser download
MP3_JPLAYER.dl_button_click = function ( j ) {
	var that = this, p = this.pl_info[j];
	jQuery('#mp3j_dlanchor_' + j).click(function (e) {
		that.dl_runinfo( p.list[p.tr].mp3, j, e );
		e.preventDefault();
	});
};

MP3_JPLAYER.dl_closeinfo_click = function ( j ) {
	var that = this;
	jQuery('#mp3j_finfo_close_' + j).click(function () {
		that.dl_dialogue( j, '', 'close');
		that.clear_timers( j );
	});
};	

MP3_JPLAYER.dl_runinfo = function ( get, j, e ) {
	var can_write,  
		dlpath,
		message,
		that = this,
		dlframe = false,
		p = this.pl_info[j],
		is_local = this.is_local_dload( get );
	
	if ( !this.intervalIDs[ j ] && !this.timeoutIDs[ j ] ) { //if timers not already running for this player
		can_write = this.write_cookie('mp3Download' + j, 'waiting', '');
		if ( is_local ) {
			if ( can_write !== false ) {
				this.dl_dialogue( j, this.vars.message_interval, 'check');
			} else {
				this.dl_dialogue( j, this.vars.message_indark, 'indark');
			}
			this.intervalIDs[ j ] = setInterval( function(){ that.dl_interval_check( j, can_write ); }, 500);
			this.timeoutIDs[ j ] = setTimeout( function(){ that.dl_timeout( j, can_write ); }, 7000);
			dlframe = true;
		} else {
			if ( this.vars.dl_remote_path === '' ) {
				message = this.vars.message_promtlink.replace('#1', get);
				message = message.replace('#2', p.list[p.tr].name);
				this.dl_dialogue( j, message, 'indark');
			} else {
				message = this.vars.message_indark.replace('#1', get);
				message = message.replace('#2', p.list[p.tr].name);
				this.dl_dialogue( j, message, 'indark');
				dlframe = true;
			}
		}
		this.dl_dialogs[ j ] = 'false';
		if ( dlframe ) {
			dlpath = this.get_dloader_path( get );
			jQuery('#mp3j_dlf_' + j).empty().append('<iframe id="mp3j_dlframe_' + j + '" name="mp3j_dlframe_' + j + '" class="mp3j-dlframe" src="' + dlpath + '?mp3=loc' + get + '&pID=' + j + '" style="display:none;"></iframe>');
		}	
	}
};

MP3_JPLAYER.dl_interval_check = function  ( j, can_write ) {
	if ( can_write !== false && this.read_cookie('mp3Download' + j) === 'true' ) {	//got cookie back, all should be good	
		this.dl_dialogue( j, this.vars.message_ok, 'hide');
		//jQuery('#debug').append('<br />check: cookie '+j+' true');
		this.clear_timers( j );
	} else if ( this.dl_dialogs[ j ] !== 'false' ) { //got a message back
		this.dl_dialogue( j, this.dl_dialogs[ j ], 'add');		
		//jQuery('#debug').append('<br />check: dialog'+j+' true');
		this.clear_timers( j );	
	} //else {
		//jQuery('#debug').append('<br />check: neither '+j+'...# ');
	//}																		
};

MP3_JPLAYER.dl_timeout = function ( j, can_write  ) {
	this.clear_timers( j );
	if ( can_write !== false ) {
		this.dl_dialogue( j, this.vars.message_timeout, 'add');
	}
	//jQuery('#debug').append('<br />no responses ('+j+' timed out) ');
};

MP3_JPLAYER.clear_timers = function ( j ) {
	if ( this.intervalIDs[ j ] !== null && this.timeoutIDs[ j ] !== null ) {
		clearInterval( this.intervalIDs[j] );
		clearTimeout( this.timeoutIDs[j] );
		this.intervalIDs[ j ] = null;
		this.timeoutIDs[j] = null;
	}
	jQuery('#mp3j_dlf_' + j).empty(); //ditch iframe
	this.write_cookie('mp3Download' + j, '', -1); //clear any cookie
	//jQuery('#debug').append('<br />cookie/frame '+j+' cleared');
};

MP3_JPLAYER.dl_dialogue = function ( j, text, state ) {
	if ( 'check' === state ) {
		jQuery('#mp3j_finfo_gif_' + j).show();
		jQuery('#mp3j_finfo_txt_' + j).empty().append(text).show();
		jQuery('#mp3j_finfo_' + j).show();
	} else if ( 'add' === state ) {
		jQuery('#mp3j_finfo_gif_' + j).hide();
		jQuery('#mp3j_finfo_txt_' + j).empty().append(text).show();
	} else if ( 'indark' === state ) {
		jQuery('#mp3j_finfo_gif_' + j).hide();
		jQuery('#mp3j_finfo_txt_' + j).empty().append(text).show();
		jQuery('#mp3j_finfo_' + j).fadeIn(100);
	} else if ( 'close' === state ) {
		jQuery('#mp3j_finfo_gif_' + j).hide();
		jQuery('#mp3j_finfo_' + j).hide();
	} else {
		jQuery('#mp3j_finfo_gif_' + j).hide();
		if ( text !== '' ) {
			jQuery('#mp3j_finfo_txt_' + j).empty().append(text).show();
		}
		jQuery('#mp3j_finfo_' + j).fadeOut(1000);
	}
};

MP3_JPLAYER.read_cookie = function ( name ) {
	var i, cookie, allCookies = document.cookie.split('; ');
	if ( allCookies.length > 0 ) {
		for ( i = 0; i < allCookies.length; i += 1 ) {
			cookie = allCookies[i].split( '=' );
			if ( cookie[0] === name ) {
				return cookie[1];
			}
		}
	}
	return false;
};

MP3_JPLAYER.write_cookie = function ( name, value, days ) {
	var date, expires = "";
	if ( days ) {
		date = new Date();
		date.setTime( date.getTime() + (days*24*60*60*1000) );
		expires = "; expires=" + date.toGMTString();
	}
	document.cookie = name + "=" + value + expires + "; path=/";
	return this.read_cookie( name );
};

MP3_JPLAYER.get_dloader_path = function ( loc ) {
	var k, path = "", file = "", chunks; 
	chunks = loc.split('/');
	file = chunks[chunks.length-1];
	//jQuery('#debug').append('<br />');
	//for ( k = 0; k < chunks.length; k += 1 ) {
	//	jQuery('#debug').append('<br />[' + k + '] ' + chunks[k]);
	//}
	//jQuery('#debug').append('<br />file:' + file);
	if ( loc.charAt(0) === '/' ) {
		path = this.plugin_path + '/download.php';
	} else {
		path = chunks[2].replace(/^www./i, "");
		if ( path === this.dl_domain ) {
			path = this.plugin_path + '/download.php';
		} else {
			path = chunks[0] + '//' + chunks[2] + this.vars.dl_remote_path;
		}
	}
	//jQuery('#debug').append('<br />path:' + path);
	return path;
};

MP3_JPLAYER.is_local_dload = function ( loc ) {
	var domain = "", file = "", chunks, is_local = false; 
	chunks = loc.split('/');
	file = chunks[chunks.length-1];
	if ( loc.charAt(0) === '/' ) {
		is_local = true;
	} else {
		domain = chunks[2].replace(/^www./i, "");
		if ( domain === this.dl_domain ) {
			is_local = true;
		}
	}
	return is_local;
};

