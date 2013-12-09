<?php
class Steam {

  protected $config;
  protected $data;

  public function __construct($module=null, $ids=[])
  {
    $config = \Config::get('jiko::steam');

    if($module === "friends") return $this->getFriendGames($ids);

    try {
      $steam = new \Steam\Api($module, $ids);
      $this->data = $steam->output();
    } catch (Exception $e) {
      $this->data = $e->getMessage();
    }
  }

  public function friend_games($steam_id)
  {
    $steam = new \Steam\Api(array(
      "steamid" => $steam_id
    ));

    return json_decode($steam->output(), true);
  }

  public function friends_games()
  {
    $friend_ids = \Config::get('jiko::steam.friend_ids');

    $data = array();
    foreach($friend_ids as $label => $steam_id):
      $steam = new \Steam\Api(array
        "steamid" => $steam_id
      ));
      $steamarr = json_decode($steam->output(), true);
      $friend = array("friend" => $label);
      $data[] = array_merge($steamarr, $friend);
    endforeach;

    return json_encode($data);
  }

  public function defaults()
  {
    $this->config = array(
      // me
      "steamid" => "76561198058839919"
    );
  }

  public function output()
  {
    return $this->data;
  }

  public function __get($property)
  {
    if(property_exists($this, $property)) {
      return $this->property;
    }
  }
}