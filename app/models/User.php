<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {
  protected $hidden = array('password');

  protected $table = 'users';

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

  public function connection()
  {
   return $this->hasMany('UserConnection');
  }

  public function info()
  {
    return $this->hasMany('UserInfo');
  }

  public function authsession()
  {
   return $this->hasMany('AuthSession');
  }
}