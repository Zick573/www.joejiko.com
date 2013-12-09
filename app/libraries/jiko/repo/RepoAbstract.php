<?php namespace Jiko\Repo;

abstract class RepoAbstract {
  protected function slug($string)
  {
    return filter_var( str_replace(' ', '-', strtolower(trim($string))), FILTER_SANITIZE_URL);
  }
}