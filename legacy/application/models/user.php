<?php

class User extends Shared\Model
{
    /**
    * @column
    * @readwrite
    * @primary
    * @type autonumber
    */
    protected $_id;

    /**
    * @column
    * @readwrite
    * @type integer
    *
    * @label google account id
    */
    protected $_google;

    /**
    * @column
    * @readwrite
    * @type integer
    *
    * @label twitter account id
    */
    protected $_twitter;

    /**
    * @column
    * @readwrite
    * @type integer
    *
    * @label facebook account id
    */
    protected $_facebook;

    /**
    * @column
    * @readwrite
    * @type text
    * @length 100
    *
    * @validate required, alpha, min(3), max(32)
    * @label name
    */
    protected $_name;

    /**
    * @column
    * @readwrite
    * @type text
    * @length 100
    * @index
    *
    * @validate required, max(100)
    * @label email address
    */
    protected $_email;

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_password;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_status;
		
    /**
    * @column
    * @readwrite
    * @type text
    */
		protected $_ip;
}
