<?php
use \Michelf\Markdown;
class BaseController extends Controller {

  public $debug = array(); // temp @todo remove
  public $user;
  public $hybridauth;
  public $user_from_session = false;
  private $gcdn_base = "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/";
  protected $errors = array();

  public function __construct(){
    // initial user info
    $this->user = (Auth::check()) ? Auth::user() : "Anonymous";

    if (!$this->user instanceof User)
    {
      // check for hybrid auth info
      $hybridAuthCheck = self::hybridAuthCheck();
    }

    if(is_null($this->user) || $this->user == "Anonymous"){
      $this->errors[] = $hybridAuthCheck;

      // set user as Anonymous
      $this->user = new stdClass();
      $this->user->name = "Anonymous";
      $this->user->id = 0;
      $this->user->role = 0;
    }
    else
    {
      $this->user->info = User::find($this->user->id)->info()->where('sortOrder', '=', '1')->first();
      if(is_null($this->user->email) || empty($this->user->email) || !isset($this->user->email)){
        return Redirect::to('user/connected/missing-required');
      }
    }
  }

  /**
   * [hybridAuthCheck description]
   * @return void
   */
  public function hybridAuthCheck()
  {
    try {

      if(!$this->hybridauth instanceof Hybrid_Auth){
        // init
        $this->hybridauth = new Hybrid_Auth(app_path() . '/config/hybridauth.php');
      }

      $oauth = $this->hybridauth;
      $connected_providers = $oauth->getConnectedProviders();

      if( !count($connected_providers) ) {
        throw new Exception('No connected providers');
      }

      $provider_name = head($connected_providers);
      $$provider_name = $oauth->getAdapter($provider_name);
      $profile = $$provider_name->getUserProfile();
      $provider_uid = $profile->identifier;

      // connect and restore session for hybridauth api stuff
      $user = self::hybridAuthRestoreSession($provider_name, $provider_uid);

      // make user available to routes
      if($user instanceof User){
        $this->user = $user;
      }

    } catch(Exception $e) {

      return $e->getMessage();

    }

    return;
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

    } catch(\Exception $e) {

      return $e->getMessage();

    }
  }

  public function setupLayout()
  {

    $footer_copy_path = base_path().'/app/assets/markdown/footer.md';
    if(file_exists($footer_copy_path)) {
      $site_footer_copy = Markdown::defaultTransform(file_get_contents($footer_copy_path));
    }
    $sharedViewData = array(
      'logo' => file_get_contents($this->gcdn_base.'img/shared/logo.svg'),
      'triangle' => file_get_contents($this->gcdn_base.'img/shared/triangle.svg'),
      'routeName' => Request::path(),
      'site_footer_copy' => $site_footer_copy,
      'user' => $this->user
    );
    foreach($sharedViewData as $label => $value)
    {
      View::share($label, $value);
    }

    if ( ! is_null($this->layout))
    {
      $this->layout = View::make($this->layout);
    }
  }
}