<?php namespace Jiko\Service\Form\Post;

use Jiko\Service\Validation\AbstractLaravelValidator;

class PostFormLaravelValidator extends AbstractLaravelValidator {
  /**
   * validation rules
   *
   * @var array
   */
  protected $rules = [
    'title' => 'required',
    'user_id' => 'required|exists:users,id',
    'status_id' => 'required|exists:statuses,id',
    'excerpt' => 'required',
    'content' => 'required',
    'tags' => 'required'
  ];
}