<?php
class UserController extends BaseController {

  public function __construct(){

    if(!$this->hybridauth instanceof Hybrid_Auth):
      $this->hybridauth = new Hybrid_Auth(app_path() . '/config/hybridauth.php');
    endif;

    parent::__construct();
  }

  public function getDebug()
  {
    $is_logged = "no";
    $from_session = "no";
    $other_stuff = $this->user;
    return View::make('user.debug')->with([
      'is_logged' => $is_logged,
      'from_session' => $from_session,
      'other_stuff' => $other_stuff
    ]);
  }

  /**
   * [hybridAuthCheck description]
   * @return void
   */
  public function hybridAuthCheck()
  {
    try {
      if(!$this->hybridauth instanceof Hybrid_Auth):
        // init
        $this->hybridauth = new Hybrid_Auth(app_path() . '/config/hybridauth.php');
      endif;

      $oauth = $this->hybridauth;
      $connected_providers = $oauth->getConnectedProviders();

      if( !count($connected_providers) ):
        throw new Exception('No connected providers');
      endif;

      $provider_name = head($connected_providers);
      $$provider_name = $oauth->getAdapter($provider_name);
      $profile = $$provider_name->getUserProfile();
      $provider_uid = $profile->identifier;

      // connect and restore session for hybridauth api stuff
      $user = self::hybridAuthRestoreSession($provider_name, $provider_uid);

      // make user available to routes
      if(!$user instanceof User):
        return false;
      endif;

      return true;

    } catch(Exception $e) {

      return $e->getMessage();

    }
  }

  /**
   * [hybridAuthRestoreSession description]
   * @param  Int    $provider_id
   * @param  Int    $id
   * @return User   $user     [description]
   */
  public function hybridAuthRestoreSession($provider_name, $provider_uid) {

    // $this->debug->user[] = "@start hybridAuthRestoreSession";
    try {

      if(!$this->hybridauth instanceof Hybrid_Auth){
        // init
        $this->hybridauth = new Hybrid_Auth(app_path() . '/config/hybridauth.php');
      }

      $hybridauth = $this->hybridauth;

      // check database for user id matching session provider
      $uid = DB::table('user_connections')
                ->where('provider_name', '=', strtolower($provider_name))
                ->where('provider_uid', '=', $provider_uid)
                ->pluck('user_id');
      // $uid = UserConnection::find($provider_uid)->where('provider_name', $provider_id)->pluck('user_id');
      if(!$uid){
        // $this->debug->user[] = "@restore no connections found for UID";
        throw new Exception('Restore session: No connections found for this UID.');
      }

      // attempt login
      // $this->debug->user[] = "@restore loginUsingId(".$uid.")";
      $user = Auth::loginUsingId($uid);
      if(!$user instanceof User){
        throw new Exception('Login with provider failed using UID#'.$uid);
      }

      // fetch session data
      $auth_session_data = AuthSession::where('user_id', $uid)->pluck('hybridauth_session');
      if(!$auth_session_data) {
        throw new Exception('Restore session: No session data for this UID#'.$uid);
      }

      // do restore
      $hybridauth->restoreSessionData( $auth_session_data );
      if( !count($hybridauth->getConnectedProviders()) ) {
        throw new Exception('Restore session: Session restored with no connected providers.');
      }

      // session restored with connected providers
      $this->hybridauth = $hybridauth;

    } catch( Exception $e ){

      return $e->getMessage();

    }

    return $user;
  }

  /**
   * [authWithHybridAuth description]
   * @param  Hybrid_Provider_Adapter $provider [description]
   * @return Void                            [description]
   */
  public function authWithHybridAuth(Hybrid_Provider_Adapter $provider){

    $user = null;
    // lookup user
    try {

      $provider_name = strtolower($provider->id);
      $profile = $provider->getUserProfile();
      $provider_uid = $profile->identifier;
      $uid = DB::table('user_connections')
        ->where('provider_uid', '=', $provider_uid)
        ->where('provider_name', '=', $provider_name)
        ->pluck('user_id');
      // $uid = Models\User\Connection::find($provider_uid)->where('provider_name', $provider_name)->pluck('user_id');
      if(!$uid){
        // try getting the UID from an email
        $uid = DB::table('users')
          ->where('email', '=', $profile->email)
          ->pluck('id');
      }
      if($uid){
        Auth::loginUsingId($uid);
        if(Auth::check()){
          $this->user = Auth::user();
          return "User should be logged in with uid#".$uid;
        }
      }

      if ( !$user instanceof User ) {
        // register
        $user = UserController::registerWithProvider($provider);

        if ( !$user instanceof User) {
          if(!is_null($user)){
            throw new Exception("Invalid user instance. M: ".$user);
          }
          throw new Exception('Failed to register user. (null user)');
        }
      }

      // login
      Auth::loginUsingId($user->id, true); /* remember by default */
      if(!Auth::check()){
        throw new Exception('Could not connect user with Auth using UID#'.$user->id);
      }
      $this->user = $user;

      return "User should be logged in with uid#".$user->id;

    } catch(Exception $e) {

      return $e->getMessage();

    }
  }

