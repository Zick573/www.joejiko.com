<?php namespace Jiko\Repo\Steam;

class Steam {

  /**
   * stores steam methods, keys, ids
   *
   * @var array
   */
  protected $config;

  public function __construct($config=[])
  {
    $this->config = $config;
  }

  /**
   * JSON response from steam
   *
   * @param  boolean $args       [description]
   * @param  string  $methodName [description]
   * @return JSON array
   */
  public function get($args=false, $methodName="RecentlyPlayedGames")
  {
    if(!$args) {
      $args = [
        'steamid' => $this->config['ids']['me']
      ];
    }

    try {

      if(!is_array($args) || !array_key_exists('steamid', $args)) {

        throw new SteamException('Missing Steam ID');

      }

      if(!$endpoint = $this->config['methods'][$methodName]) {
        throw new SteamException('Missing endpoint in Config');
      }

    } catch (SteamException $e) {

      return $e->getMessage();

    }

    $query_string = http_build_query(array_merge(
      [
        'key' => $this->config['api_key'],
        'format' => 'json'
      ],
      $args
    ));

    $url = sprintf("%s?%s", $endpoint, $query_string);

    $recently_played_games = file_get_contents($url);

    return $recently_played_games;

  }

}