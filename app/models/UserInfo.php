<?php
class UserInfo extends Eloquent {
  protected $primaryKey = 'user_id';
  protected $table = 'user_info';
  protected $fillable = ['*'];
  public $incrementing = false;
}