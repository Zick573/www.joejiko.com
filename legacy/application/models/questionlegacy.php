<?php

class QuestionLegacy extends Shared\Model
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
    * @type text
    */		
		protected $_user_email;
		
    /**
    * @column
    * @readwrite
    * @type integer
    */
		protected $_user_id;
		
    /**
    * @column
    * @readwrite
    * @type text
    */		
		protected $_user_ip;
		
    /**
    * @column
    * @readwrite
    * @type text
    */		
		protected $_user_name;
		
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
    * @type text
    */		
		protected $_question_date;
		
    /**
    * @column
    * @readwrite
    * @type text
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
