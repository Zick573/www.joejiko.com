<?php
class Block extends Eloquent
{
  protected $table = "pages";
  protected $softDelete = true;
  protected $timestamps = true;
}