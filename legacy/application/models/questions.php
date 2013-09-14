<?php

class Questions extends Shared\Model
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
		protected $_user_type;
		
    /**
    * @column
    * @readwrite
    * @type text
    */		
		protected $_question;
		
    /**
    * @column
    * @readwrite
    * @type text
    */
		protected $_answer;
		
    /**
    * @column
    * @readwrite
    * @type datetime
    */
		protected $_question_date;
		
    /**
    * @column
    * @readwrite
    * @type datetime
    */
		protected $_answer_date;
		
    /**
    * @column
    * @readwrite
    * @type integer
    */
		protected $_notify;
		
    /**
    * @column
    * @readwrite
    * @type integer
    */
		protected $_status;
		
}   
