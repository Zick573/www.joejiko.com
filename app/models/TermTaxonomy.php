<?php
class TermTaxonomy extends Eloquent
{
  protected $primaryKey = 'term_taxonomy_id';
  protected $table = 'term_taxonomy';
  protected $softDelete = false;
  public $timestamps = false;

  public function term()
  {
    return $this->hasMany('Term');
  }
}