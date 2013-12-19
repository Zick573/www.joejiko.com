<?php namespace Jiko\Repo\Steam;

interface SteamInterface {

  /**
   * get steam games for all steam users in config
   * @return array
   */
  public function all();

  /**
   * results from a single user
   */
  public function find($id);

  public function get();
}