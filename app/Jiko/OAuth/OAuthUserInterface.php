<?php namespace Jiko\OAuth;

use User;

class OAuthUserInterface
{

  public function attempt(Array $credentials=[]);

  public function connect();

  public function disconnect();

  public function register(Array $credentials=[]);

  public function store();

  public function restore(User $user);

  public function validate($provider);

}