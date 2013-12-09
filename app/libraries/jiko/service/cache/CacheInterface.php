<?php namespace Jiko\Service\Cache;

interface CacheInterface {

  /**
   * retrieve data from cache
   *
   * @param  string  cache item key
   * @return mixed   PHP data result of cache
   */
  public function get($key);

  /**
   * add data to the cache
   *
   * @param  string  cache item key
   * @param  mixed   the data to store
   * @param  integer the number of minutes to store the item
   * @return mixed   $value variable returned for convenience
   */
  public function put($key, $value, $minutes=null);

  /**
   * add data to the cache
   * taking pagination data into account
   *
   * @param  [type] $currentPage [description]
   * @param  [type] $perPage     [description]
   * @param  [type] $totalItems  [description]
   * @param  [type] $items       [description]
   * @param  [type] $key         [description]
   * @param  [type] $minutes     [description]
   * @return [type]              [description]
   */
  public function putPaginated($currentPage, $perPage, $totalItems, $items, $key, $minutes=null);

  /**
   * test if item exists in cache
   * only returns true if exists and is not expired
   *
   * @param  [type]  $key [description]
   * @return boolean      [description]
   */
  public function has($key);
}