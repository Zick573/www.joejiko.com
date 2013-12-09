<?php namespace Jiko\Repo\Steam;

interface SteamInterface {

  /**
   * get steam games for all Ids
   * @return array
   */
  public function all();

  /**
   * get steam games for specific user
   */
  public function byId();
}