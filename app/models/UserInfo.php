<?php
class UserInfo extends Eloquent {
  protected $primaryKey = 'user_id';
  protected $table = 'user_info';
  protected $guarded = ['id'];
  // protected $fillable = ['*'];
  public $incrementing = false;
}