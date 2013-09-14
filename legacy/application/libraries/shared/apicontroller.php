<?php

namespace Shared
{
			
    class ApiController extends \Framework\Base
    {

        /**
        * @read
        */
        protected $_json;

        public function __construct()
        {
					
        }

        public function render()
        {
					header("Content-type: application/json");                
        	echo $this->_json;  
        }
    }
}