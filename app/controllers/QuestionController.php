<?php
class QuestionController extends DefaultController {
  public function getIndex()
  {
    $questions = Question::where('status', '=', '1')
      ->take(25)
      ->orderBy('id', 'desc')
      ->get();
    return View::make('questions')->with('questions', $questions);
  }

  public function getAsk()
  {
    return View::make('questions.ask');
  }

  public function getOne($id)
  {
    $question = Question::find($id);
    return View::make('question')->with('question', $question);
  }

  public function missingMethod($method, $parameters=[])
  {
    // missing
    return Redirect::to('questions');
  }
}