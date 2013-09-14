<?php

class LoveCalculatorScore extends Shared\Model
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
    */
    protected $_user;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_score;
		
    /**
    * @column
    * @readwrite
    * @type text
    */
		protected $_ip;
}
