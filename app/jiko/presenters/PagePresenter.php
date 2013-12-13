<?php namespace Jiko\Presenters;

class PagePresenter extends Presenter
{
  public function layout()
  {
    return json_decode($this->resource->layout);
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