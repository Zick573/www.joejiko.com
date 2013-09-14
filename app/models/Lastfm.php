<?php
use \Lastfm\Api;
class Lastfm {

  protected $config;
  protected $data;

  public function __construct($config)
  {
    $config = (object) $config;
    $this->config = $config;
    try {
      $lastfm = new \Lastfm\Api(array(
        'user' => $config->user,
        'method' => $config->method,
        'page' => $config->page
      ));
      $this->data = $lastfm->outputJSON();
    } catch (Exception $e) {
      $this->data = $e->getMessage();
    }
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