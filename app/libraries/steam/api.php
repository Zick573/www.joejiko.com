<?php
namespace Steam
{
  class Api
  {
    protected $args = array(
      "key" => "E82B48F7548BF20966AFED91C6E649FA",
      "format" => "json"
    );
    protected $base = "http://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/";
    protected $url;

    protected function buildUrl()
    {
      $this->url = $this->base.'?'.http_build_query($this->args);
    }

    public function __construct(array $args)
    {
      $this->args = array_merge($this->args, $args);
      $this->buildUrl();
    }

    public function output()
    {
      return file_get_contents($this->url);
    }
  }
}