<?php
class Term_Relationship extends Eloquent
{
  protected $primaryKey = 'object_id';
  protected $table = 'term_relationships';
  protected $softDelete = false;
  public $timestamps = false;
}