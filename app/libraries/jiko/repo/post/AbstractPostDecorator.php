<?php namespace Jiko\Repo\Post;

abstract class AbstractPostDecorator implements PostInterface {
  protected $nextPost;

  public function __construct(PostInterface $nextPost)
  {
    $this->nextPost = $nextPost;
  }

  public function byId($id)
  {
    return $this->nextPost->byId($id);
  }

  public function byPage($page=1, $limit=10, $all=false)
  {
    return $this->nextPost->byPage($page, $limit, $all);
  }

  public function bySlug($slug)
  {
    return $this->nextPost->bySlug($slug);
  }

  public function byTag($tag, $page=1, $limit=10)
  {
    return $this->nextPost->byTag($tag, $page, $limit);
  }

  public function create(array $data)
  {
    return $this->nextPost->create($data);
  }

  public function update(array $data)
  {
    return $this->nextPost->update($data);
  }
}