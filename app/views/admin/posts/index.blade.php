@extends('layouts.master')
@section('content')
  <h1>
    Posts <a href="{{ URL::route('admin.posts.create') }}" class="btn btn-success"><i class="icon-plus-sign"></i> Add new page</a>
  </h1>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Title</th>
      <th>Content</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($posts as $post)
    <tr>
      <td>{{ $post->id }}</td>
      <td>{{ $post->title }}</td>
      <td>{{ $post->content }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
@stop