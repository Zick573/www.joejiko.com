<?php namespace Jiko\Service\Form\Post;

use Jiko\Service\Validation\ValidInterface;
use Jiko\Repo\Post\PostInterface;

class PostForm {

  /**
   * form data
   *
   * @var array
   */
  protected $data;

  /**
   * Validator
   *
   * @var \Jiko\Form\Service\ValidInterface
   */
  protected $validator;

  /**
   * post repository
   *
   * @var \Jiko\Repo\Post\PostInterface
   */
  protected $post;

  public function __construct(ValidInterface $validator, PostInterface $post)
  {
    $this->validator = $validator;
    $this->post = $post;
  }

  /**
   * [save description]
   *
   * @param  array  $input [description]
   * @return [type]        [description]
   */
  public function save(array $input)
  {
    if( ! $this->valid($input) ) return false;

    $input['tags'] = $this->processTags($input['tags']);

    return $this->article->create($input);
  }

  /**
   * [update description]
   *
   * @param  array  $input [description]
   * @return [type]        [description]
   */
  public function update(array $input)
  {
    if( ! $this->valid($input) ) return false;

    $input['tags'] = $this->processTags($input['tags']);

    return $this->post->update($input);
  }

  /**
   * return any validation errors
   *
   * @return array
   */
  public function errors()
  {
    return $this->validator->errors();
  }

  /**
   * test if form validator passes
   *
   * @param  array  $input [description]
   * @return boolean
   */
  public function valid(array $input)
  {
    return $this->validator->with($input)->passes();
  }

  /**
   * covert strings of tags
   * to array of tags
   *
   * @param  string $tags
   * @return array
   */
  public function processTags($tags)
  {
    $tags = explode(',', $tags);

    foreach( $tags as $key => $tag ):
      $tags[$key] = trim($tag);
    endforeach;

    return $tags;
  }
}