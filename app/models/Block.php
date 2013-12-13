<?php

class Block extends Eloquent
{
  protected $table = "blocks";
  protected $softDelete = true;
  protected $timestamps = true;
}