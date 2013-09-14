<?php

class WebController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$urls = Material::where('user_id', Auth::user()->id)->get();

		return Response::json(array(
			'error' => false,
			'urls' > $urls->toArray()
		), 200);
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
		$material = new Material;
		$material->url = Request::get('url');
		$material->description = Request::get('description');
		$material->user_id = Auth::user()->id;

		// validation and filtering

		$material->save();

		return Response::json(array(
			'error'=>false,
			'materials' => $material->toArray()
		), 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// make sure current user owns the requested resource
		$url = Material::where('user_id', Auth::user()->id)
			->where('id', $id)
			->take(1)
			->get();

		return Response::json(array(
			'error' => false,
			'urls' => $url->toArray()
		), 200);
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
		$material = Material::where('user_id', Auth::user()->id)->find($id);

		if ( Request::get('url') )
		{
			$material->url = Request::get('url');
		}

		if ( Request::get('description') )
		{
			$material->description = Request::get('description');
		}

		$material->save()

		return Response::json(array(
			'error' => false,
			'message' => 'url updated'
		), 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$material = Material::where('user_id', Auth::user()->id)->find($id);
		$material->delete();

		return Response::json(array(
			'error' => false,
			'message' => 'url deleted'
		), 200);
	}

}