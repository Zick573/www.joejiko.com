
var playCounter=0;  // the tracknumber played
var playAll=1;      // if automatic go to next track
var down;           // mouse down
var site_url;       //is set somewhere else, look in headder
var swfPlayer;      //the flash player
var mp3PlayerImgPath=site_url+'/wp-content/plugins/mp3-player/';    //path to plugin
var play=0;       //indicates if the playbutton is on or off
var selectedPlayerId;
var trackNumber;
var audioElement ; //html5 player







var player={       

    setPlayerVars:function(data){

        rg=data.split('_')
        selectedPlayerId= rg[1];
        trackNumber=rg[2];

    },

    /*used by play stop pause left to the slider*/
    pause: function()
    {



        if(audioElement.canPlayType("audio/mp3")){

            audioElement.pause(); 
            play=0; 

        }else{

            swfPlayer.SetVariable("method:pause", "");
            play=0; 

        }
    },


    /*used by play stop pause left to the slider*/
    playIt: function(postId){   

        if(audioElement.canPlayType("audio/mp3")){
            audioElement.play(); 
            playAll=1; 
            selectedPlayerId=postId;
        }else{
            playAll=1; 
            swfPlayer.SetVariable("method:play", "");
            swfPlayer.SetVariable("enabled", "true");
            selectedPlayerId=postId;

        }
    },


    /*used by play stop pause left to the slider*/
    stop: function()
    {

        if(audioElement.canPlayType("audio/mp3")){

            audioElement.pause(); 
            audioElement.currentTime=0;
            play=0; 

        }else{
            swfPlayer.SetVariable("method:stop", "");
            play=0;
        } 
    },

    /*
    *this function calculates song position when moving the slider. 
    */

    setPosition: function(pos)
    {

        if(audioElement.canPlayType("audio/mp3")){
            //alert(pos);
            pos= Math.round(audioElement.duration*parseInt(pos)/100);
            position =parseInt(pos); //parseInt(myListener.position)+10000;
            audioElement.currentTime= pos;
            audioElement.play();


        }else{

            pos= Math.round(myListener.duration*parseInt(pos)/100);
            position =parseInt(pos); //parseInt(myListener.position)+10000;
            swfPlayer.SetVariable("method:setPosition", position);
        }
    }
    ,
    playHTML5:function(audioFile){  

        if(audioElement.canPlayType("audio/mp3")){

            playAll=1; 
            audioElement.setAttribute('src', audioFile);
            audioElement.load()
            audioElement.play(); 

        }else{
            swfPlayer.SetVariable("method:Stop", '');
            swfPlayer.SetVariable("method:setUrl", audioFile);
            player.playIt(selectedPlayerId);
        }
    },

    stopHTML5: function(){
        if(audioElement.canPlayType("audio/mp3")){

            audioElement.pause(); 
            play=0; 

        }else{
            swfPlayer.SetVariable("method:stop", '');
            play=0; 
        }
    },

    playerClick: function(){     

        player.setPlayerVars(jQuery(this).attr('id'));

        allAudio=playlist[selectedPlayerId];
        audioFile=allAudio[trackNumber]; //set audiofile
        audioId=trackNumber;
        playCounter=trackNumber;          //id in array of all audiofiles, has to be set so we know where we are en playlist

        if(jQuery(this).hasClass('playing')){  

            player.stopHTML5();

            jQuery(this).removeClass('playing');
            //   jQuery(this).attr('src', mp3PlayerImgPath+'play.png');
            play=0;
        }else{

            jQuery('.playBtn').removeClass('playing');
            // jQuery('.playBtn').attr('src',mp3PlayerImgPath+ 'play.png');
            play=1;

            player.playHTML5(audioFile);

            jQuery(this).addClass('playing');
            //  jQuery(this).attr('src', mp3PlayerImgPath+'stop.png');
        }
        return false;
    },

    playbtnOver:function(){

        if(jQuery(this).hasClass('playing')){

            //jQuery(this).addClass("hover"); //ny 
            // jQuery(this).attr('src', mp3PlayerImgPath+'stop_hover.png');
        }else{

            // jQuery(this).addClass("hover"); //ny 
            //alert(jQuery(this).attr('src')); 
            // jQuery(this).attr('src', mp3PlayerImgPath+'play_hover.png');
        }
    },

    playbtnOut:function(){
        if(jQuery(this).hasClass('playing')){
            //jQuery(this).attr('src', mp3PlayerImgPath+'stop.png');
        }else{
            // jQuery(this).attr('src', mp3PlayerImgPath+'play.png');
        }
    }       ,

    playerInit:function(){

        jQuery('.playBtn').click(this.playerClick);
        jQuery('.playBtn').hover(this.playbtnOver, this.playbtnOut);
        swfPlayer = document.getElementById('myFlash');
    }
}

