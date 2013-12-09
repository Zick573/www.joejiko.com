<?php namespace Jiko\Repo\Tag;

use Jiko\Repo\RepoAbstract;
use Jiko\Service\Cache\CacheInterface;
use Illuminate\Database\Eloquent\Model;

class EloquentTag extends RepoAbstract implements TagInterface {

  protected $tag;
  protected $cache;

  public function __construct(Model $tag, CacheInterface $cache)
  {
    $this->tag = $tag;
    $this->cache = $cache;
  }

  public function findOrCreate(array $tags)
  {
    $foundTags = $this->tag->whereIn('tag', $tags)->get();

    $returnTags = [];

    if($foundTags):
      foreach($foundTags as $tag):
        $pos = array_search($tag->tag, $tags);

        // add returned tags to array
        if($post !== false)
        {
          $returnTags[] = $tag;
          unset($tags[$pos]);
        }
      endforeach;
    endif;

    foreach($tags as $tag):
      $returnTags[] = $this->tag->create([
          'tag' => $tag,
          'slug' => $this->slug($tag)
        ]);
    endforeach;

    return $returnTags;
  }
}