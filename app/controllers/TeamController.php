<?php
class TeamController extends DefaultController {
  public function getIndex()
  {
    $this->layout->content = View::make('team.index');
  }

  public function getJoin()
  {
    $this->layout->content = View::make('team.join');
  }

  public function missingMethod($parameters=[])
  {
    return Redirect::to('home');
  }
}