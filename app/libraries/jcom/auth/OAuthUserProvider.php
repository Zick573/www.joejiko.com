<?php

namespace Jcom\Auth;

use Illuminate\Auth\UserProviderInterface, Illuminate\Auth\GenericUser;

class OAuthUserProvider implements UserProviderInterface
{
  private $app;
  /**
   * @var  UserService
   */
  private $userService;

  public function __construct($app)
  {
    $this->app = $app;
  }

  /**
   * Retrieve a user by their unique identifier
   *
   * @param  mixed $identifier
   *
   * @return \Illuminate\Auth\UserInterface|null
   */
  public function retrieveByID($identifier)
  {
    /** @var User $user */
    $user = $this->userService->findUserbyUserIdentifier($identifier);

    if(!$user instanceof User) {
      return false;
    }

    return new GenericUser([
      'id' => $user->getUserIdentifier(),
      'username' => $user->getUserName()
    ]);
  }

  /**
   * Retrieve a user by the given creds
   *
   * @param array $credentials
   *
   * @return  \Illuminate\Auth\UserInterface|null
   */
  public function retrieveByCredentials(array $credentials)
  {
    /** @var User $user */
    $user = $this->userService->findUserByUserName($credentials['username']);

    if(!$user instanceof User) {
      return false;
    }

    return new GenericUser([
      'id' => $user->getUserIdentifier(),
      'username' => $user->getUserName()
    ]);
  }

  /**
   * Validate a user against the given creds
   *
   * @param \Illuminate\Auth\UserInterface $user
   * @param array $credentials
   *
   * @return bool
   */
  public function validateCredentials(\Illuminate\Auth\UserInterface $user, array $credentials)
  {
    $validated = $this->userService->validateUserCredentials(
      $credentials['username'],
      $credentials['password']
    );

    $validated = $validated && $user->userName = $credentials['username'];

    return $validated;
  }
}