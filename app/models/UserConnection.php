<?php
class UserConnection extends Eloquent {

  protected $guarded = array('user_id');
  protected $primaryKey = 'user_id';
  protected $table = 'user_connections';
  public $incrementing = false;

}