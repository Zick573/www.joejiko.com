<?php

class ThoughtController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function getIndex()
  {
    $thoughts = Post::orderBy('created_at', 'desc')->get(); // where type=thoughts
    return View::make('posts.thoughts.index')->with(array('posts' => $thoughts));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function getCreate()
  {
    if(Auth::check()){
      return View::make('posts.thoughts.create');
    }
    return Redirect::to('thoughts');
  }

  public function postCreate()
  {
    if(!Input::has('post_content') || !Auth::check())
    {
      return Redirect::to('thoughts');
    }

    $content = Input::get('post_content');

    $thought = new Post;
    $thought->user_id = $this->user->id;
    $thought->user_name = $this->user->name;
    $thought->content = $content;
    $thought->type = "thought";
    $thought->save();

    return Redirect::to('thoughts');
  }

  public function missingMethod($parameters)
  {
    // missing
    return Redirect::to('thoughts');
  }

}