var loaded;
var loadedPercent=10;
//var myListener = new Object();
var myListener ={

    onInit : function()
    {
        this.position = 0;

        jQuery('body').mousedown(function() {

            down=1;
        });
        jQuery('body').mouseup(function() {

            down=0;
        });
    },

    onUpdate: function()
    {                                   
        var isPlaying = this.isPlaying == "true";
        this.postId=selectedPlayerId;

        loaded=this.bytesPercent*this.duration/100;
        loadedPercent=this.bytesPercent;

        // x=this.position/this.bytesTotal;
        // hundred=100/this.bytesTotal;
        //more precise duration not load depended
        dur=   Math.round(this.duration*this.bytesTotal/this.bytesLoaded);
        //console.log('total',this.bytesTotal,'dur',dur,'loaded',this.bytesLoaded,'positon',this.position);


        sliderPosition= myListener.calculatePosition(this.position,dur);

        if(isPlaying){

            jQuery("#info_bytes"+selectedPlayerId).html(this.bytesPercent + "%");
            jQuery('#playerplay'+selectedPlayerId).css('display','none' ); 
            jQuery('#playerpause'+selectedPlayerId).css('display','block' );

        } else{


            if(playAll==1 && play==1){

                playCounter++; 

                swfPlayer.SetVariable("method:setUrl", allAudio[playCounter]);
                swfPlayer.SetVariable("method:play","");
                swfPlayer.SetVariable("enabled", "true"); 
                audio=playlist[selectedPlayerId];   

                //jQuery('#track_'+this.postId+'_'+playCounter).attr('src', mp3PlayerImgPath+'stop.png');
                jQuery('#track_'+selectedPlayerId+'_'+playCounter).addClass('playing');
                // jQuery('#track_'+this.postId+'_'+(playCounter-1)).attr('src', mp3PlayerImgPath+'play.png');
                jQuery('#track_'+selectedPlayerId+'_'+(playCounter-1)).removeClass('playing');

            }

            jQuery('#playerplay'+selectedPlayerId).css('display','block' ); 
            jQuery('#playerpause'+selectedPlayerId).css('display','none' );

        }



        if(down==0 ){ 



            jQuery('#slider'+selectedPlayerId).css('left',sliderPosition+"px");
        }

    },



    calculatePosition:function(position,duration){

        var timelineWidth = 100;
        var sliderWidth = 40;
        var sliderPositionMin = 0;
        var sliderPositionMax = 100;
        //var sliderPosition = sliderPositionMin + Math.round((timelineWidth - sliderWidth) * position / duration);
        var sliderPosition = Math.round(100*( parseInt(position)/parseInt( duration)));

        if (sliderPosition < sliderPositionMin) {
            sliderPosition = sliderPositionMin;
        }
        if (sliderPosition > sliderPositionMax) {
            sliderPosition = sliderPositionMax;
        }
        if (sliderPosition >= loaded) {  
            // sliderPosition =loaded;
            //  setPosition(sliderPosition);

        }


        return  sliderPosition;
    }

};



/**
* slider
*/

