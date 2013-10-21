<?php
class UserInfo extends Eloquent {
  protected $primaryKey = 'user_id';
  protected $table = 'user_info';
  public $incrementing = false;
}