<?php
if ( ! function_exists('cdn_style_min'))
{
  /**
   * Generates the necessary HTML for a
   * CDN stylesheet link.
   *
   * @param  string $path
   * @return string
   */
  function cdn_style_min($path = 'styles.min.css')
  {
      $publicDirName = "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28";
      $cssDirName = "css";

      $path = "$publicDirName/$cssDirName/$path";
      return "<link rel='stylesheet' href='{$path}'>";
  }
}

if ( ! function_exists('dev_style_min'))
{
  /**
   * Generates the necessary HTML for a
   * CDN stylesheet link.
   *
   * @param  string $path
   * @return string
   */
  function dev_style_min($path = 'styles.min.css')
  {
      $publicDirName = "";
      $cssDirName = "css";

      $path = "$publicDirName/$cssDirName/$path";
      return "<link rel='stylesheet' href='{$path}'>";
  }
}

if ( ! function_exists('cdn'))
{
  /**
   * Generates the necessary HTML for a
   * CDN stylesheet link.
   *
   * @param  string $path
   * @return string
   */
  function cdn($path = 'https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/')
  {
      return $path;
  }
}

if ( ! function_exists('cdn_img'))
{
  /**
   * Generates the necessary HTML for a
   * CDN stylesheet link.
   *
   * @param  string $path
   * @return string
   */
  function cdn_img($path = 'https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/img/')
  {
      return $path;
  }
}

if ( ! function_exists('js_path'))
{
  /**
   * Generates the necessary HTML for a
   * CDN stylesheet link.
   *
   * @param  string $path
   * @return string
   */
  function js_path($path = '/js/')
  {
      return $path;
  }
}

if(!function_exists('bgi_site_sidebar'))
{
  /**
   * return a random background image
   *
   * @return string image src
   */
  function bgi_site_sidebar()
  {
    $arr_sidebar_bgs = [
        [
          "caption" => "game controller final fantasy",
          "src" => "http://distilleryimage4.ak.instagram.com/a024078c66c511e399ea124c84cba47c_8.jpg"
        ],
        [
          "caption" => "geek master",
          "src" => "http://distilleryimage5.ak.instagram.com/b95b4a4a6c3211e3a78d12f4d430b315_8.jpg"
        ],
        [
          "caption" => "oculus",
          "src" => "http://distilleryimage8.ak.instagram.com/4f9f50ac6f5611e3a2ca12a881e99ebc_8.jpg"
        ],
        [
          "caption" => "zeah looking like a retard",
          "src" => "http://distilleryimage2.ak.instagram.com/1cf707445ac011e396f3121282ee2c71_8.jpg"
        ],
        [
          "caption" => "mario",
          "src" => "http://distilleryimage10.ak.instagram.com/e1f6ac569fd711e2802422000a9e014e_7.jpg"
        ],
        [
          "caption" => "pikachu",
          "src" => "http://distilleryimage4.ak.instagram.com/393a7d7c146811e2a73522000a1faf50_7.jpg"
        ],
        [
          "caption" => "selfie",
          "src" => "http://distilleryimage9.ak.instagram.com/58d5bd9c140b11e3ad1322000a9e28e6_7.jpg"
        ],
        [
          "caption" => "vador jiko",
          "src" => "http://distilleryimage0.ak.instagram.com/da561f28966311e19dc71231380fe523_7.jpg"
        ]
      ];
    return $arr_sidebar_bgs[rand(0, count($arr_sidebar_bgs)-1)]["src"];
  }
}