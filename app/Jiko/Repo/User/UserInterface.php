<?php namespace Jiko\Repo\User;

interface UserInterface {

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