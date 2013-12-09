@extends('layouts.admin.master')
@section('page.title')
  Admin\Content
@stop
@section('content')
  <h1>
    Content <a href="/admin/content/create" class="btn btn-success"><i class="icon-plus-sign"></i> Add New</a>
  </h1>
  <form>
    <select>
      <option>Question</option>
      <option>Thought</option>
      <option>Image</option>
    </select>
    <textarea></textarea>
    <button type="submit">create</button>
@stop