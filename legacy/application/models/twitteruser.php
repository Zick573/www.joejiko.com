<?php

class TwitterUser extends Shared\Model
{
  /**
  * @column
  * @readwrite
  * @primary
  * @type text
  */
  protected $_id;

  /**
  * @column
  * @readwrite
  * @type text
  */
  protected $_token;

  /**
  * @column
  * @readwrite
  * @type text
  */
  protected $_secret;

  protected $_table = 'twitter_user';
}