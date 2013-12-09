<?php Namespace Question;
class Status extends \Eloquent {
  protected $softDelete = true;
  protected $table = 'questions_status';
  protected $label;
}