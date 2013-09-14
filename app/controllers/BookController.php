<?php
class BookController extends BaseController
{
  public function getIndex()
  {
    return View::make('pages.books.index');
  }

  public function getSearch()
  {
    return View::make('pages.books.search');
  }
}