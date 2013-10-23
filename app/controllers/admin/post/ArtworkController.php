<?php namespace Admin\Post;
use Auth, Eloquent, Input, View, Post, Term;
class ArtworkController extends \DefaultController
{
  protected $files;

  /**
   * Function: sanitize
   * Returns a sanitized string, typically for URLs.
   *
   * Parameters:
   *     $string - The string to sanitize.
   *     $force_lowercase - Force the string to lowercase?
   *     $anal - If set to *true*, will remove all non-alphanumeric characters.
   */
  public static function sanitize($string, $force_lowercase = true, $anal = false) {
      $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                     "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                     "â€”", "â€“", ",", "<", ".", ">", "/", "?");
      $clean = trim(str_replace($strip, "", strip_tags($string)));
      $clean = preg_replace('/\s+/', "-", $clean);
      $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
      return ($force_lowercase) ?
          (function_exists('mb_strtolower')) ?
              mb_strtolower($clean, 'UTF-8') :
              strtolower($clean) :
          $clean;
  }

  public function postIndex()
  {
    if( Post::artwork()->where('title', Input::get('title')) ):
      echo "Filename already exists in artwork.";
    endif;

    Eloquent::unguard();
    $artwork_uri_base = "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/img/artwork/";
    $artwork = new Post(array(
      'user_id' => Auth::user()->id,
      'type' => 'artwork',
      'guid' => $artwork_uri_base.Input::get('title'),
      'mime_type' => Input::get('mimeType'),
      'title' => Input::get('title'),
      'content' => Input::get('description'),
      'excerpt' => Input::get('thumbnailLink')
    ));
    $artwork->save();

    foreach(explode(',', Input::get('tags')) as $tag):
      $tag = trim($tag);
      if(!$term = Terms::tag()->where('name', '=', trim($tag))):
        // create a new tag
        $term = new Term(array(
          'name' => $tag,
          'slug' => self::sanitize($tag)
        ));
        $artwork->term()->save($term, array('taxonomy', 'tag'));


        $term_taxonomy = new Term_Taxonomy(array(
          'term_taxonomy_id' => '',
          'term_id' => '',
          'taxonomy' => 'tag',
          'count' => 1
        ));

        $term_relationship = new Term_Relationship(array(
          'object_id' => $artwork->id,
          'term_taxonomy_id' => $term_taxonomy->id
        ));
      endif;

    endforeach;
  }

  public function getIndex()
  {
    // all files and folders in /artwork
    $drive_items = $this->files->listFiles(array('q' => "'0B_9a_WMIXbTteWxWRmloeWxac0k' in parents"))->getItems();
    $artworks = Post::all();
    return View::make('admin.pages.artwork.index')->with(array(
      'drive_items' => $drive_items,
      'artworks' => $artworks
      // 'artwork' => Post::artwork()->get()
    ));
  }

  public function __construct(\Soramugi\GoogleDrive\Client $client)
  {
    // set up Google Drive API
    // $client = new \Soramugi\GoogleDrive\Client();
    $client->setClientId('29103454985.apps.googleusercontent.com');
    $client->setClientSecret('p4tbDiXaL9_fCSGOGgRD01TJ');
    // $client->setScopes(array('https://www.googleapis.com/auth/drive'));
    $client->setUseObjects(true);
    $token = '{"access_token":"ya29.AHES6ZQrYD_dp7_rQqp4oe6_lKKCtqLw8CSSQZOzAHhB-bPAnM04TQ","token_type":"Bearer","expires_in":3600,"refresh_token":"1\/kFmAsX8sIvInN3RWr0ErIm0Yqo2AULNpcBCfBZeZ5mw","created":1382304355}';
    $client->setAccessToken($token);
    $this->files = new \Soramugi\GoogleDrive\Files($client);
  }
}