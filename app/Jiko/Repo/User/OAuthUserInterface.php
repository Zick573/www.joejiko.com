<?php namespace Jiko\OAuth;

use User;

class OAuthUserInterface
{

  public function attempt(array $credentials);

  public function connect();

  public function disconnect();

  public function register(array $data);

  public function store();

  public function restore();

  public function validate($provider);

}