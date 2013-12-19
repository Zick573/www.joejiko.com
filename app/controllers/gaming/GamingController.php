<?php
class GamingController extends DefaultController
{
  public function index()
  {
    return View::make('gaming');
  }

  public function friend($key)
  {
    $steam = new Steam;
    $friends = $steam->friend_ids();
    if(!array_key_exists($key, $friends)):
      return Redirect::to('gaming');
    endif;

    return View::make('gaming.friend')->with(
      'games', $steam->friend_games($friends[$key])
    );
  }
}