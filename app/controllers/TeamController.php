<?php
class TeamController extends DefaultController {
  public function getIndex()
  {
    return View::make('team.index');
  }

  public function getJoin()
  {
    return View::make('team.join');
  }

  public function missingMethod($parameters=[])
  {
    return Redirect::to('home');
  }
}