<?php

class HomeController extends DefaultController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	public function getIndex()
	{
		$viewData = array(
			'title' => 'hello jiko',
		);
		return View::make('home', $viewData);
	}

	public function getAll($page)
	{
		// 404
		return View::make('errors.not-found')->with(array('request'=>$page));
	}

	public function getAbout()
	{
		return View::make('pages.about');
	}

	public function getGaming()
	{
		return View::make('gaming');
	}

	public function getMore()
	{
		return View::make('pages.more');
	}

	public function getMusic()
	{
    $tracks = Music::all();
		return View::make('pages.music');
	}

	public function getResume()
	{
		return View::make('pages.resume');
	}

	public function getSubscribe()
	{
		return View::make('pages.subscribe');
	}

	public function getSupport()
	{
		return View::make('pages.support');
	}

	public function getTeamJiko()
	{
		return View::make('team.join');
	}

	public function getWeb()
	{
		return View::make('web.clips');
	}

	public function getLabs()
	{
		return View::make('pages.labs');
	}

  public function missingMethod($parameters)
  {
    // missing
    return Redirect::to('home');
  }

}