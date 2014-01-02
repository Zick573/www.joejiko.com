<?php
class UserController extends DefaultController {
  public function getIndex()
  {
    if(!Auth::check()):
      return Redirect::to('user/connect')->with([
        'message' => 'Please connect to continue..'
      ]);
    endif;

    return View::make('user.index');
  }

  public function getInfo()
  {
    if(!Auth::check()):
      return Redirect::to('user/connect')->with([
        'message' => 'Please connect to continue..'
      ]);
    endif;

    return View::make('user.info');
  }

  public function getMissingInfo()
  {
    return View::make('user.connected.missing_required');
  }

  public function getConnected()
  {
    if(!Auth::check()):
      // redirect to referring page (with error)
      if(Session::has('connected_from_url')):
        return Redirect::to(Session::get('connected_from_url'))->with('user_connect_status', "failed");
      endif;

      // fall back on static connect page (with error)
      return Redirect::to('user/connect')->with('user_connect_status', "failed");
    endif;

    // prompt for email
    if(!isset($this->user->email) || is_null($this->user->email)):
      Redirect::to('user/connected/missing-required-info');
    endif;

    // redirect to referring page (success)
    if(Session::has('connected_from_url')):
      return Redirect::to(Session::get('connected_from_url'))->with('user_connected', true);
    endif;

    return View::make('home')->with('user_connected', true);
  }

  public function doRegisterEmail()
  {
    return View::make('user.register-email-disabled');
  }

  public function doConnectEmail()
  {
    if(!Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('passwd')), true)):
      return View::make('user.connect')->with([
        'error' => [
          'INVALID_CREDS' => 'Invalid credentials. Try again, maybe?'
        ]
      ]);
    endif;
    return self::getConnected();
  }

  public function OAuthValidateProvider($provider)
  {
    $providers = Config::get('hybridauth.providers');

    if(!array_key_exists(ucfirst($provider), $providers)):
      throw new Exception('Auth provider not found in config.');
    endif;

    if(!$providers[ucfirst($provider)]["enabled"]):
      throw new Exception("Connecting with $provider is disabled");
    endif;

    return true;
  }

  /**
   * @param Hybrid_Auth $oauth   [description]
   * @param UserSession $session [description]
   */
  public function OAuthRestore(Hybrid_Auth $oauth, UserSession $session)
  {
    $session = $session->where('user_id', Auth::user()->id)->first()->pluck('session');
    $oauth->restoreSessionData( $session );
    return count($oauth->getConnectedProviders());
  }

  /**
   * [OAuthRegister description]
   * @param Hybrid_Provider_Adapter $provider [description]
   * @param [type]                  $info     [description]
   */
  public function OAuthRegister(Hybrid_Provider_Adapter $provider, Hybrid_User_Profile $profile)
  {
      # make sure email isn't already registered
      # by a different provider
      if(!$user = User::where('email', $profile->email)->first()
        && trim($profile->email) !== ''):
        # insert user
        $user = new User;
        $user->email = $profile->email;
        $user->name = $profile->displayName;
        $user->save();
      endif;

    // DB::transaction(function() use ($provider, $profile) {
      $provider_id = AuthProvider::where('name', strtolower($provider->id))
        ->firstOrFail()
        ->pluck('id');

      # insert provider connection info
      $connection = DB::table('user_connections')->insert([
        'user_id' => $user->id,
        'provider_name' => strtolower($provider->id),
        'provider_uid' => $profile->identifier
      ]);

      # insert user info from provider
      $info = DB::table('user_info')->insert([
        'user_id' => $user->id,
        'provider_id' => $provider_id,
        'profile_url' => $profile->profileURL,
        // 'website_url' => $profile->websiteURL,
        'photo_url' => $profile->photoURL,
        'display_name' => $profile->displayName,
        'description' => $profile->description,
        'first_name' => $profile->firstName,
        'last_name' => $profile->lastName,
        'gender' => $profile->gender,
        'language' => $profile->language,
        'age' => $profile->age,
        'birth_day' => $profile->birthDay,
        'birth_month' => $profile->birthMonth,
        'birth_year' => $profile->birthYear,
        'email' => $profile->email,
        'email_verified' => $profile->emailVerified,
        'phone' => $profile->phone,
        'address' => $profile->address,
        'country' => $profile->country,
        'region' => $profile->region,
        'city' => $profile->city,
        'zip' => $profile->zip
      ]);
    // }); // END transaction
    Session::put('oauth_register', true);
    return $user->id;

  }

  public function OAuthAttempt(Hybrid_Auth $oauth, $credentials)
  {
    self::OAuthValidateProvider($credentials);

    # actual OAuth attempt
    $provider = $oauth->authenticate($credentials);
    if(!$provider instanceof Hybrid_Provider_Adapter):
      throw new Exception('Not a valid oauth provider.');
    endif;

    $profile = $provider->getUserProfile();
    $credentials = [
      'provider_name' => strtolower($provider->id),
      'provider_uid' => $profile->identifier
    ];
    $uid = UserConnection::where('provider_name', $credentials['provider_name'])
      ->where('provider_uid', $credentials['provider_uid'])
      ->pluck('user_id');

    # attempt to register the OAuth user
    if(!$uid):
      $uid = self::OAuthRegister($provider, $profile);
    endif;

    # log in with site ID
    Auth::loginUsingId($uid);
    if(!Auth::check()):
      throw new Exception('Connection failed.. or something');
    endif;

    // store session
    $authsession = DB::table('auth_sessions')->insert([
      'user_id' => Auth::user()->id,
      'session' => $oauth->getSessionData()
    ]);

    Session::put('oauth_loginusingid', true);
    return Auth::user();
  }

  /**
   * [doConnect description]
   * @param  [type] $method [description]
   * @return [type]         [description]
   */
  public function doConnect($method)
  {
    # oauth callback
    if ("provider" == $method):
      try {
        Hybrid_Endpoint::process();
        return;
      } catch (Exception $e) {
        // redirect back to http://$_SERVER[HTTP_HOST]/user/connect
        return Redirect::route('hybridauth')->with('flash_notice', $e->getMessage());
      }
    endif;

    # oauth response
    try {
      $hybridauth = new Hybrid_Auth(Config::get('hybridauth'));
      $user = self::OAuthAttempt($hybridauth, $method);
      return self::getConnected();

    } catch(Exception $e) {

      return View::make('user.connect')->with(['error' => $e->getMessage()]);

    }
  }

  /**
   * /user/connect
   * Show connect options or redirect
   */
  public function getConnect()
  {
    if(!Auth::check()):
      return View::make('user.connect');
    endif;

    return Redirect::to('user/connected');
  }

  /**
   * /user/disconnect
   * disconnect/logout user and redirect
   */
  public function getDisconnect()
  {
    try {

      # oauth cleanup
      $oauth = new Hybrid_Auth(Config::get('hybridauth'));
      if(count($oauth->getConnectedProviders()) > 0):

        try {

          // save session
          $session = $oauth->getSessionData();
          $store = AuthSession::create([
            'user_id' => Auth::user()->id,
            'session' => $session,
            'type' => 'hybridauth'
          ]);

        } catch (Exception $e) {
          // do nothing
        }

        $oauth->logoutAllProviders();

      endif;

      if(Auth::guest()):
        throw new Exception('Already disconnected. Would you like to connect?');
      endif;

      Auth::logout();
    } catch (Exception $e) {
      return Redirect::route('home')->with('flash_notice', $e->getMessage());
    }

    return Redirect::route('home')->with('user_disconnected', true);
  }

  public function store()
  {

    Event::fire('store method called');

    return 'store method called';
  }
}