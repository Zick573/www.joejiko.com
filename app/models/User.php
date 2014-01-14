<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

  protected $guarded = ['id', 'password'];
  protected $hidden = ['password'];
  protected $oauth;
  protected $table = 'users';
  protected $softDelete = true;

  public function __construct(Jiko\OAuth\OAuthUserInterface $oauth)
  {
    $this->oauth = $oauth;
  }

  public function getAuthIdentifier()
  {
    return $this->id;
  }
  public function getAuthPassword()
  {
    return $this->password;
  }
  public function getReminderEmail()
  {
    return;
  }

  public function info()
  {
    return $this->hasOne('UserInfo');
  }

  public function roles()
  {
    return [
      'jiko' => $this->isJiko(),
      'admin' => $this->isAdmin(),
      'team' => $this->isTeam(),
      'guest' => $this->isGuest()
    ];
  }

  # Roles
  public function isJiko()
  {
    return 999 == $this->role;
  }

  public function isAdmin()
  {
    return 499 < $this->role;
  }

  public function isTeam()
  {
    return 249 < $this->role;
  }

  public function isGuest()
  {
    return 0 == $this->role;
  }
}