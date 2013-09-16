<?php
  class mp3_player_item{
      private $title;
      private $streamUrl;
      private $downloadUrl;
      
    function  __CONSTRUCT(){
        
    }
    
    
    
    
    public function setStreamUrl($url){
        $this->streamUrl=$url;
    }
    
    
    
    public function setDownloadUrl($url){
        $this->downloadUrl=$url;
    }
    
    public function setTitle($title){
        $this->title=$title;
        
    }
    
    
    public function downloadUrl(){
        return $this->downloadUrl;
    }
    
    
    public function streamUrl(){
        return $this->streamUrl;
    }
    public function title(){
        return $this->title;
        
    }
    
  }
?>
