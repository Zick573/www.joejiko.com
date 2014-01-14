<?php namespace Jiko\Repo\User;

use Jiko\Repo\RepoAbstract;
use Illuminate\Database\Eloquent\Model;

class EloquentUser extends RepoAbstract implements UserInterface {

  protected $user;

  public function __construct(Model $user, TagInterface $tag)
  {
    $this->user = $user;
    $this->tag = $tag;
  }

  public function byId($id)
  {
    return $this->user->with('status')
      ->with('author')
      ->with('tags')
      ->where('id', $id)
      ->first();
  }

  public function create(array $data)
  {
    $user = $this->user->create([
      'user_id' => $data['user_id'],
      'status_id' => $data['status_id'],
      'title' => $data['title'],
      'slug' => $this->slug($data['title']),
      'excerpt' => $data['excerpt'],
      'content' => $data['content']
    ]);

    if(!$user) return false;

    return true;
  }

  public function update(array $data)
  {
    $user = $this->user->find($data['id']);

    $user->user_id = $data['user_id'];
    $user->status_id = $data['status_id'];
    $user->title = $data['title'];
    $user->slug = $this->slug($data['title']);
    $user->excerpt = $data['excerpt'];
    $user->content = $data['content'];
    $user->save();

    $this->syncTags($user, $data['tags']);

    return true;
  }

  public function totalUsers($all = false)
  {
    if(!$all) return $this->user->where('status_id', 1)->count();

    return $this->user->count();
  }
}