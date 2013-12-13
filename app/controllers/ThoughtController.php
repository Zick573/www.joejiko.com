<?php

class ThoughtController extends DefaultController {

  public function __construct()
  {
    $this->beforeFilter('auth.admin', array('only' => array(
      'getCreate',
      'postCreate'
    )));
  }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  // public function content() {
  //   return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $this->content)
  // }
  public function getIndex()
  {
    // where type=thoughts
    // $posts = $this->post->orderBy('created_at', 'desc')->get();
    return View::make('posts.thoughts.index')->with(array(
      'posts' => Post::thoughts()->recent()->get()
    ));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function getCreate()
  {
    return View::make('posts.thoughts.create');
  }

  public function postCreate()
  {
    if(!Input::has('post_content')):
      return Redirect::to('thoughts');
    endif;

    $content = Input::get('post_content');
    Eloquent::unguard();
    $post = new Post(array(
      'user_id' => Auth::user()->id,
      'content' => $content,
      //'title' => truncate($content, 70),
      //'category' => $category_id,
      //'excerpt' => truncate($content, 160),
      'status' => 'publish',
      //'comment_status' => 'open',
      // 'name' => $slug,
      // 'guid' => $raw_permalink
      'type' => 'thought'
    ));
    $post->save();
    if(!$post):
      die('failed..');
    endif;

    return Redirect::to('thoughts')->with(array('result' => $post));
  }

  public function missingMethod($method, $parameters=[])
  {
    // missing
    return Redirect::to('thoughts')->flash('bad_request', $parameters);
  }

}