<?php
class SteamController extends BaseController
{

  protected $steam;

  public function __construct()
  {
    $this->steam = new Jiko\Repo\Steam\Steam(Config::get('jiko.steam'));
  }

  public function friends()
  {

    $resp = [];
    foreach(Config::get('jiko.steam.ids.friends') as $name => $id) {
      $resp[] = json_decode(
        $this->steam->get([
        'steamid' => $id
        ])
      );
    }

    return Response::json($resp);
  }

  public function friend($id)
  {

    $resp = json_decode(
      $this->steam->get([
        'steamid' => $id
      ])
    );

    return Response::json($resp);
  }

  public function me()
  {

    $resp = json_decode(
      $this->steam->get()
    );

    return Response::json($resp);
  }

  public function recentlyPlayed($id=null)
  {
    if($id == 'friends') {
      return $this->friends();
    }

    if(is_numeric($id)) {
      return $this->friend($id);
    }

    if($id == 'me') {
      return $this->me();
    }

    App::abort(404);
  }
}