<?php

    /**
    * This is a image-script. Code is taken from different souces on the internet
    * To use you must accept their licens 
    * SimpleImage is modified by Simon Hansen to use crop scale and round corners and drop shadow
    */

    /**
    * File: SimpleImage.php
    * Author: Simon Jarvis
    * Copyright: 2006 Simon Jarvis
    * Date: 08/11/06
    * Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
    * 
    * This program is free software; you can redistribute it and/or 
    * modify it under the terms of the GNU General Public License 
    * as published by the Free Software Foundation; either version 2 
    * of the License, or (at your option) any later version.
    * 
    * This program is distributed in the hope that it will be useful, 
    * but WITHOUT ANY WARRANTY; without even the implied warranty of 
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
    * GNU General Public License for more details: 
    * http://www.gnu.org/licenses/gpl.html
    *
    * 
    * 
    **/





    if(!class_exists('SimpleImage')){
        class SimpleImage {

            var $image;
            var $image_type;
            var $cacheDir;
            var $cacheDirUrl;
            var $backgroundColor;



            function dropShadow2($style='normal'){

                /* set drop shadow options */

                /* offset of drop shadow from top left */
                define("DS_OFFSET",  1);

                /* number of steps from black to background color */
                define("DS_STEPS", 5);

                /* distance between steps */
                define("DS_SPREAD", 1);
                $original_image2=$this->image;

                switch($style){
                    case 'normal': $border=0;
                        break;
                    case'old': $border=10;
                        break;
                    case 'kant': $border=4;
                        break;
                    default: $border=0;

                }

                $o_width = imagesx($original_image2)+2*$border;
                $o_height = imagesy($original_image2)+2*$border;



                /* define the background color */
                $background = array("r"=> 255, "g" => 255, "b" => 255);


                $image2 = imagecreatetruecolor($o_width, $o_height);
                $color=$this->allocate_color($image2,'ffffff');
                /* floodfill the canvas with the background color */
                imagefilledrectangle($image2, 0,0, $o_width, $o_height, $color);


                /* overlay the original image on top of the border*/
                imagecopymerge($image2,$original_image2, $border,$border, 0,0, $o_width-2*$border, $o_height-2*$border, 100);

                imagedestroy($original_image2);

                $original_image=$image2;


                $width  = $o_width + (DS_STEPS*DS_SPREAD)*2;
                $height = $o_height + (DS_STEPS*DS_SPREAD)*2;

                $image = imagecreatetruecolor($width, $height);

                /* determine the offset between colors */
                $factor="1.3";
                $step_offset = array(
                "r" => ($background["r"] / (DS_STEPS*$factor)), 
                "g" => ($background["g"] / (DS_STEPS*$factor)), 
                "b" => ($background["b"] / (DS_STEPS*$factor))
                );

                /* calculate and allocate the needed colors */
                $current_color = $background;
                for ($i = 0; $i <= DS_STEPS; $i++) {
                    $colors[$i] = imagecolorallocate($image, round($current_color["r"]), round($current_color["g"]), round($current_color["b"]));

                    $current_color["r"] -= $step_offset["r"];
                    $current_color["g"] -= $step_offset["g"];
                    $current_color["b"] -= $step_offset["b"];
                }

                /* floodfill the canvas with the background color */
                imagefilledrectangle($image, 0,0, $width, $height, $colors[0]);

                /* draw overlapping rectangles to create a drop shadow effect */
                for ($i = 0; $i < count($colors); $i++) {
                    imagefilledrectangle($image, DS_SPREAD*$i, DS_SPREAD*$i, $width, $height, $colors[$i]);
                    $width -= DS_SPREAD;
                    $height -= DS_SPREAD;
                }




                $x=DS_SPREAD*DS_STEPS-DS_OFFSET;
                $y=DS_SPREAD*DS_STEPS-DS_OFFSET;


                /* overlay the original image on top of the drop shadow */
                imagecopymerge($image, $original_image, $x,$y, 0,0, $o_width, $o_height, 100);

                /* output the image */
                $this->image=$image;
                /* clean up the image resources */
                imagedestroy($original_image);


            }


            /**
            * round_corners, allocate_color and antialias_pixel is taken from round_corner script
            * made by Contributors at eXorithm. 
            * 
            * License
            * 
            * The algorithms presented on the eXorithm site are covered by the following license. The license 
            * does not apply to the eXorithm software which is covered by copyright to AKTIV Software 
            * Corporation.
            * Permission is hereby granted, free of charge, to any person obtaining a copy of these 
            * algorithms (the "Software"), to deal in the Software without restriction, including without
            * limitation the rights to use, copy, modify, merge, publish, distribute, distribute with
            * modifications, sublicense, and/or sell copies of the Software, and to permit persons to whom 
            * the Software is furnished to do so, subject to the following conditions:
            * This permission notice shall be included in all copies or substantial portions of the Software.
            * 
            * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
            * BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
            * NONINFRINGEMENT. IN NO EVENT SHALL THE ABOVE COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
            * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
            * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
            * 
            * Except as contained in this notice, the name(s) of the copyright holders shall not be used in 
            * advertising or otherwise to promote the sale, use or other dealings in this Software without
            *  prior written authorization.
            * 
            *
            * Round the corners of an image. Transparency and anti-aliasing are supported.
            *
            * @version 0.1
            * @author Contributors at eXorithm
            * @link http://www.exorithm.com/algorithm/view/round_corners Listing at eXorithm
            * @link http://www.exorithm.com/algorithm/history/round_corners History at eXorithm
            * @license http://www.exorithm.com/home/show/license
            */

            /**
            * put your comment there...
            * 
            * @param mixed $radius
            * @param mixed $color
            * @param mixed $transparency
            */
            function roundCorners($radius=20,$color="ffffff",$transparency='0')
            {


                $color=$this->backgroundColor;

                $image=$this->image;

                $width = imagesx($image);
                $height = imagesy($image);

                $image2 = imagecreatetruecolor($width, $height);

                imagecopy($image2, $image, 0, 0, 0, 0, $width, $height);

                imagesavealpha($image2, true);
                imagealphablending($image2, false);



                $full_color = $this->allocate_color($image2, $color, $transparency);

                // loop 4 times, for each corner...
                for ($left=0;$left<=1;$left++) {
                    for ($top=0;$top<=1;$top++) {

                        $start_x = $left * ($width-$radius);
                        $start_y = $top * ($height-$radius);
                        $end_x = $start_x+$radius;
                        $end_y = $start_y+$radius;

                        $radius_origin_x = $left * ($start_x-1) + (!$left) * $end_x;
                        $radius_origin_y = $top * ($start_y-1) + (!$top) * $end_y;

                        for ($x=$start_x;$x<$end_x;$x++) {
                            for ($y=$start_y;$y<$end_y;$y++) {
                                $dist = sqrt(pow($x-$radius_origin_x,2)+pow($y-$radius_origin_y,2));

                                if ($dist>($radius+1)) {
                                    imagesetpixel($image2, $x, $y, $full_color);
                                } else {
                                    if ($dist>$radius) {
                                        $pct = 1-($dist-$radius);
                                        $color2 = $this->antialias_pixel($image2, $x, $y, $full_color, $pct);
                                        imagesetpixel($image2, $x, $y, $color2);
                                    }
                                }
                            }
                        }

                    }
                }





                $this->image=$image2;
            }


            /**
            * allocate_color
            *
            * Helper function to allocate a color to an image. Color should be a 6-character hex string.
            *
            * @version 0.2
            * @author Contributors at eXorithm
            * @link http://www.exorithm.com/algorithm/view/allocate_color Listing at eXorithm
            * @link http://www.exorithm.com/algorithm/history/allocate_color History at eXorithm
            * @license http://www.exorithm.com/home/show/license
            */

            /**
            * put your comment there...
            * 
            * @param mixed $image
            * @param mixed $color
            * @param mixed $transparency
            * @return int
            */
            function allocate_color($image=null,$color='268597',$transparency='0')
            {
                if (preg_match('/[0-9ABCDEF]{6}/i', $color)==0) {
                    throw new Exception("Invalid color code.");
                }
                if ($transparency<0 || $transparency>127) {
                    throw new Exception("Invalid transparency.");
                }

                $r  = hexdec(substr($color, 0, 2));
                $g  = hexdec(substr($color, 2, 2));
                $b  = hexdec(substr($color, 4, 2));
                if ($transparency>127) $transparency = 127;

                if ($transparency<=0)
                    return imagecolorallocate($image, $r, $g, $b);
                else
                    return imagecolorallocatealpha($image, $r, $g, $b, $transparency);
            }





            /**
            * antialias_pixel
            *
            * Helper function to apply a certain weight of a certain color to a pixel in an image. The index of the resulting color is returned. 
            *
            * @version 0.1
            * @author Contributors at eXorithm
            * @link http://www.exorithm.com/algorithm/view/antialias_pixel Listing at eXorithm
            * @link http://www.exorithm.com/algorithm/history/antialias_pixel History at eXorithm
            * @license http://www.exorithm.com/home/show/license
            *
            */

            /**
            * put your comment there...
            * 
            * @param mixed $image
            * @param mixed $x
            * @param mixed $y
            * @param mixed $color
            * @param mixed $weight
            * @return int
            */
            function antialias_pixel($image=null,$x=0,$y=0,$color='0',$weight=0.5)
            {
                $c = imagecolorsforindex($image, $color);
                $r1 = $c['red'];
                $g1 = $c['green'];
                $b1 = $c['blue'];
                $t1 = $c['alpha'];

                $color2 = imagecolorat($image, $x, $y);
                $c = imagecolorsforindex($image, $color2);
                $r2 = $c['red'];
                $g2 = $c['green'];
                $b2 = $c['blue'];
                $t2 = $c['alpha'];

                $cweight = $weight+($t1/127)*(1-$weight)-($t2/127)*(1-$weight);

                $r = round($r2*$cweight + $r1*(1-$cweight));
                $g = round($g2*$cweight + $g1*(1-$cweight));
                $b = round($b2*$cweight + $b1*(1-$cweight));

                $t = round($t2*$weight + $t1*(1-$weight));

                return imagecolorallocatealpha($image, $r, $g, $b, $t);
            }


            function dropShadow($style='kant') {
                $this->backgroundColor;

                $shade= new gdShade();
                switch ($style){
                    case 'kant':

                        $shade->getIm('#000000','#'.$this->backgroundColor,$this->image,10);
                        $this->image=$shade->image;

                        break;

                    case 'normal':

                        $shade->getIm('#000000','#'.$this->backgroundColor,$this->image,0);
                        $this->image=$shade->image;

                        break;

                    case 'old':

                        $shade->getIm('#000000','#'.$this->backgroundColor,$this->image,20);
                        $this->image=$shade->image;

                        break;


                    default:   

                        $shade->getIm('#000000','#'.$this->backgroundColor,$this->image,0);
                        $this->image=$shade->image;

                }


                return;
                /** //old shadow generator 
                $image=$this->image;

                #Get image width / height
                $width = ImageSX($image);
                $height = ImageSY($image);


                //Below I'm storing all 8 shadow images into memory.
                $shadowStyle = dirname(__FILE__)."/shadow/" . $style;
                $tl = imagecreatefromgif($shadowStyle . "/shadow_TL.gif");
                $t = imagecreatefromgif($shadowStyle . "/shadow_T.gif");
                $tr = imagecreatefromgif($shadowStyle . "/shadow_TR.gif");
                $r = imagecreatefromgif($shadowStyle . "/shadow_R.gif");
                $br = imagecreatefromgif($shadowStyle . "/shadow_BR.gif");
                $b = imagecreatefromgif($shadowStyle . "/shadow_B.gif");
                $bl = imagecreatefromgif($shadowStyle . "/shadow_BL.gif");
                $l = imagecreatefromgif($shadowStyle . "/shadow_L.gif");


                $w = imagesx($l); //Width of the left shadow image
                $h = imagesy($l); //Height of the left shadow image

                $canvasHeight = $height;// + (2 * $w);
                $canvasWidth = $width;// + (2 * $w);

                //create a blank canvas with these new dimensions
                $canvas = imagecreatetruecolor($canvasWidth, $canvasHeight);

                // Putting your images together
                imagecopyresized($canvas, $t, 0, 0, 0, 0, $canvasWidth, $w, $h, $w);
                imagecopyresized($canvas, $l, 0, 0, 0, 0, $w, $canvasHeight, $w, $h);
                imagecopyresized($canvas, $b, 0, $canvasHeight - $w, 0, 0, $canvasWidth, $w, $h, $w);
                imagecopyresized($canvas, $r, $canvasWidth - $w, 0, 0, 0, $w, $canvasHeight, $w, $h);


                $w = imagesx($tl);
                $h = imagesy($tl);
                imagecopyresized($canvas, $tl, 0, 0, 0, 0, $w, $h, $w, $h);
                imagecopyresized($canvas, $bl, 0, $canvasHeight - $h, 0, 0, $w, $h, $w, $h);
                imagecopyresized($canvas, $br, $canvasWidth - $w, $canvasHeight - $h, 0, 0, $w, $h, $w, $h);
                imagecopyresized($canvas, $tr, $canvasWidth - $w, 0, 0, 0, $w, $h, $w, $h);


                $w = imagesx($l);
                imagecopyresampled($canvas, $image, $w, $w, 0, 0, imagesx($image)-(2 * $w), imagesy($image)-(2 * $w), imagesx($image), imagesy($image));




                $this->image=$canvas;
                */


            }

            function load($filename) {
                $image_info = getimagesize($filename);
                $this->image_type = $image_info[2];
                if( $this->image_type == IMAGETYPE_JPEG ) {
                    $this->image = imagecreatefromjpeg($filename);
                } elseif( $this->image_type == IMAGETYPE_GIF ) {
                    $this->image = imagecreatefromgif($filename);
                } elseif( $this->image_type == IMAGETYPE_PNG ) {
                    $this->image = imagecreatefrompng($filename);
                }
            }
            function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
                if( $image_type == IMAGETYPE_JPEG ) {
                    imagejpeg($this->image,$filename,$compression);
                } elseif( $image_type == IMAGETYPE_GIF ) {
                    imagegif($this->image,$filename);         
                } elseif( $image_type == IMAGETYPE_PNG ) {
                    imagepng($this->image,$filename);
                }   
                if( $permissions != null) {
                    chmod($filename,$permissions);
                }
            }
            function output($image_type=IMAGETYPE_JPEG) {
                if( $image_type == IMAGETYPE_JPEG ) {
                    imagejpeg($this->image);
                } elseif( $image_type == IMAGETYPE_GIF ) {
                    imagegif($this->image);         
                } elseif( $image_type == IMAGETYPE_PNG ) {
                    imagepng($this->image);
                }   
            }
            function getWidth() {
                return imagesx($this->image);
            }
            function getHeight() {
                return imagesy($this->image);
            }
            function resizeToHeight($height) {
                $ratio = $height / $this->getHeight();
                $width = $this->getWidth() * $ratio;
                $this->resize($width,$height);
            }
            function resizeToWidth($width) {
                $ratio = $width / $this->getWidth();
                $height = $this->getheight() * $ratio;
                $this->resize($width,$height);
            }
            function scale($scale) {
                $width = $this->getWidth() * $scale/100;
                $height = $this->getheight() * $scale/100; 
                $this->resize($width,$height);
            }
            function resize($width,$height) {

                $new_height=$height;
                $new_width=$width;
                $image=$this->image;
                // Get original width and height
                $width = imagesx ($image);
                $height = imagesy ($image);
                $origin_x = 0;
                $origin_y = 0;

                // generate new w/h if not provided
                if ($new_width && !$new_height) {
                    $new_height = floor ($height * ($new_width / $width));
                } else if ($new_height && !$new_width) {
                        $new_width = floor ($width * ($new_height / $height));
                    }



                    // create a new true color image
                    $canvas = imagecreatetruecolor ($new_width, $new_height);

                $src_x = $src_y = 0;
                $src_w = $width;
                $src_h = $height;

                $cmp_x = $width / $new_width;
                $cmp_y = $height / $new_height;

                // calculate x or y coordinate and width or height of source
                if ($cmp_x > $cmp_y) {

                    $src_w = round ($width / $cmp_x * $cmp_y);
                    $src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);

                } else if ($cmp_y > $cmp_x) {

                        $src_h = round ($height / $cmp_y * $cmp_x);
                        $src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);

                    }



                    imagecopyresampled($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);




                $this->image = $canvas;  


            }     


            function getWebPageBgColor(){
                $settings = get_option( 'cms_pack_settings' );

                if(!$settings['cms_pack_bg_color']){
                    return "ffffff"; //default background color of content area
                }else{
                    return trim($settings['cms_pack_bg_color'],'#');
                }
            }



            function get($src,$width,$height,$shadowStyle='',$dontCache=0){

                $rg=array('round','kant','old','normal','');

                if(!in_array($shadowStyle,$rg)){
                    die('style can be : round,kant,normal or old');
                }

                $this->backgroundColor=$this->getWebPageBgColor();

                $cacheDir=$this->cacheDir;
                $caheUrlDir=$this->cacheDirUrl;
                $filename=$cacheDir.$shadowStyle.$this->backgroundColor.$width.'x'.$height.basename($src);


               // $dontCache=1; //for testing. Make sure its comment out on active site
                if(file_exists($src) and is_file($src)){



                    if(!file_exists($filename) || $dontCache){
                        echo 'FILES GENERATED'; 

                        $this->load($src);
                        $this->resize($width,$height);

                        if($shadowStyle){
                            if($shadowStyle=='round'){
                                $this->roundCorners();
                            }else{
                                $this->dropShadow($shadowStyle);




                            }
                        }
                        $this->save($filename);

                        imagedestroy($this->image);

                    }
                    return $caheUrlDir.$shadowStyle.$this->backgroundColor.$width.'x'.$height.basename($src);
                }   

            }
        }

    }
?>
