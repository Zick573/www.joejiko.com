<?php namespace Jiko\Exception;

interface HandlerInterface {
  /**
   * handle jiko exceptions
   *
   * @param \Jiko\Exception\JikoException
   * @return void
   */
  public function handler(JikoException $exception);
}