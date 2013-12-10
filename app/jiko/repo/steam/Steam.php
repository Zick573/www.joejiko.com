<?php namespace Jiko\Repo\Steam;

use Jiko\Api\SteamApi;

class Steam implements SteamInterface
{
  protected $games;

  public function __construct(SteamApi $games) {
    $this->games = $games;
  }

  public function all()
  {
    return $this->games->all();
  }

  /**
   * get specific game
   *
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function byId($id)
  {
    return $this->games->find($id);
  }
}