<?php namespace App\Controllers\Admin;

use App\Models\Post;
use Input, Redirect, Str;

class PostController extends \BaseController {
  public function index()
  {
    return View::make('admin.posts.index')->with('posts', Post::all());
  }

  public function show($id)
  {
    return View::make('admin.posts.show')->with('post', Post::find($id));
  }

  public function create()
  {
    return View::make('admin.posts.create');
  }

  public function store()
  {
    $post = new Post;
    $post->title = Input::get('title');
    $post->save();

    return Redirect::route('admin.posts.edit', $post->id);
  }

  public function edit($id)
  {
    return View::make('admin.posts.edit')->with('post', Post::find($id));
  }

  public function update($id)
  {
    return 'Update';
  }

  public function destroy($id)
  {
    return 'Destroy';
  }

}
