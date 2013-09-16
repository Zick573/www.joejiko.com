/* MP3-jPlayer 1.8 - admin js */
	
var MP3J_ADMIN = {
	toggle_states: [
		{ tog_0: false },
		{ tog_1: false },
		{ fox_library: false },
		{ fox_folder: false },
		{ fox_op1: false },
		{ fox_op5: false },
		{ fox_op2: false },
		{ fox_op3: false },
		{ fox_op4: false },
		{ tog_9: false }
	],
	toggleit: function ( id, label ) {
		if (this.toggle_states[id]) { 
			jQuery("#" + id + "-list").fadeOut(100); 
			jQuery("#" + id + "-toggle").empty().append(label).blur(); 
			this.toggle_states[id] = false;
		} else { 
			jQuery("#" + id + "-list").fadeIn(200); 
			jQuery("#" + id + "-toggle").empty().append('Hide '+label).blur();
			this.toggle_states[id] = true;
		}			
	},
	showhideit: function ( id, label ) {
		if (this.toggle_states[id]) { 
			jQuery("#" + id + "-list").fadeOut(100); 
			jQuery("#" + id + "-toggle").empty().append('Show '+label).blur(); 
			this.toggle_states[id] = false;
		} else { 
			jQuery("#" + id + "-list").fadeIn(200); 
			jQuery("#" + id + "-toggle").empty().append('Hide '+label).blur();
			this.toggle_states[id] = true;
		}			
	}
}


/* Form select event on player style dropdown */ 
var player_select=document.getElementById("player-select");
player_select.onchange=function(){ 
	var chosen=this.options[this.selectedIndex];
	if (chosen.value=="styleF" || chosen.value=="styleG" || chosen.value=="styleH"){ 
		$S('mp3fcss').color="#d6d6d6";
		$S('mp3fcss').backgroundColor="#fcfcfc";
		$S('mp3fcss').borderColor="#f0f0f0";
		$S('player-csssheet').color="#d6d6d6";
	}
	if (chosen.value=="styleI"){ 
		$S('mp3fcss').color="#000";
		$S('mp3fcss').backgroundColor="#fff";
		$S('mp3fcss').borderColor="#dfdfdf";
		$S('player-csssheet').color="#444";
	}
};


/* Admin/Picker interaction */ 
var curcol="";
var initcolour = "#6d9dde";
var red = 0; // between 0 and 1
var green = 0;
var blue = 0;
var hue = 0; // between 0 and 360
var saturation = 0; // between 0 and 1
var value = 0;

function putfcolour(fID,blID) {
	$woof(fID).value='#'+curcol;
	$S(blID).background='#'+curcol;
}
function udfcol(fID, blID) {
	$S(blID).background=$woof(fID).value;
}
function sendfcolour(fID) {
	hval=$woof(fID).value;
	initpicker(hval);
}
function initpicker(hval){
	HextoRGB(hval);
	RGBtoHSV();
	sendHSV={H:hue, S:saturation*100, V:value*100};
	HSVupdate(sendHSV);
	$S('SVslide').left = Math.round(saturation*100*1.62)-1 + "px";
	$S('SVslide').top = 161-Math.round((value*100*1.62)) + "px";
	$S('Hslide').top = 159-Math.round((165/360)*hue) + "px";
	$S('SV').backgroundColor='#'+color.HSV_HEX({H:hue, S:100, V:100});
}
function HextoRGB(hexString) {  
	  if(hexString === null || typeof(hexString) != "string") {
		SetRGB(0,0,0);
		return;
	  }
	  if (hexString.substr(0, 1) == '#')
		hexString = hexString.substr(1);
	  if(hexString.length != 6) {
		SetRGB(0,0,0);
		return;
	  }  
	  var r = parseInt(hexString.substr(0, 2), 16);
	  var g = parseInt(hexString.substr(2, 2), 16);
	  var b = parseInt(hexString.substr(4, 2), 16);
	  if (isNaN(r) || isNaN(g) || isNaN(b)) {
		SetRGB(0,0,0);
		return;
	  }
	  SetRGB(r,g,b);  
}
function SetRGB(r, g, b){
	  red = r/255.0;
	  green = g/255.0;
	  blue = b/255.0;
}
function RGBtoHSV(){
	  var max = Math.max(Math.max(red, green), blue);
	  var min = Math.min(Math.min(red, green), blue);
	  value = max;
	  saturation = 0;
	  if(max !== 0)
		saturation = 1 - min/max;
	  hue = 0;
	  if(min == max)
		return;
	 
	  var delta = (max - min);
	  if (red == max)
		hue = (green - blue) / delta;
	  else if (green == max)
		hue = 2 + ((blue - red) / delta);
	  else
		hue = 4 + ((red - green) / delta);
	  hue = hue * 60;
	  if(hue < 0)
		hue += 360;
}

/* Colour picker (modified DHTML Color Square by ColorJack) */
function $woof(v,o) { return((typeof(o)=='object'?o:document).getElementById(v)); }
function $S(o) { o=$woof(o); if(o) return(o.style); }
function abPos(o) { var o=(typeof(o)=='object'?o:$woof(o)), z={X:0,Y:0}; while(o!==null) { z.X+=o.offsetLeft; z.Y+=o.offsetTop; o=o.offsetParent; } return(z); }
function agent(v) { return(Math.max(navigator.userAgent.toLowerCase().indexOf(v),0)); }
function toggle(v) { $S(v).display=($S(v).display=='none'?'block':'none'); }
function within(v,a,z) { return((v>=a && v<=z)?true:false); }
function XY(e,v) { var o=agent('msie')?{'X':event.clientX+document.documentElement.scrollLeft,'Y':event.clientY+document.documentElement.scrollTop}:{'X':e.pageX,'Y':e.pageY}; return(v?o[v]:o); }
function zero(v) { v=parseInt(v); return(!isNaN(v)?v:0); }

