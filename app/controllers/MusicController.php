<?php
use Lastfm\Api as LastfmApi;
class MusicController extends DefaultController {
  protected function tracker($method='user.getRecentTracks', $user='joejiko', $page=1)
  {
      $music = new LastfmApi(array(
        'method' => $method,
        'user' => $user,
        'page' => $page
      ));
      $data = $music->output('tracker');
      $data = json_decode($data);
      return array(
        'tracks' => $data->tracks,
        'stats' => $data->stats
      );
  }
  public function index()
  {
    // ajax request
    if( array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER)
    && 'xmlhttprequest' == strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] )):
      if(Input::get("tracks")):
        $data['tracks'] = Input::get("tracks");
      endif;

      if(Input::get("track")):
        $data['track'] = Input::get("track");
      endif;

      header("Content-type: application/json");
      return json_encode(array('html' => View::make('music.tracker.feed')->with($data) ));
    endif;

    $data = self::tracker();
    $this->layout->content = View::make('music.tracker')->with($data);
  }
}