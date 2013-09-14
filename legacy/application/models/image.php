<?php

class Image extends Shared\Model
{
    /**
    * @column
    * @readwrite
    * @type text
    * @length 255
    */
    protected $_filename;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_size;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_width;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_height;

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_metadata;

    /**
    * @column
    * @readwrite
    * @type text
    * @length 45
    */
    protected $_mime_type;

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_caption;

    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_description;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_uploaded_by;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_parent;

    protected $_table = 'images';
}
