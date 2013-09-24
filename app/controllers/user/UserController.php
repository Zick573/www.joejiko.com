<?php
class UserController extends BaseController {

  public function __construct(){

    if(!$this->hybridauth instanceof Hybrid_Auth){
      $this->hybridauth = new Hybrid_Auth(app_path() . '/config/hybridauth.php');
    }

    parent::__construct();

  }

  public function getDebug()
  {
    $is_logged = "no";
    $from_session = "no";
    $other_stuff = $this->user;
    return View::make('user.debug')->with(array(
      'is_logged' => $is_logged,
      'from_session' => $from_session,
      'other_stuff' => $other_stuff
    ));
  }

  public function registerWithProvider(Hybrid_Provider_Adapter $provider)
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
    if(!Auth::check()){
      return Redirect::to('user/connect');
    }

    return View::make('user.index');
  }

  public function getInfo()
  {
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
    if($this->user instanceof User || Auth::check()){
      return Redirect::to('user/connected');
    }

    return View::make('user.connect');
  }

  public function getMissingInfo()
  {
    return View::make('user.connected.missing_required');
  }

  public function getConnected()
  {
    if(!Auth::check()){
      // redirect to referring page (with error)
      if(Session::has('connected_from_url')):
        return Redirect::to(Session::get('connected_from_url'))->with('user_connect_status', "failed");
      endif;

      // fall back on static connect page (with error)
      return Redirect::to('user/connect')->with('user_connect_status', "failed");
    }

    if(!isset($this->user->email) || is_null($this->user->email)){
      Redirect::to('user/connected/missing-required-info');
    }

    if(Session::has('connected_from_url')):
      // redirect to referring page (success)
      return Redirect::to(Session::get('connected_from_url'))->with('user_connected', true);
    endif;

    // fall back on a static connected page (success)
    // return View::make('user.connected');
  }

  public function doRegisterEmail()
  {
    return View::make('user.register-email-disabled');
  }

  public function doConnectEmail()
  {
    if($user = User::where('email', Input::get('email'))->first()):
        if(Hash::check(
            Input::get('passwd'),
            $user->password
        )) {
            Auth::loginUsingId($user->id);
            return self::getConnected();
        }
    endif;

    /** not working because not implementing a "username" column **/
    // if(Auth::attempt(array(
    //     'email' => Input::get('email'),
    //     'password' => Input::get('passwd')
    // ))) {
    //     return self::connected();
    // }

    // failed
    return View::make('user.connect')->with(array('error' => 'Invalid credentials. Try again maybe'));
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
        throw new \Exception('Invalid auth instance');
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
            throw new \Exception('Not a valid auth provider.');
          }
          $auth_result = self::authWithHybridAuth($provider);

          if( !$this->user instanceof User) {
            throw new \Exception('Not a valid user. Message: '.$auth_result);
          }

          // store session
          $auth_session = DB::table('auth_sessions')->insert(array(
            'user_id' => $this->user->id,
            'hybridauth_session' => $hybridauth->getSessionData()
          ));

          // output success
          return self::getConnected();
        }

        throw new \Exception("Connecting with $method is disabled");
      }

      throw new \Exception('Auth provider not found in config.');

    } catch(\Exception $e) {

      return View::make('user.connect')->with( array('error' => $e->getMessage() ));

    }

    return self::getConnected();
  }

}