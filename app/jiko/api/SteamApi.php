<?php namespace Jiko\Api;

class SteamApi implements ApiInterface {

  protected $api_key;
  protected $endpoint;

  public function __construct($api_key, $endpoint)
  {
    $this->api_key = $api_key;
    $this->endpoint = $endpoint;
  }

  public function get($params=[])
  {
    $params['format'] = 'json';
    return file_get_contents( sprintf("%s?%s", $this->endpoint, http_build_query($params)) );
  }
}