<?php
    /**
    * The functions file for for displaying the mp3 player
    *
    * @package mp3-player
    */


    $countGlobal=0;


    class simple_mp3_player{

        private $imageTag;

        function javascript_and_css(){   


            echo '<script type="text/javascript">
            //<![CDATA[
            var site_url=  \''.site_url().'\';                            
            //]]>
            </script>';


            return ;

        }

        /**
        * Load the Thickbox JavaScript if needed.
        *
        * @since 0.8
        */
        function simple_mp3_player_enqueue_script() {

            wp_enqueue_script('jquery');
            add_action('wp_print_scripts', array($this,'javascript_and_css'));
            wp_enqueue_script('simple_mp3_player_js', WP_CONTENT_URL . '/plugins/mp3-player/javascript.js', array('jquery'));

            wp_enqueue_script('simple_mp3_player_js2', WP_CONTENT_URL . '/plugins/mp3-player/js.js', array('jquery'));


            wp_enqueue_style('simple_mp3_style', WP_CONTENT_URL . '/plugins/mp3-player/style.css');

        }

        /**
        * Constructor
        * 
        */
        function simple_mp3_player(){

            /* Load any scripts needed. */
            add_action( 'template_redirect', array($this,'simple_mp3_player_enqueue_script' ));

            return;
        }


        /**
        * put your comment there...
        * 
        * @param mixed $sc
        */

        function getAttachmentsFromSoundcloud($sc){
            if($sc!=''){ //$sc soundcloud 

                $settings = get_option( 'mp3_player_settings' );
                $client_id=$settings['mp3_player_soundcloud_CLIENT_ID'];

                $data=$this->scGetData($sc,$client_id);
                $s=json_decode( $data);

                //var_dump($s->tracks);  
                if(is_array($s->tracks)){
                    foreach($s->tracks as $track){

                        $itemObj= new mp3_player_item();
                        $itemObj->setTitle($track->title);
                        $itemObj->setStreamUrl($track->stream_url.'?client_id='.$client_id);
                        $itemObj->setDownloadUrl($track->download_url.'?client_id='.$client_id);
                        $this->attachments[]=$itemObj; 

                    }

                }
            }

            return $this->attachments;
        }


        /**
        * put your comment there...
        * 
        * @param mixed $id
        */

        function getAttachmentsFromGallery($id){
            /* Arguments for get_children(). */
            $children = array(
                'post_parent' => $id,
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                //'post_mime_type' => 'image',
                //'post_mime_type' => 'audio',
                'post_mime_type' => '',
                'order' => 'ASC',
                'orderby' => 'menu_order ID',
            );


            /* Get image attachments. If none, return. */
            $attachments = get_children( $children );

            $img_src='';
            if(count($attachments)>0){
                foreach ( $attachments as $id => $attachment ){
                    if($attachment->post_mime_type=='audio/mpeg'){

                        $itemObj= new mp3_player_item();
                        $itemObj->setTitle(esc_attr( $attachment->post_title ));
                        $itemObj->setStreamUrl(wp_get_attachment_url( $id ));
                        $itemObj->setDownloadUrl($_SERVER['PHP_SELF'].'/?mp3PlayerFile='.$id.'&title='.esc_attr( $attachment->post_title ));

                        $this->attachments[]=$itemObj; 
                    }
                }

                //image for cover
                if($attachment->post_mime_type=='image/jpeg'){    
                    $img_src = get_attached_file( $id);
                    #$size='medium';    
                    #$img = wp_get_attachment_image_src( $id, $size );




                }


            }
            if($img_src!=''){

                $cover= new SimpleImage();
                $uploadDir=wp_upload_dir();

                $dir=$uploadDir['basedir'].'/mp3-player-cover/';
                if(!is_dir($dir)){
                    mkdir($dir);
                }
                $cover->cacheDir=$dir;
                $cover->cacheDirUrl=$uploadDir['baseurl'] .'/mp3-player-cover/';
                $image=$cover->get($img_src,300,300);

                $this->imageTag= '<img style="float:right" src="' . $image . '" alt="' . $title . '" title="' . $title . '" />';

            }
            return $this->attachments;
        }

        /**
        * put your comment there...
        * 
        */
        function scGetData($sc,$client_id){

            //echo $client_id;
            $request='http://api.soundcloud.com/playlists/'.$sc.'.json?client_id='.$client_id;
            $uploadDir=wp_upload_dir();

            $dir=$uploadDir['path'].'/mp3-player-soundcloud-cache';

            $playlistDir=$dir.'/'.md5($request);

            if(!is_dir($dir)){
                mkdir($dir);
            }

            if(!is_dir($playlistDir)){
                mkdir($playlistDir);
            }


            // Open a known directory, and proceed to read its contents
            if (is_dir($playlistDir)) {
                if ($dh = opendir($playlistDir)) {
                    while (($file = readdir($dh )) !== false) {
                        if($file !='.' and $file !='..' ){
                            if(time()>(intval($file)+300)){

                                unlink($playlistDir.'/'.$file);
                            }else{
                                $gotCache=file_get_contents($playlistDir.'/'.$file);
                                break;
                            }
                        }
                    }
                    closedir($dh);
                }
            }


            if(!$gotCache){
                $gotNew=@file_get_contents($request);

                $data=$gotNew;
                file_put_contents ( $playlistDir.'/'.time(),$gotNew);
            }else{
                $data=$gotCache;

            }

            return $data;

        }
        function init($attr) {

            global $post;

            /* Orderby. */
            if ( isset( $attr['orderby'] ) ) {
                $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
                if ( !$attr['orderby'] )
                    unset( $attr['orderby'] );
            }

            /* Default gallery settings. */
            $defaults = array(
                'order' => 'ASC',
                'orderby' => 'menu_order ID',
                'id' => $post->ID,
                'link' => 'full',
                'itemtag' => 'dl',
                'icontag' => 'dt',
                'captiontag' => 'dd',
                'columns' => 3,
                'size' => 'thumbnail',
                'include' => '',
                'exclude' => '',
                'numberposts' => -1,
                'offset' => '',
                'sc'=>''
            );

            /* Merge the defaults with user input. Make sure $id is an integer. */
            extract(shortcode_atts( $defaults, $attr ) );
            $id = intval( $id );

            $attachments=$this->getAttachmentsFromSoundcloud($sc);
            $attachments=$this->getAttachmentsFromGallery($id);


            /* If is feed, leave the default WP settings. We're only worried about on-site presentation. */
            if ( is_feed() ) {
                return 'music on website';
            }

            $post_id =  $id ;

            $output=$this->render($attachments,$post_id,$title,$link);
            return $output;

        }


        /**
        * Display
        * 
        * @param mixed $attachments
        * @param mixed $post_id
        * @param mixed $itemtag
        * @param mixed $icontag
        * @param mixed $id
        * @param mixed $title
        * @param mixed $size
        * @param mixed $link
        * @param mixed $attributes
        */
        function render($attachments,$post_id,$title,$link){

            global  $countGlobal ;


            // generating the js playlist
            if($countGlobal<1){
                $p='var playlist=new Array();';
            }
            $countGlobal++;
            $javascript="<script type=\"text/javascript\">

            ".$p."  
            playlist['".$post_id."']=new Array()
            ";
            $i=0;
            if(count($attachments)>0){
                foreach ( $attachments as $attachment ) {

                    $javascript.="playlist[".$post_id."][".$i."]='".$attachment->streamUrl() ."';\n";

                    //$javascript.="playlist[".$post_id."][".$i."]='".$_SERVER['PHP_SELF'].'/?mp3PlayerFile='.$id.'&title='.$title ."';\n";
                    $i++;   
                }
            }

            $javascript.="  
            </script>";
            $output=$javascript;


            //using the first gallery-image as cover

            $bild =$this->imageTag;
            add_action('wp_footer',array($this,'renderFlashPlayer'));
            $output.= "\t\t\t
            <div  class='simple-mp3-player-holder' >".$bild;
            $output .= "\n\t\t\t\t<div class='simple-mp3-player'>";


            /* Loop through each attachment. */

            $i=0;

            if(count($attachments)>0){
                foreach ( $attachments as $attachment ) {



                    $title = $attachment->title();

                    $output .= "<div class='mp3-item'  >";

                    $output .= "<div >";

                    $filename=$id;
                    $title;

                    $output .='<div>

                    <a class="download" style="float:right;line-height:12px;" href="'.$attachment->downloadUrl().'" >&nbsp</a>
                    <a class="playBtn" Id="track_'.$post_id.'_'.$i.'" onclick="return false" href="#" class="playBtn" >'.$title .'</a>

                    </div>';
                    $i++;
                    $output .= "</div>";

                    $output .= "</div>";
                }  
            }

            $output .= $this->renderPlayer($post_id).'

            </div>
            <div class="playerclear" ><!--empty commment--></div>
            </div>';


            return $output;
        }

        function renderPlayer($post_id){

            $player='        





            <div id="playercontroller_'.$post_id.'" class="playercontroller">
            <div id="playerplay'.$post_id.'" class="button play"><a href="javascript:player.playIt('.$post_id.')" >PLAY</a>


            </div>
            <div id="playerpause'.$post_id.'" class="button pause"><a href="javascript:player.pause()">PAUSE</a></div>
            <div id="playerstop'.$post_id.'" class="button stop"><a href="javascript:player.stop()">STOP</a></div>
            <div class="timeline"></div>
            <div class="carpe_horizontal_slider_track">
            <div class="carpe_slider_slit"></div>
            <div class="timeline carpe_slider" id="slider'.$post_id.'" display="display1" style="left:0px"></div>
            </div>                   
            <span class="info_bytes" id="info_bytes'.$post_id.'">loaded</span>
            </div>';  
            return $player;
        }

        function renderFlashPlayer(){


            echo '<!--[if IE]>
            <script type="text/javascript" event="FSCommand(command,args)" for="myFlash">
            eval(args);
            </script>
            <![endif]-->
            <audio id="html5Player" src=""  ></audio>
            <object class="playerpreview" id="myFlash" type="application/x-shockwave-flash" data="'.site_url().'/wp-content/plugins/mp3-player/player_mp3_js.swf" width="1" height="1">
            <param name="movie" value="'.site_url().'/wp-content/plugins/mp3-player/player_mp3_js.swf" />

            <param name="AllowScriptAccess" value="always" />
            <param name="FlashVars" value="listener=myListener&amp;interval=500" />

            </object>


            ';      
        }

    }
?>