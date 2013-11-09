<?php

    /* 
    @package mp3-player
    */ 

    /*
    Plugin Name: Mp3 Player
    Plugin URI: http://simonhans.dk
    Description: Renders an mp3 player from mp3-files in gallery
    Version: 0.9.7
    Author: Simon Hansen
    Author URI: http://simonhans.dk
    License: GPLv2 or later
    */

    /*
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
    */





    add_shortcode('mp3','mp3_player_handler');

    if(!is_admin()){ //only include for frontend
        require_once( dirname (__FILE__) . '/mp3_player_functions.php' );
        require_once( dirname (__FILE__) . '/mp3-player-item.php' );
        require_once( dirname (__FILE__) . '/SimpleImage.php' );

        $objmp3=new simple_mp3_player();//enqueue script
        add_action( 'template_redirect', 'mp3_player_download' );

    }
    
    
    //include scripts for backend
    if(is_Admin()){
        $plugin_directory = dirname(plugin_basename(__FILE__)); 
        require_once(  dirname(__FILE__). '/admin.php' );

    }




    function mp3_player_handler($attr){
        $objmp3=new simple_mp3_player();
        return $objmp3->init($attr); 

    }



    //    echo get_option( 'mp3_counter', 0 );


    function mp3_player_counter(){

        if(!get_option( 'mp3_counter')){
            add_option('mp3_counter',1);
        }
        $var=get_option( 'mp3_counter', 0 );
        $var=$var+1;
        // add_option( 'mp3_counter', $var); 
        update_option('mp3_counter', $var);

    }

    /**
    * download script. 
    * 
    */

    function mp3_player_download(){
        $fileId=$_GET['mp3PlayerFile'];  //id of attachement

        // echo $fileId;exit; 

        if($fileId){
            if(!is_numeric($fileId)){
                exit();
            }

            $fullPath =get_attached_file( $fileId );

            //echo  $fullPath; exit;

            if ($fd = fopen ($fullPath, "r")) {
                $fsize = filesize($fullPath);
                //         header("Content-type: application/octet-stream");
                header("Content-type: audio/mp3");
                header("Content-Disposition: filename=\"".$_GET["title"]."\"");
                header("Content-length: $fsize");
                while(!feof($fd)) {
                    $buffer = fread($fd, 2048);
                    echo $buffer;
                }
            }
            fclose ($fd);
            mp3_player_counter();


            exit;

        }
    }
?>