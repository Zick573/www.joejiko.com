<?php namespace Jiko\Repo\User;

use Hybrid_Auth;
use Jiko\Repo\RepoAbstract;
use Illuminate\Database\Eloquent\Model;

class HybridAuthUser implements OAuthUserInterface, UserInterface {

  protected $user;
  protected $hybridauth;

  public function __construct(Model $user, Hybrid_Auth $hybridauth)
  {
    $this->user = $user;
    $this->hybridauth = $hybridauth;
  }

  public function check()
  {
    $this->hybridauth->getConnectedProviders();
  }

  /**
   * Connect user using provider adapter
   *
   * @param  [type] $provider_name [description]
   * @return mixed User |
   */
  public function connect($provider_name)
  {
    try {

      # authenticate provider
      $adapter = $this->hybridauth->authenticate($provider_name);

      # check for user info in local database
      $this->user->getConnection('provider', $provider_id);

      # register new user
      $this->register($provider_name, $adapter->getUserProfile());

      return $this->user->attempt($credentials);

    } catch ( Exception $e ) {

      return $e->getMessage();

    }
  }

  /**
   * Disconnect user and store session for later.
   *
   * @return void
   */
  public function disconnect()
  {
    try {

      # save session for return
      $this->storeSession();

      # kill
      $this->hybridauth->logoutAllProviders();

    } catch (Exception $e) {

      echo "logoutAllProviders: " . $e->getMessage();

    }
  }

  /**
   * register a new user using HybridAuth adapter information
   *
   * @param  string              $provider_name identifying the service/adapter
   * @param  Hybrid_User_Profile $profile       profile properties from Hybrid_Provider_Adapter
   *
   * @return mixed boolean FALSE | integer User ID
   */
  public function register($provider_name, Hybrid_User_Profile $profile)
  {
    /**
     * @todo validate $profile->email
     * email must be unique. facebook, google, will share the email
     * but twitter doesn't.
     *
     * if user is connecting with twitter, we'll tag them as 'limited'
     * until they add their email & verify it.
     */
    $profile_email = $profile->email;

    if(!isset($profile->email) || is_null($profile->email) || trim($profile->email) == "") {
      $profile_email = false;
      $profile_hash = md5(uniqid(rand(),true));
    }

    # create user entry
    $this->user->create([
      'email' => $profile_email == false ? $profile_hash : $profile_email,
      'name' => $profile->displayName,
      'location' => sprintf("%s, %s, %s", $profile->city, $profile->region, $profile->country),
      'role' => 1,
      'status' => $profile_email == false ? 'limited' : null
    ]);

    # create reference to provider connection
    $this->user->connection->create([
      'user_id' => $this->user->id,
      'provider_name' => $provider_name,
      'provider_uid' => $profile->identifier
    ]);

    # create profile with info from connection
    $this->user->profile->create([
      'user_id' => $this->user->id,
      'provider_id' => $provider_id,
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
    ]);

    return $this->user->id;
  }

  public function storeSession()
  {

    # store the session for later
    $this->user->session->create($this->hybridauth->getSessionData());

  }

  /**
   * [restore description]
   *
   * @param  User   $user [description]
   * @return integer Count of stored provider connections found for User
   */
  public function restore(User $user)
  {
    $session = UserSession::where('user_id', $user->id)
      ->first()
      ->pluck('session');

    $this->hybridauth->restoreSessionData( $session );

    return count($this->hybridauth->getConnectedProviders());
  }

  /**
   * [validate description]
   *
   * @param  [type] $provider [description]
   * @return mixed           true or string error message
   */
  public function validate($provider)
  {

    $providers = $this->config->providers;

    try {
      /**
       * @todo OAuthException extends Exception
       */
      if(!array_key_exists(ucfirst($provider), $providers)):

        throw new Exception('Auth provider not found in config.');

      endif;

      if(!$providers[ucfirst($provider)]["enabled"]):

        throw new Exception("Connecting with $provider is disabled");

      endif;

    } catch ( Exception $e ) {

      return $e->getMessage();

    }

    return true;
  }

  public function byId($id)
  {
    return $this->user->find($id);
  }

  public function create(array $data)
  {
    $user = $this->user->create([

    ]);

    if(!$user) return false;

    return true;
  }

  public function update(array $data)
  {
    $user = $this->user->find($data['id']);
    $user->save();

    return true;
  }
}