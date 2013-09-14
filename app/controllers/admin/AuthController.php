<?php namespace App\Controllers\Admin;

  use Auth, BaseController, Form, Input, Redirect, View;

  class AuthController extends \BaseController {
    public function getLogin()
    {
      return View::make('admin.auth.login');
    }

    public function postLogin()
    {
      $credentials = array(
        'email' => Input::get('email'),
        'password' => Input::get('password')
      );

      try
      {
        $user = Auth::attempt($credentials, true);

        if($user)
        {
          return Redirect::route('admin.pages.index');
        }
        catch(\Exception $e)
        {
          return Redirect::route('admin.login')->withErrors( array('login' => $e->getMessage() ));
        }
      }
    }

    public function getLogout()
    {
      Auth::logout();

      return Redirect::route('admin.login');
    }
  }