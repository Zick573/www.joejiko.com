<?php
class TestController extends DefaultController
{
  public function getIndex($label)
  {
    if('connected' == $label):
      return View::make('test.modal.connected');
    endif;

    return Redirect::to('/');
  }
}