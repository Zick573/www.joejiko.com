<?php
class UserConnection extends Eloquent {

  protected $fillable = ['user_id', 'provider_name', 'provider_uid'];
  protected $primaryKey = 'user_id';
  protected $table = 'user_connections';
  public $timestamps = true;
  public $incrementing = false;

  public function user()
  {
    return $this->hasOne();
  }

}