<?php namespace Jiko\Service\Validation;

interface ValidInterface {

  /**
   * add data to validate
   *
   * @param  array
   * @return \Impl\Service\Validation\ValidInterface $this
   */
  public function with(array $input);

  /**
   * test if validation passes
   *
   * @return boolean
   */
  public function passes();

  /**
   * retrieve validation errors
   *
   * @return array
   */
  public function errors();
}