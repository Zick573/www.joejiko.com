<?php
class BookController extends DefaultController
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