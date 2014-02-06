<?php
class QuestionController extends DefaultController {
  public function index()
  {
    $questions = Question::where('status', '=', '1')
      ->take(25)
      ->orderBy('id', 'desc')
      ->get();
    return View::make('questions')
      ->withQuestions($questions);
  }

  public function store()
  {
    return 'store';
  }

  public function create()
  {
    return View::make('questions.ask');
  }

  public function show(\Question $question)
  {
    return View::make('question')
      ->withQuestion($question);
  }

  public function missingMethod($parameters=[])
  {
    // missing
    return Redirect::to('questions');
  }
}