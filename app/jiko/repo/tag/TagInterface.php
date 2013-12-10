<?php namespace Jiko\Repo\Tag;

interface TagInterface {

  /**
   * find existing tags or create if they don't exist
   *
   * @param  array $tags array of strings, each representing a tag
   * @return array       array or arrayable collection of tag objects
   */
  public function findOrCreate(array $tags);

}