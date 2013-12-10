<?php namespace Jiko\Service\Validation;

use Illuminate\Validation\Factory;

abstract class AbstractLaravelValidator implements ValidInterface {
  /**
   * validator
   *
   * @var \Illuminate\Validation\Factory
   */
  protected $validator;

  /**
   * validation data key => value array
   *
   * @var array
   */
  protected $data = array();

  /**
   * validation errors
   *
   * @var array
   */
  protected $errors = array();

  /**
   * validation rules
   *
   * @var array
   */
  protected $rules = array();

  public function __construct(Factory $validator)
  {
    $this->validator = $validator;
  }

  /**
   * set data to validate
   *
   * @return \Jiko\Service\Validation\AbstractLaravelValidator
   */
  public function with(array $data)
  {
    $this->data = $data;

    return $this;
  }

  /**
   * validation passes or fails
   *
   * @return boolean
   */
  public function passes()
  {
    $validator = $this->validator->make($this->data, $this->rules);

    if($validator->fails())
    {
      $this->errors = $validator->messages();
      return false;
    }

    return true;
  }

  /**
   * return errors, if any
   *
   * @return array
   */
  public function errors()
  {
    return $this->errors;
  }
}