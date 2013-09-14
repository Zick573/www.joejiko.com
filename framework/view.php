<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\Events as Events;
    use Framework\View\Exception as Exception;
    
    class View extends Base
    {     	   
        /**
        * @readwrite
        */
        protected $_data;
				
				/**
				* @readwrite
				*/
				protected $_scripts;
				
				/**
				* @readwrite
				*/
        protected $_styles;
				
        public function __construct($options = array())
        {
            parent::__construct($options);
            
            Events::fire("framework.view.construct.before", array('placeholder'));
						
						// do nothing
            
            Events::fire("framework.view.construct.after", array('placeholder'));
        }
        
        public function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }
        
				public function setScripts($file)
				{
				
					if(is_array($file))
					{
						foreach($file as $k => $v)
						{
							$filepath = '/scripts/';
							
							if(is_int($k))
							{
								$filepath .= $v;
								$location = 'footer';
							} else {
								$filepath .= $k;
								$location = $v;
							}
							
							$filepath .= '.js';
							
							$valid = file_exists(APP_PATH.'/public/'.$filepath);
							$scripts[$location][$filepath] = $valid;
						}
					} else {
						
						$filepath = '/scripts/'.$file.'.js';
						$valid = file_exists($filepath);
						$scripts[$filepath] = $valid;
					}
					
					$this->_scripts = $scripts;
					return $this;
				}
				
				public function setStyles($file, $media = NULL)
				{
					
					if(!$media){ $media = "all"; }
					
					if(is_array($file))
					{
						
						foreach($file as $filename => $media)
						{
							$styles['/styles/'.$filename.'.css'] = $media;
						}
						
					} else {
						
						$filepath = '/styles/'.$file.'.css';
						// string
						$styles[$filepath] = $media;
						
					}
					
					$this->_styles = $styles;
					return $this;
				}
				
        public function get($key, $default = "")
        {
            if (isset($this->data[$key]))
            {
                return $this->data[$key];
            }
            return $default;
        }
        
        protected function _set($key, $value)
        {    
            if (!is_string($key) && !is_numeric($key))
            {
                throw new Exception\Data("Key must be a string or a number");
            }
        
            $data = $this->data;
            
            if (!$data)
            {
                $data = array();
            }
            
            $data[$key] = $value;
            $this->data = $data;
        }
        
        
        public function set($key, $value = null)
        {
            if (is_array($key))
            {
                foreach ($key as $_key => $value)
                {
                    $this->_set($_key, $value);
                }
                return $this;
            }
            
            $this->_set($key, $value);
            return $this;
        }                 
    }    
}