<?php namespace Admin;
use Input, View, Question, Question\Status as Status;
class QuestionController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('admin.pages.questions.index')->with(array(
      'questions' =>Question::all(),
      'questions_answered' => Question::where('status', '=', '1')->get(),
      'questions_unanswered' => Question::where('status', '=', '0')->get(),
      'questions_ignored' => Question::where('status', '=', '2')->get(),
      'questions_rejected' => Question::where('status', '=', '3')->get(),
      'questions_status' => Status::all()
    ));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
    $question = Question::find(Input::get('id'));
    if(!$question):
      return View::make('admin.pages.questions.updated')->with('result', array('message' => 'fail to find ID '.Input::get('id')));
    endif;

    if(Input::get('response')):
    $affectedRows = Question::where('id', Input::get('id'))
      ->update(array(
        'response' => Input::get('response'),
        'status' => 1
      ));
    endif;

    if(Input::get('status')):
      $affectedRows = Question::where('id', Input::get('id'))
        ->update(array(
          'status' => Input::get('status')
        ));
    endif;

    return View::make('admin.pages.questions.updated')->with('result', $affectedRows);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}