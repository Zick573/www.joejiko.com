<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {
  protected $hidden = array('password');
  protected $table = 'users';
  protected $hybridauth;
  protected $softDelete = true;

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
    return $this->hasMany('UserInfo');
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