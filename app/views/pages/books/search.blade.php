@extends('layouts.master')
@section('page.title')
  Search for books
@stop
@section('content')
<h1>Books</h1>
<h2>Search (freebase)</h2>
<div class="books">
  <div class="books-search">
    <input data-part="query" type="text" name="q">
    <button data-part="search">search</button>
  </div>

  <h3>Results</h3>
  <div data-part="result" class="books-search-result"></div>
</div>
@stop