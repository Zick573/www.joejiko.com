<?php namespace Jiko\OAuth;

use User;
use Hybrid_User_Profile;

interface OAuthUserInterface
{

  public function attempt(array $credentials);

  public function connect($provider_name);

  public function disconnect();

  public function register($provider_name, Hybrid_User_Profile $profile);

  public function store();

  public function restore(User $user);

  public function validate($provider);

}