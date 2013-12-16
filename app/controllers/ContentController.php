<?php

use Jiko\Repo\Page\PageInterface;

class ContentController extends BaseController {

  protected $layout = 'page.layout';

  protected $page;

  public function __construct(PageInterface $page)
  {
    $this->page = $page;
  }

  /**
   * paginated posts
   * GET /
   */
  public function home()
  {
    // $page = Input::get('page', 1);

    // $perPage = 3;

    // $pagiData = $this->post->byPage($page, $perPage);

    // $posts = Paginator::make($pagiData->items, $pagiData->totalItems, $perPage);

    // $this->layout->content = View::make('home')->with('posts', $posts);

    return "nothing";
  }

  /**
   * Single page
   * GET /{slug}
   */
  public function page($slug)
  {
    $page = $this->page->bySlug($slug);

    if(!$page) App:abort(404);

    $this->layout->content = View::make('page')->with([
      'layout' => $page->layout
    ]);
  }

}