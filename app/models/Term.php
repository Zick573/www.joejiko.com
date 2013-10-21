<?php
class Term extends Eloquent
{
  protected $primaryKey = 'term_id';
  protected $table = 'terms';
  protected $softDelete = false;
  public $timestamps = false;
  // public $name;
  // public $slug;

  public function taxonomy()
  {
    return $this->belongsTo('TermTaxonomy');
  }
}