<?php
class PostController extends DefaultController {

  public function __construct(PostRepositoryInterface $posts)
  {
    $this->posts = $posts;
  }

  public function getIndex()
  {
    $posts = $this->posts->all();

    return View::make('posts.index', compact('posts'));
  }

}