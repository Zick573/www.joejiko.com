<?php
class Terms extends Eloquent
{
  protected $primaryKey = 'term_id';
  protected $table = 'terms';
  protected $softDelete = false;
  public $timestamps = false;
  // public $name;
  // public $slug;

  public function taxonomy()
  {
    // taxonomy, description, parent, count
    return $this->belongsToMany('Term_Taxonomy', 'term_relationships', 'term_id', 'term_taxonomy_id');
  }
}