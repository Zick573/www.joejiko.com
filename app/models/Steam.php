<?php
class Steam {

  protected $config;
  protected $data;

  public function __construct($config=array())
  {
    $this->defaults();

    if(array_key_exists('module', $config)) {
      if($config['module'] === "friends") {
        $this->data = self::getFriendGames();
        return $this;
      }
    }

    try {
      $steam = new \Steam\Api($this->config);
      $this->data = $steam->output();
    } catch (Exception $e) {
      $this->data = $e->getMessage();
    }

    return $this;
  }

  public function getFriendGames()
  {
    $friend_ids = array(
      "gimp" => "76561198032148118",
      "vashton" => "76561197969364176",
      "bekah" => "76561198099283523",
      "zach" => "76561198079545715"
    );

    $data = array();
    foreach($friend_ids as $label => $steamid):
      $steam = new \Steam\Api(array(
        "steamid" => $steamid
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