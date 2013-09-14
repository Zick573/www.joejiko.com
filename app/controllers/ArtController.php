<?php
class ArtController extends BaseController {
  public function getIndex()
  {
    return View::make('artwork');
  }
}