<?php
class Hybrid extends Eloquent
{

  public function session()
  {
    return $this->hasMany('authsession');
  }
}