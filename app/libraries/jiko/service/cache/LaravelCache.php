<?php namespace Jiko\Service\Cache;

use Illuminate\Cache\CacheManager;

class LaravelCache implements CacheInterface {
  protected $cache;
  protected $cachekey;
  protected $minutes;

  public function __construct(CacheManager $cache, $cachekey, $minutes=null)
  {
    $this->cache = $cache;
    $this->cachekey = $cachekey;
    $this->minutes = $minutes;
  }

  public function get($key)
  {
    return $this->cache->section($this->cachekey)->get($key);
  }

  public function put($key, $value, $minutes=null)
  {
    if( is_null($minutes) )
    {
      $minutes = $this->minutes;
    }

    return $this->cache->section($this->cachekey)->put($key, $value, $minutes);
  }

  public function putPaginated($currentPage, $perPage, $totalItems, $items, $key, $minutes=null)
  {
    $cached = new \StdClass;

    $cached->currentPage = $currentPage;
    $cached->items = $items;
    $cached->totalItems = $totalItems;
    $cached->perPage = $perPage;

    $this->put($key, $cached, $minutes);

    return $cached;
  }

  public function has($key)
  {
    return $this->cache->section($this->cachekey)->has($key);
  }
}