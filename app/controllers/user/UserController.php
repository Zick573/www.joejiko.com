<?php
use Jiko\OAuth\HybridOAuthUserInterface;
use Input as I;
use Redirect as R;
use Session as S;
use View as V;

class UserController extends DefaultController {

  public function __construct()
  {
    // if(!Auth::check()):
    //   return R::to('user/connect')->with([
    //     'message' => 'Please connect to continue..'
    //   ]);
    // endif;
    $this->beforeFilter('auth', ['except' => ['connect', 'connected', 'disconnect']]);
  }

  public function index()
  {
    return V::make('user.index');
  }

  public function profile()
  {
    return V::make('user.info');
  }

  public function missingInfo()
  {
    return V::make('user.connected.missing_required');
  }


  public function connectWithOAuth()
  {

  }

  public function connected()
  {

    // if(!Auth::check()):

    //   # redirect to referring page (with error)
    //   if(S::has('connected_from_url')):
    //     return R::to(S::get('connected_from_url'))
    //       ->with('user_connect_status', "failed");
    //   endif;

    //   # fall back on static connect page (with error)
    //   return R::to('user/connect')->with('user_connect_status', "failed");

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
    //   R::to('user/connected/missing-required-info');

    // }

    # redirect to referring page (success)
    if(S::has('connected_from_url')):
      $r_to = S::get('connected_from_url');
      $r_with = ['user_connected' => true];
      return R::to($r_to)->with($r_with);
    endif;

    return V::make('home')->with('user_connected', true);
  }

  public function registerWithEmail()
  {
    return V::make('user.register-email-disabled');
  }

  /**
   * [doConnectEmail description]
   *
   * @return [type] [description]
   */
  public function connectWithEmail()
  {
    if(!Auth::attempt(
        [
          'email' => I::get('email'),
          'password' => I::get('passwd')
        ],
        true
      )
    ){

      return V::make('user.connect')->with([
        'error' => [
          'INVALID_CREDS' => 'Invalid credentials. Try again, maybe?'
        ]
      ]);

    }

    return self::getConnected();
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

    if(!$user_missing_email) {

      # make sure email isn't already registered
      # by a different provider
      if(!$user = User::where('email', $profile->email)->first()) {

        # insert user with provider info
        $user = new User;
        $user->email = $profile->email;
        $user->name = $profile->displayName;
        $user->save();
      }
    }

    # user register attempt without email
    else
    {

      # get user from from connected provider
      # or create a new user
      if($connection = UserConnection::where('provider_uid', $profile->identifier)->first()) {
        $user = User::find($connection->user_id);

      }

      else {
        # create a limited/unverified user
        $user = User::create([
          'email' => md5(uniqid(rand(),true)),
          'status' => "limited",
          'name' => $profile->displayName
        ]);

        # use random token for missing email
        // $user->email = md5(uniqid(rand(),true));
        // $user->status = "limited";
        // $user->name = $profile->displayName;
        // $user->save();
      }
    }

    // DB::transaction(function() use ($provider, $profile) {
    $provider_id = AuthProvider::where('name', strtolower($provider->id))->firstOrFail()->pluck('id');

    # insert provider connection info
    /**
     * @todo event.fire
     * oauth connect
     * with $user, $provider, $profile
     */
    $connection = UserConnection::create([
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
    $info = UserInfo::create([
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
    S::put('oauth_register', true);
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

    S::put('oauth_loginusingid', true);
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
        return R::route('hybridauth')->with('flash_notice', $e->getMessage());

      }
    endif;

    # oauth response
    try {

      $hybridauth = new Hybrid_Auth(Config::get('hybridauth'));

      // register or login
      $user = self::OAuthAttempt($hybridauth, $method);

      return self::getConnected();

    } catch(Exception $e) {

      return V::make('user.connect')->with(['error' => $e->getMessage()]);

    }
  }

  /**
   * /user/connect
   * Show connect options or redirect
   */
  public function getConnect()
  {
    if(!Auth::check()):
      return V::make('user.connect');
    endif;

    return R::to('user/connected');
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
          $store = AuthS::create([
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
      return R::route('home')->with('flash_notice', $e->getMessage());
    }

    return R::route('home')->with('user_disconnected', true);
  }

  public function store()
  {

    Event::fire('store method called');

    return 'store method called';
  }
}