<?php namespace Jiko\Repo\User;

interface UserInterface {

  /**
   * retrieve user by id
   * regardless of status
   *
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function byId($id);

  /**
   * create a new user
   * @param  array  $data [description]
   * @return [type]       [description]
   */
  public function create(array $data);

  /**
   * update an existing user
   *
   * @param  array  $data [description]
   * @return [type]       [description]
   */
  public function update(array $data);
}