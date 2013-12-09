<?php
namespace Google\Api
{
  class Freebase
  {
    protected $config;

    public function __construct($config=array())
    {
      $this->setDefaults();
    }

    public function get($api_name, $params)
    {
      if(in_array($api_name, $this->config->api_name))
      {
        return $this->$api_name($params);
      }

      throw new \Exception("Invalid request because api name '$api_name' not in config");
    }

    protected function search($params=array())
    {
      $config = $this->config;
      $allowed = $config->search['params'];

      // check if the passed parameters are allowed
      foreach($params as $param => $value)
      {
        if(!in_array($param, $allowed)){
          // get rid of it
          unset($params[$param]);
        }
      }

      // build query. make request
      $params = http_build_query($params);
      $url = $config->service_url.'search?key='.$config->api_key.'&'.$params;
      return file_get_contents($url);
    }

    protected function setDefaults()
    {
      $config = array(
        "api_key" => "AIzaSyCTaM2WANR7FkYrCBMFYJWBqlleuh9AL_g",
        "api_name" => array(
          "search",
          "topics"
        ),
        "service_url" => "https://www.googleapis.com/freebase/v1/",
        "search" => array(
          "params" => array("query", "filter", "output", "lang")
        )
      );
      $this->config = (object) $config;
    }
  }
}