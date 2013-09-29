<?php

class ThoughtController extends BaseController {

  protected $post;
  protected $user;

  public function __construct(Post $post, User $user)
  {
    parent::__construct();

    $this->post = $post;
    $this->user = $user;
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function getIndex()
  {
    // where type=thoughts
    $posts = $this->post->orderBy('created_at', 'desc')->get();
    return View::make('posts.thoughts.index')->with(array('posts' => $posts));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function getCreate()
  {
    if(!$this->user):
      return Redirect::to('thoughts');
    endif;

    return View::make('posts.thoughts.create');
  }

  public function postCreate()
  {
    // Auth
    if(!Input::has('post_content') || !$this->user):
      return Redirect::to('thoughts');
    endif;

    $content = Input::get('post_content');

    $post = new $this->post;
    $post->user_id = $this->user->id;
    $post->user_name = $this->user->name;
    $post->content = $content;
    $post->type = "thought";
    $result = $post->save();

    return Redirect::to('thoughts')->with(array('result' => $result));
  }

  public function missingMethod($parameters)
  {
    // missing
    return Redirect::to('thoughts')->flash('bad_request', $parameters);
  }

}