<?php

use Jiko\Repo\Post\PostInterface;

class ContentController extends BaseController {
  protected $layout = 'layout';

  protected $post;

  public function __construct(PostInterface $post)
  {
    $this->post = $post;
  }

  /**
   * paginated posts
   * GET /
   */
  public function home()
  {
    $page = Input::get('page', 1);

    $perPage = 3;

    $pagiData = $this->post->byPage($page, $perPage);

    $posts = Paginator::make($pagiData->items, $pagiData->totalItems, $perPage);

    $this->layout->content = View::make('home')->with('posts', $posts);
  }

  /**
   * Single post
   * GET /{slug}
   */
  public function post($slug)
  {
    $post = $this->post->bySlug($slug);

    if(!$post) App:abort(404);

    $this->layout->content = View::make('post')->with('post', $post);
  }
}