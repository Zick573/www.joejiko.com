@extends('layouts.admin.master')
@section('page.title')
  Admin\Content
@stop
@section('content')
  <h1>
    Content <a href="/admin/content/create" class="btn btn-success"><i class="icon-plus-sign"></i> Add New</a>
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