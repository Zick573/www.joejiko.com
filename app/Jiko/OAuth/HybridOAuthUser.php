<?php namespace Jiko\OAuth;

use Config, Exception;
use User;
use UserSession;

class HybridOAuthUser implements OAuthUserInterface
{

  protected $hybridauth;
  protected $config;

  public function __construct(Hybrid_Auth $hybridauth)
  {
    $this->hybridauth = $hybridauth;
    $this->config = Config::get('hybridauth');
  }

  public function attempt(Array $credentials=[])
  {

  }

  public function connect()
  {

  }

  public function disconnect()
  {

  }

  public function register(Array $credentials=[])
  {

  }

  public function store()
  {

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

}