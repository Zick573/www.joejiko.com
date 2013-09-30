<?php
class ArtController extends DefaultController {
  public function getIndex()
  {
    return View::make('artwork');
  }
}