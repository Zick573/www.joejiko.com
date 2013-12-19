<?php
namespace Steam
{
  class SteamException extends \Exception {}

  class Api
  {
    protected $config;
    public function __construct($config=[])
    {

      $this->config = $config;

    }

    public function recentlyPlayedGames($args=false, $methodName="RecentlyPlayedGames")
    {


    }
  }
}