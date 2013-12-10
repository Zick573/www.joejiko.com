<?php namespace Jiko\Repo\Post;

interface PostInterface {

  /**
   * retrieve post by id
   * regardless of status
   *
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function byId($id);

  /**
   * get paginated posts
   *
   * @param  integer $page  [description]
   * @param  integer $limit [description]
   * @param  boolean $all   [description]
   * @return [type]         [description]
   */
  public function byPage($page=1, $limit=10, $all=false);

  /**
   * get single post by URL
   *
   * @param  [type] $slug [description]
   * @return [type]       [description]
   */
  public function bySlug($slug);

  /**
   * get posts by their tag
   *
   * @param  [type]  $tag   [description]
   * @param  integer $page  [description]
   * @param  integer $limit [description]
   * @return [type]         [description]
   */
  public function byTag($tag, $page=1, $limit=10);

  /**
   * create a new post
   * @param  array  $data [description]
   * @return [type]       [description]
   */
  public function create(array $data);

  /**
   * update an existing post
   *
   * @param  array  $data [description]
   * @return [type]       [description]
   */
  public function update(array $data);
}