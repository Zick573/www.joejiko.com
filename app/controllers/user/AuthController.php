<?php namespace Controllers\User;

  use Jiko\OAuth\OAuthUserProviderInterface;

  use Auth, BaseController, Form, Input, Redirect, View;

  class AuthController extends \BaseController {
    protected $OAuth;
    protected $user;

    public function __construct(OAuthUserProviderInterface $oauth){
      $this->OAuth = $OAuth;

      if(Auth::check())
      {
        // the user is logged in
        $this->user = Auth::user();
      }
    }

    public function check(){
      try {
        $auth = $this->OAuth;
        $connected_providers = $auth->getConnectedProviders();
      } catch(Exception $e) {
        Redirect::to('home')->withErrors(array('connect' => $e->getMessage()));
      }

      if( count($connected_providers) ) {
        try {
          $provider = head($connected_providers);
          $$provider = $oauth->getAdapter($provider);
          $profile = $$provider->getUserProfile();
          $restore = self::restoreSession($provider, $profile->identifier);
          if( !is_bool($restore)) {
            throw new Exception($restore);
          }
        } catch(Exception $e) {
          Redirect::to('home')->withErrors(array('connect' => $e->getMessage()));
        }
        return Redirect::to('home');
      }
    }

    public function restoreSession($provider, $id) {
      try {
        $oauth = $this->hybridAuth;
        $uid = Models\User\Connection::find($id)->where('provider', $provider)->pluck('user_id');
        $auth_session_data = Models\Auth\Session::where('user_id', $uid)->pluck('hybridauth_session');

        $oauth->restoreSessionData( $auth_session_data );
        $this->hybridAuth = $oauth;

        if( count($oauth->getConnectedProviders()) ) {
          return true;
        }

        return false;

      } catch( Exception $e ){

        return $e->getMessage();

      }
    }

    /** views **/
    public function getLogin()
    {
      if(!$this->user){
        return View::make('user.connect');
      }

      return View::make('user.connected');
    }

    public function getLogout()
    {
      Auth::logout();
      return Redirect::route('user.disconnect');
    }
  }