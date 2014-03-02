<?php
use Jiko\OAuth\OAuthUserInterface;

class UserController extends DefaultController {

  public function __construct(OAuthUserInterface $hybridAuth)
  {
    $this->hybridAuth = $hybridAuth;
    $this->beforeFilter('auth', [
      'except' => [
        'connect',
        'connectWithProvider',
        'connected',
        'disconnect'
      ]
    ]);
  }

  public function index()
  {
    return \View::make('user.index');
  }

  public function infoImport()
  {
    $profile = $this->hybridAuth->getUserProfile();
    $prepared = [
      'provider_name' => strtolower($profile->provider_name),
      'provider_uid' => $profile->identifier,
      'profile_url' => $profile->profileURL,
      'website_url' => $profile->webSiteURL,
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
    ];
    Auth::user()->info()->create($prepared);

    return Redirect::to('user/info');
  }

  public function info()
  {
    return \View::make('user.info');
  }

  public function missingInfo()
  {
    return \View::make('user.connected.missing_required');
  }

  public function connected()
  {

    // if(!Auth::check()):

    //   # redirect to referring page (with error)
    //   if(Session::has('connected_from_url')):
    //     return Redirect::to(Session::get('connected_from_url'))
    //       ->with('user_connect_status', "failed");
    //   endif;

    //   # fall back on static connect page (with error)
    //   return Redirect::to('user/connect')->with('user_connect_status', "failed");

    // endif;

    # check for email (required)
    /**
     * @todo $this->user->validate()
     */
    // if( !isset($this->user->email)
    //     || is_null($this->user->email)
    //     || empty($this->user->email)
    // ){

    //   # prompt for email if it's missing
    //   Redirect::to('user/connected/missing-required-info');

    // }

    # redirect to referring page (success)
    if(\Session::has('connected_from_url')):
      $r_to = \Session::get('connected_from_url');
      $r_with = ['user_connected' => true];
      return \Redirect::to($r_to)->with($r_with);
    endif;

    return \Redirect::to('/')->with('user_connected', true);
  }

  public function registerWithEmail()
  {
    return \View::make('user.register-email-disabled');
  }

  /**
   * [doConnectEmail description]
   *
   * @return [type] [description]
   */
  public function connectWithEmail()
  {
    if(!\Auth::attempt(
        [
          'email' => \Input::get('email'),
          'password' => \Input::get('passwd')
        ],
        true
      )
    ){

      return \View::make('user.connect')->with([
        'error' => [
          'INVALID_CREDS' => 'Invalid credentials. Try again, maybe?'
        ]
      ]);

    }

    return self::getConnected();
  }

  public function connectWithProvider($provider=null, $action='')
  {
    if(Input::get('error')) {
      dd(Input::all());
    }

    if(is_null($provider)) {
      try {

        # OAuth pass
        Hybrid_Endpoint::process();

      }
      catch ( \Exception $e ) {
        echo "Error at Hybrid_Endpoint process (UserController@connectWithProvider): $e";
      }
      return;
    }

    $provider_id = is_object($provider) ? $provider->name : null;

    # connect user
    $user = $this->hybridAuth->connect($provider_id);
    \Auth::login($user);

    return Redirect::to('user/connected');
  }

  public function OAuthValidateProvider($provider)
  {
    // moved
  }

  /**
   * @param Hybrid_Auth $oauth   [description]
   * @param UserSession $session [description]
   */
  public function OAuthRestore(Hybrid_Auth $oauth, UserSession $session)
  {
    // moved
  }

  /**
   * [OAuthRegister description]
   *
   * @param Hybrid_Provider_Adapter $provider [description]
   * @param [type]                  $info     [description]
   */
  public function OAuthRegister(Hybrid_Provider_Adapter $provider, Hybrid_User_Profile $profile)
  {

    /**
     * @todo User->validate()
     */
    $user_missing_email = (isset($profile->email) && !empty($profile->email));

    /**
     * @todo DB transaction
     * User, UserConnection should be created at the same time
     */
    if(!$user_missing_email) {
        # insert user with provider info
        $user = new \User;
        $user->email = $profile->email;
        $user->name = $profile->displayName;
        $user->save();
    }

    # user register attempt without email
    else
    {

      # get user from from connected provider
      # or create a new user
      if($connection = \UserConnection::where('provider_uid', $profile->identifier)->first()) {
        $user = \User::find($connection->user_id);

      }

      else {
        # create a limited/unverified user
        $user = \User::create([
          'email' => md5(uniqid(rand(),true)),
          'status' => "limited",
          'name' => $profile->displayName
        ]);
      }
    }

    // DB::transaction(function() use ($provider, $profile) {
    $provider_id = \AuthProvider::where('name', strtolower($provider->id))->firstOrFail()->pluck('id');

    # insert provider connection info
    /**
     * @todo event.fire
     * oauth connect
     * with $user, $provider, $profile
     */
    $connection = \UserConnection::create([
      'user_id' => $user->id,
      'provider_name' => strtolower($provider->id),
      'provider_uid' => $profile->identifier
    ]);
    // $connection = DB::table('user_connections')->insert([

    // ]);

    /**
     * @todo event.fire
     * user register
     * with $provider, profile
     */
    # insert user info from provider
    $info = \UserInfo::create([
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
    // $info = DB::table('user_info')->insert([

    // ]);
    // }); // END transaction
    \Session::put('oauth_register', true);
    return $user->id;

  }

  public function OAuthAttempt(Hybrid_Auth $oauth, $credentials)
  {
    self::OAuthValidateProvider($credentials);

    # actual OAuth attempt
    $provider = $oauth->authenticate($credentials);
    if(!$provider instanceof Hybrid_Provider_Adapter):
      throw new \Exception('Not a valid oauth provider.');
    endif;

    $profile = $provider->getUserProfile();
    $credentials = [
      'provider_name' => strtolower($provider->id),
      'provider_uid' => $profile->identifier
    ];
    $uid = \UserConnection::where('provider_name', $credentials['provider_name'])
      ->where('provider_uid', $credentials['provider_uid'])
      ->pluck('user_id');

    # attempt to register the OAuth user
    if(!$uid):
      $uid = static::OAuthRegister($provider, $profile);
    endif;

    # log in with site ID
    \Auth::loginUsingId($uid);
    if(!\Auth::check()):
      throw new \Exception('Connection failed.. or something');
    endif;

    // store session
    $authsession = \DB::table('auth_sessions')->insert([
      'user_id' => \Auth::user()->id,
      'session' => $oauth->getSessionData()
    ]);

    Session::put('oauth_loginusingid', true);
    return Auth::user();
  }

  public function connectWithOAuth()
  {
    # oauth callback
    # oauth response

    # process data from oauth provider
    // Hybrid_Endpoint::process();

    # redirect to connect options with message
    return View::make('user.connect')->with('flash_notice', 'Connect with OAuth failed.');
  }

  /**
   * /user/connect
   *
   * Show connect options or redirect
   */
  public function connect()
  {

    if(Input::has('email'))
    {
      # connect with Email?
      $this->connectWithEmail();
    }

    # connect with OAuth?
    $this->connectWithOAuth();

    # do we have a valid user?
    if(!Auth::check()):

      # show connection options
      return View::make('user.connect');

    endif;

    # user connected
    return Redirect::to('user/connected');

  }

  /**
   * /user/disconnect
   * disconnect/logout user and redirect
   */
  public function disconnect()
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