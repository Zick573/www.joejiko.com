<?php
class Term extends Eloquent
{
  protected $primaryKey = 'term_id';
  protected $table = 'terms';
  protected $softDelete = false;
  protected $fillable = [
    'name',
    'slug'
  ];
  public $timestamps = false;
}