simsSlider={

    moveSlider:function(evnt)
    {
        var evnt = (!evnt) ? window.event : evnt; // The mousemove event
        if (mouseover) { // Only if slider is dragged
            x = slider.startOffsetX + evnt.screenX // Horizontal mouse position relative to allowed slider positions
            if (x > slider.xMax) x = slider.xMax // Limit horizontal movement
            if (x < 0) x = 0 // Limit horizontal movement
            jQuery('#'+slider.id).css('left',x+'px'); 

            sliderVal = x  // pixel value of slider regardless of orientation
            sliderPos = (100 / 1) * Math.round( sliderVal / slider.distance)
            v = Math.round((sliderPos + slider.from)) ; // calculate display value

            slider.v= sliderVal ;
            return false
        }
        return
    }     ,


    slide:function (evnt)
    {
        if (!evnt) evnt = window.event; // Get the mouse event causing the slider activation.

        slider = (evnt.target) ? evnt.target : evnt.srcElement; // Get the activated slider element.

        displayId = slider.getAttribute('display') // ID of associated display element.

        slider.from = 0
        slider.xMax = 100

        slider.startOffsetX = parseInt(jQuery('#'+slider.id).css('left')) - evnt.screenX // Slider-mouse horizontal offset at start of slide.

        mouseover = true
        document.onmousemove = simsSlider.moveSlider // Start the action if the mouse is dragged.
        document.onmouseup = simsSlider.sliderMouseUp // Stop sliding.

        return false
    }             ,


    sliderMouseUp:function ()
    {   
        if (mouseover) {
            v = slider.v ? slider.v: 0 // Find last display value.
            pos = v - slider.from; // Calculate slider position (regardless of orientation).
            pos = (pos > slider.xMax) ? slider.xMax : pos
            pos = (pos < 0) ? 0 : pos

            if (parseInt(pos) > parseInt(loadedPercent)) {  
                //  pos=parseInt(loadedPercent);

            }                              

            jQuery('#'+slider.id).css('left',pos+'px'); //set slider poss 

            //console.log(pos); 

            if (document.removeEventListener) { // Remove event listeners from 'document' (W3C).
                document.removeEventListener('mousemove', simsSlider.moveSlider, false)
                document.removeEventListener('mouseup', simsSlider.sliderMouseUp, false)
            }
            else if (document.detachEvent) { // Remove event listeners from 'document' (IE).
                document.detachEvent('onmousemove', simsSlider.moveSlider)
                document.detachEvent('onmouseup', simsSlider.sliderMouseUp)
            }
        }



        player.setPosition(pos);


        mouseover = false // Stop the sliding.
    }
}









function nextsongHTML5(){                                   





    if(playAll==1 && play==1){

        playCounter++; 

        audioElement.setAttribute('src',  allAudio[playCounter]);
        audioElement.load()
        audioElement.play();
        audio=playlist[selectedPlayerId];   

        //jQuery('#track_'+this.postId+'_'+playCounter).attr('src', mp3PlayerImgPath+'stop.png');
        jQuery('#track_'+selectedPlayerId+'_'+playCounter).addClass('playing');
        // jQuery('#track_'+this.postId+'_'+(playCounter-1)).attr('src', mp3PlayerImgPath+'play.png');
        jQuery('#track_'+selectedPlayerId+'_'+(playCounter-1)).removeClass('playing');

    }




}



function eventListenerHtml5(){



    var isPlaying = audioElement.paused ;

    /*the play and pause button right to the slider*/
    if(!isPlaying){

        jQuery('#playerplay'+selectedPlayerId).css('display','none' ); 
        jQuery('#playerpause'+selectedPlayerId).css('display','block' );




    } else{

        jQuery('#playerplay'+selectedPlayerId).css('display','block' ); 
        jQuery('#playerpause'+selectedPlayerId).css('display','none' );

    }




    var f;

    f=myListener.calculatePosition(audioElement.currentTime,audioElement.duration);
    if(!down){ //if mouse down we dont want the slider to move 
        jQuery('#slider'+selectedPlayerId).css('left',f+"px");
    }


    //dont know if this is right. Trying to show percent loaded
    var buffer=parseInt((audioElement.buffered.end(0)-audioElement.buffered.start(0))*100/audioElement.duration);
    jQuery("#info_bytes"+selectedPlayerId).html(buffer+ "%");


}

jQuery(document).ready(function(){ 

    player.playerInit(); 

    sliders=jQuery('body').find('.carpe_slider');
    for (i = 0; i < sliders.length; i++) {
        sliders[i].onmousedown = simsSlider.slide ;

    }


    audioElement = document.getElementById("html5Player"); //global variabel for html5plaer object

    // if html5 audio update the slider
    if(audioElement){
    if(audioElement.canPlayType("audio/mp3")){

        audioElement.addEventListener('ended',nextsongHTML5,false);
        audioElement.addEventListener('timeupdate',eventListenerHtml5,false);





}
    }

});

                


