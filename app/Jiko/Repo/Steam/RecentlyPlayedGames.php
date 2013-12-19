<?php namespace Jiko\Repo\Steam;
/**
 * @todo 1. check for last update
 * 2. get data from table
 *          or from steam
 * 3. update data in table
 * 4. output
 */
class RecentlyPlayedGames implements SteamInterface extends Steam
{

  protected $recentlyPlayedGames;

  public function __construct(Model $recentlyPlayedGames)
  {
    $this->recentlyPlayedGames = $recentlyPlayedGames;
  }

  public function all() {
    // $recentlyPlayedGames::all();
  }

  public function byId($id) {

    // check last update

    // get results
  }
}