  public function registerWithProvider(Hybrid_Provider_Adapter $provider)
  // public function registerWithProvider(Hybrid_Provider_Adapter $provider, UserConnections $userConnections, UserInfo $userInfo)
  {
    try
    {
      $provider_name = strtolower($provider->id);
      $profile = $provider->getUserProfile();

      if($profile instanceof Hybrid_User_profile){
        /** create empty site user id **/
        $user = new User;
        $user->email = $profile->email;
        $user->name = $profile->displayName;
        $user->save();
        $uid = $user->id;

        // echo "PID#".$pid." UID#".$uid;
        /** TODO: look to see if the connection exists yet
            provider ID/UID combo should be unique **/
        // UserConnection::find($uid)->where('provider_name','=',$pid);
        $connect = DB::table('user_connections')->insert(array(
          'user_id' => $uid,
          'provider_name' => $provider_name,
          'provider_uid' => $profile->identifier
        ));

        /** access provider list identifier **/
        $provider_info = AuthProvider::where('name', '=', $provider_name)->first();
        if(!isset($provider_info->id)){
          throw new Exception('Failed to select provider with name:'.$provider_name);
        }
        $pid = $provider_info->id;

        // TODO: check for properties to avoid errors
        $userinfo = DB::table('user_info')->insert(array(
          'user_id' => $uid,
          'provider_id' => $pid,
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
        ));
      }
      else
      {
        throw new Exception('Not a valid profile instance for UID#'.$user->id);
      }

      if(!$connect){
        throw new Exception('Failed to create user connection for UID#'.$user->id);
      }

      if(!$userinfo){
        throw new Exception('Failed to assign user info to UID#'.$user->id);
      }

    } catch (Exception $e) {

      return "Failed to register: ".$e->getMessage();

    }

    return $user;
  }

  public function getIndex()
  {
    if(!Auth::check()):
      return Redirect::to('user/connect')->with(array(
        'message' => 'Please connect to continue..'
      ));
    endif;

    return View::make('user.index');
  }

  public function getInfo()
  {
    if(!Auth::check()):
      return Redirect::to('user/connect')->with(array(
        'message' => 'Please connect to continue..'
      ));
    endif;

    return View::make('user.info');
  }

  /**
   * [getDisconnect description]
   * @return [type] [description]
   */
  public function getDisconnect()
  {

    // kill them all
    try {

      if(!$this->hybridauth instanceof Hybrid_Auth){

        // are there connections present?
        if(count($this->hybridauth->getConnectedProviders()) > 0){

          // connections exist. store for the return
          $hybridauth_session_data = $this->hybridauth->getSessionData();
          $store = AuthSession::create(array(
            'user_id' => Auth::user()->id,
            'hybridauth_session' => $hybridauth_session_data
          ));

          $this->hybridauth->logoutAllProviders();

        }

      }

      if(Auth::guest()){
        throw new Exception('Already disconnected. Would you like to connect?');
      }

      Auth::logout();

    } catch (\Exception $e) {

      return Redirect::route('home')->with('flash_notice', $e->getMessage());

    }

    return Redirect::route('home')->with('user_disconnected', true);
  }

  public function getConnect()
  {
    if(!$this->user instanceof User || Auth::check()){
      return View::make('user.connect');
    }

    return Redirect::to('user/connected');
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

    // if(Hash::check(Input::get('passwd'), $user->getAuthPassword())):
    //   return View::make('user.connect')->with([
    //     'error' => [
    //       'INVALID_PASSWORD' => 'Incorrect password. Try again, maybe?',
    //       'Hash' => Hash::make(Input::get('passwd'))
    //     ]
    //   ]);
    // endif;

    return self::getConnected();
  }

  public function doConnect($method)
  {
    try {
      // check URL segment
      if ($method == "provider") {
        // process authentication
        try {
          Hybrid_Endpoint::process();
        }
        catch (Exception $e) {
          // redirect back to http://URL/social/
          return Redirect::route('hybridauth')->with('flash_notice', $e->getMessage());
        }
        return;
      }

      $hybridauth = $this->hybridauth;
      if(!$hybridauth instanceof Hybrid_Auth)
      {
        throw new Exception('Invalid auth instance');
      }

      // verify provider
      $providers = Config::get('hybridauth.providers');
      if(array_key_exists(ucfirst($method), $providers))
      {
        if($providers[ucfirst($method)]["enabled"])
        {
          // auth with hybridauth credentials
          $provider = $hybridauth->authenticate($method);

          // site login
          if (!$provider instanceof Hybrid_Provider_Adapter)
          {
            throw new Exception('Not a valid auth provider.');
          }
          $auth_result = self::authWithHybridAuth($provider);

          if( !$this->user instanceof User) {
            throw new Exception('Not a valid user. Message: '.$auth_result);
          }

          // store session
          $auth_session = DB::table('auth_sessions')->insert(array(
            'user_id' => $this->user->id,
            'hybridauth_session' => $hybridauth->getSessionData()
          ));

          // output success
          return self::getConnected();
        }

        throw new Exception("Connecting with $method is disabled");
      }

      throw new Exception('Auth provider not found in config.');

    } catch(Exception $e) {

      return View::make('user.connect')->with( array('error' => $e->getMessage() ));

    }

    return self::getConnected();
  }

}