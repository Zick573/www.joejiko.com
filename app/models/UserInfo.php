<?php
class UserInfo extends Eloquent {

  protected $guarded = array('user_id');
  protected $primaryKey = 'user_id';
  protected $table = 'user_info';
  public $incrementing = false;
}