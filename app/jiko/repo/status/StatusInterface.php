<?php namespace Jiko\Repo\Status;

interface StatusInterface {

  /**
   * [all description]
   * @return [type] [description]
   */
  public function all();

  /**
   * [byId description]
   * @return [type] [description]
   */
  public function byId();

  /**
   * [byStatus description]
   * @return [type] [description]
   */
  public function byStatus();

}