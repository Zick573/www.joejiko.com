<?php
class Term_Taxonomy extends Eloquent
{
  protected $primaryKey = 'term_taxonomy_id';

  public function terms()
  {
    return $this->hasMany('Terms');
  }
}