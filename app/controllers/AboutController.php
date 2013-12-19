<?php
class AboutController extends DefaultController
{
  public function me()
  {
    return View::make('pages.about');
  }

  public function privacy()
  {
    return View::make('pages.about.privacy');
  }

  public function resume()
  {
    return View::make('pages.resume');
  }
}