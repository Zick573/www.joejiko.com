<?php
class DbPostRepository implements PostRepositoryInterface
{
  public function all()
  {
    return Post::all()->toArray();
  }
}