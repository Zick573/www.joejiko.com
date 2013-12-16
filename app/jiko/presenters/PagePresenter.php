<?php namespace Jiko\Presenters;

use phpQuery;

class PagePresenter extends Presenter
{
  protected static $container = '<div class="container"></div>';
  protected static $row = '<div class="row"></div>';

  public function layout()
  {
    $layout = json_decode($this->resource->layout);
    return
  }

  public function row()
  {

  }

  public function column()
  {

  }

  public function block()
  {

  }
}