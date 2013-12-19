<?php
class Steam extends Eloquent {


  public function friend_games($steam_id)
  {
    $steam = new \Steam\Api;

    return json_decode($steam->output(), true);
  }

  public function friends_games()
  {

    // array of friend ids
    $friend_ids = $this->config['ids']['friends'];

    $data = array();
    foreach($friend_ids as $label => $steam_id):
      $steamarr = json_decode($steam->recentlyPlayedGames(['steamid' => $steam_id], true));
      $friend = array("friend" => $label);
      $data[] = array_merge($steamarr, $friend);
    endforeach;

    return json_encode($data);
  }

  public function output()
  {
    return $this->data;
  }
}