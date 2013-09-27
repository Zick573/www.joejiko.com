<?php namespace Lastfm;
class Api
{
  protected $_allowed;
  protected $_apiKey = "4de76348304bf7b16f34396a19a04468";
  protected $_base = "http://ws.audioscrobbler.com/2.0/";
  protected $_queryString;
  protected $_url;

  protected function _buildUrl()
  {
    // build URL
    $this->_url = $this->_base.'?'.$this->_queryString.'&api_key='.$this->_apiKey;
  }

  protected function _setAllowed()
  {
    $this->_allowed = array(
      'method' => array('user.getRecentTracks'),
      'user'   => array('joejiko','arielbambino','jasontylerxx','sleepyluna','trustxme'),
      'format' => array('json'),
      'page'   => 1
    );
  }

  protected function _setQueryString($options=array(), $format="json")
  {
    /*
      method => user.getRecentTracks
      user => joejiko
      page => page# (optional)
    */
    if(array_key_exists("method", $options)
      && in_array($options["method"], $this->_allowed["method"])
      && array_key_exists("user", $options)
      && in_array($options["user"], $this->_allowed["user"]))
    {
      $params["method"] = $options["method"];
      $params["user"] = $options["user"];

      if (array_key_exists("page", $options))
      {
        $options["page"] = preg_replace('[\D]', '', $options["page"]);
        $params["page"] = $options["page"];
      }

      if($format && in_array($format, $this->_allowed["format"]))
      {
        $params["format"] = $format;
      }

      $queryString = '';
      // set up parameters
      foreach ($params as $key => $value) {
          $queryString .= "$key=" . urlencode($value) . "&";
      }

      return $queryString;
    }
    else
    {
      return;
    }
  }

  public function __construct($options)
  {
    $this->_setAllowed();
    $this->_queryString = $this->_setQueryString($options);
    $this->_buildUrl();
  }

  public function __destruct()
  {
    // do nothing
    // return $this->output();
  }

  public function outputJSON()
  {
    return $this->output();
  }

  public function output($type=NULL)
  {
    $content = file_get_contents($this->_url);

    // format for tracker
    if(!is_null($type))
    {
      $tracksArr = array();
      if($type=="tracker")
      {
        $content = json_decode($content, true);
        $stats = array(
          'page' => $content["@attr"]["page"],
          'totalPages' => $content["@attr"]["totalPages"],
          'total' => $content["@attr"]["total"]
        );
        foreach($content["recenttracks"]["track"] as $track)
        {
          if(array_key_exists("@attr", $track))
          {
            array_push($tracksArr, array(
              "img" => $track["image"][1]["#text"],
              "name" => $track["name"],
              "artist" => $track["artist"]["#text"],
              "type" => "now playing"
            ));
          }
          else
          {
            // normal track
            array_push($tracksArr, array(
              "artist" => $track["artist"]["#text"],
              "date" => $track["date"]["#text"],
              "name" => $track["name"],
              "type" => "track",
              "url" => $track["url"]
            ));
          }
        }
      }
      return json_encode(array("tracks" => $tracksArr, "stats" => $stats));
    }
    else
    {
      return $content;
    }
  }
}