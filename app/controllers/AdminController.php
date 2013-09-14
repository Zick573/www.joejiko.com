<?php
class AdminController extends BaseController {
  public function getIndex()
  {
    return View::make('admin.index')->with(array('user' => $this->user));
  }

  public function getQuestions()
  {
    $questions = Question::all();
    return View::make('admin.pages.questions.index')->with(array('questions' => $questions));
  }

  public function missingMethod($parameters)
  {
    return Redirect::to('home');
  }
}