<?php
class TermRelationship extends Eloquent
{
  protected $primaryKey = 'object_id';
  protected $table = 'term_relationships';
  protected $softDelete = false;
  public $timestamps = false;

  public function post()
  {
    return $this->belongsTo('post', 'object_id');
  }
}