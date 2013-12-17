<?php namespace Jiko\Repo\Post;

use Jiko\Service\Cache\CacheInterface;

class CacheDecorator extends AbstractPostDecorator {
  protected $cache;

  public function __construct(PostInterface $nextPost, CacheInterface $cache)
  {
    parent::__construct($nextPost);
    $this->cache = $cache;
  }

  public function byId($id)
  {
    $key = md5('id.'.$id);

    if($this->cache->has($key)) return $this->cache->get($key);

    $post = $this->nextPost->byId($id);

    $this->cache->put($key, $post);

    return $post;
  }

  public function byPage($page=1, $limit=10, $all=false)
  {
    $allkey = ($all) ? '.all' : '';
    $key = md5('page.'.$page.'.'.$limit.$allkey);

    if($this->cache->has($key)) return $this->cache->get($key);

    $paginated = $this->nextPost->byPage($page, $limit);

    $this->cache->put($key, $paginated);

    return $paginated;
  }

  public function bySlug($slug)
  {
    $key = md5('slug.'.$slug);

    if($this->cache->has($key)) return $this->cache->get($key);

    $post = $this->nextPost->bySlug($slug);

    $this->cache->put($key, $post);

    return $post;
  }

  public function byTag($tag, $page=1, $limit=10)
  {
    $key = md5('tag.'.$tag.'.'.$page.'.'.$limit);

    if($this->cache->has($key)) return $this->cache->get($key);

    $paginated = $this->nextPost->byTag($tag, $page, $limit);

    $this->cache->put($key, $paginated);

    return $paginated;

  }
}