var maxValue={'H':360,'S':100,'V':100}, HSV={H:360, S:100, V:100};
var slideHSV={H:360, S:100, V:100}, zINDEX=15, stop=1;
function HSVslide(d,o,e) {
	function tXY(e) { tY=XY(e).Y-ab.Y; tX=XY(e).X-ab.X; }
	function mkHSV(a,b,c) { return(Math.min(a,Math.max(0,Math.ceil((parseInt(c)/b)*a)))); }
	function ckHSV(a,b) { if(within(a,0,b)) return(a); else if(a>b) return(b); else if(a<0) return('-'+oo); }
	function drag(e) { 
		if(!stop) { 
			if(d!='drag') tXY(e);
			if(d=='SVslide') { 
				ds.left=ckHSV(tX-oo,162)+'px'; 
				ds.top=ckHSV(tY-oo,162)+'px';
				slideHSV.S=mkHSV(100,162,ds.left); 
				slideHSV.V=100-mkHSV(100,162,ds.top); 
				HSVupdate();
			}
			else if(d=='Hslide') { 
				var ck=ckHSV(tY-oo,163), r=['H','S','V'], z={};
				ds.top=(ck-5)+'px'; 
				slideHSV.H=mkHSV(360,163,ck);
				for(var i in r) { 
					i=r[i]; 
					z[i]=(i=='H')?maxValue[i]-mkHSV(maxValue[i],163,ck):HSV[i]; 
				}
				HSVupdate(z); 
				$S('SV').backgroundColor='#'+color.HSV_HEX({H:HSV.H, S:100, V:100});
			}
	}}
	if(stop) { 
		stop=''; 
		var ds=$S(d!='drag'?d:o);
		var ab=abPos($woof(o)), tX, tY, oo=(d=='Hslide')?2:4; 
		ab.X+=10; ab.Y+=22; 
		if(d=='SVslide') slideHSV.H=HSV.H; 
		document.onmousemove=drag; 
		document.onmouseup=function(){ 
			stop=1; 
			document.onmousemove=''; 
			document.onmouseup=''; 
		}; 
		drag(e);
	}
}
function HSVupdate(v) { 
	v=color.HSV_HEX(HSV=v?v:slideHSV);
	$woof('plugHEX').innerHTML='#'+v;
	$S('plugCUR').background='#'+v;
	curcol=v;
	return(v);
}
function loadSV() { 
	var z='';
	for(var i=165; i>=0; i--) { 
		z+="<div style=\"BACKGROUND: #"+color.HSV_HEX({H:Math.round((360/165)*i), S:100, V:100})+";\"><br /><\/div>"; 
	}
}

/* Colour library */
color={};
color.cords=function(W) {
	var W2=W/2, rad=(hsv.H/360)*(Math.PI*2), hyp=(hsv.S+(100-hsv.V))/100*(W2/2);
	$S('mCur').left=Math.round(Math.abs(Math.round(Math.sin(rad)*hyp)+W2+3))+'px';
	$S('mCur').top=Math.round(Math.abs(Math.round(Math.cos(rad)*hyp)-W2-21))+'px';
};
color.HEX=function(o) { o=Math.round(Math.min(Math.max(0,o),255));
	return("0123456789ABCDEF".charAt((o-o%16)/16)+"0123456789ABCDEF".charAt(o%16));
};
color.RGB_HEX=function(o) { var fu=color.HEX; return(fu(o.R)+fu(o.G)+fu(o.B)); };
color.HSV_RGB=function(o) {
	
	var R, G, A, B, C, S=o.S/100, V=o.V/100, H=o.H/360;
	if(S>0) { if(H>=1) H=0;
		H=6*H; F=H-Math.floor(H);
		A=Math.round(255*V*(1-S));
		B=Math.round(255*V*(1-(S*F)));
		C=Math.round(255*V*(1-(S*(1-F))));
		V=Math.round(255*V); 

		switch(Math.floor(H)) {
			case 0: R=V; G=C; B=A; break;
			case 1: R=B; G=V; B=A; break;
			case 2: R=A; G=V; B=C; break;
			case 3: R=A; G=B; B=V; break;
			case 4: R=C; G=A; B=V; break;
			case 5: R=V; G=A; B=B; break;
		}
		return({'R':R?R:0, 'G':G?G:0, 'B':B?B:0, 'A':1});
	}
	else return({'R':(V=Math.round(V*255)), 'G':V, 'B':V, 'A':1});
};
color.HSV_HEX=function(o) { return(color.RGB_HEX(color.HSV_RGB(o))); };

/* Startup */
jQuery("#tog_0-list").css( "display" , "none" );
jQuery("#tog_1-list").css( "display" , "none" );
jQuery("#fox_library-list").css( "display" , "none" );
jQuery("#fox_folder-list").css( "display" , "none" );
jQuery("#fox_op1-list").css( "display" , "none" );
jQuery("#fox_op5-list").css( "display" , "none" );
jQuery("#fox_op2-list").css( "display" , "none" );
jQuery("#fox_op3-list").css( "display" , "none" );
jQuery("#fox_op4-list").css( "display" , "none" );

$S('plugin').display='block';
loadSV();
initpicker(initcolour);
