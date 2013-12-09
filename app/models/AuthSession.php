<?php

class AuthSession extends Eloquent {

  protected $guarded = array('user_id');
  protected $primaryKey = 'user_id';
  protected $table = 'auth_sessions';
  public $incrementing = false;
}