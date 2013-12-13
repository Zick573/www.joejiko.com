<?php namespace Jiko\Repo\Page;

use Jiko\Repo\RepoAbstract;
use Illuminate\Database\Eloquent\Model;

class EloquentPage extends RepoAbstract implements PageInterface {
  protected $page;
  protected $tag;

  public function __construct(Model $page, TagInterface $tag)
  {
    $this->page = $page;
  }

  public function byId($id)
  {
    return $this->page->with('status')
      ->with('author')
      ->with('tags')
      ->where('id', $id)
      ->first();
  }

  public function bySlug($slug)
  {

  }

  public function byTag($tag, $page=1, $limit=10)
  {

  }

  public function byPage($page=1, $limit=10, $all=false)
  {
    $result = [
      'page' => $page,
      'limit' => $limit,
      'totalItems' => 0,
      'items' => []
    ];

    $query = $this->post->with('status')
      ->with('author')
      ->with('tags')
      ->orderBy('created_at', 'desc');

    if(!$all) $query->qhere('status_id', 1);

    $posts = $query->skip($limit * ($page-1))
      ->take($limit)
      ->get();

    $result['totalItems'] = $this->totalPosts($all);
    $result['items'] = $posts->all();

    return (object) $result;
  }

  public function create(array $data)
  {
    $post = $this->post->create([
      'user_id' => $data['user_id'],
      'status_id' => $data['status_id'],
      'title' => $data['title'],
      'slug' => $this->slug($data['title']),
      'excerpt' => $data['excerpt'],
      'content' => $data['content']
    ]);

    if(!$post) return false;

    $this->syncTags($post, $data['tags']);

    return true;
  }

  public function update(array $data)
  {
    $post = $this->post->find($data['id']);

    $post->user_id = $data['user_id'];
    $post->status_id = $data['status_id'];
    $post->title = $data['title'];
    $post->slug = $this->slug($data['title']);
    $post->excerpt = $data['excerpt'];
    $post->content = $data['content'];
    $post->save();

    $this->syncTags($post, $data['tags']);

    return true;
  }

  public function syncTags(Model $post, array $tags)
  {
    $found = $this->tag->findOrCreate($tags);

    $tagIds = [];

    foreach($found as $tag):
      $tagIds[] = $tag->id;
    endforeach;

    $post->tags()->sync($tagIds);
  }

  public function totalPosts($all = false)
  {
    if(!$all) return $this->post->where('status_id', 1)->count();

    return $this->post->count();
  }

  public function totalByTag($tag)
  {
    return $this->tag->bySlug($tag)
      ->posts()
      ->where('status_id', 1)
      ->count();
  }
}