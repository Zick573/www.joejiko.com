<?php

namespace Framework\Router\Route
{
    use Framework\Router as Router;
    
    class Regex extends Router\Route
    {    
        /**
        * @readwrite
        */
        protected $_keys;
        
        public function matches($url)
        {
            $pattern = $this->pattern;
            
            // check values
            preg_match_all("#^{$pattern}$#", $url, $values);
            
            if (sizeof($values) && sizeof($values[0]) && sizeof($values[1]))
            {
							unset($values[0]);
							$reset = array_values($values);
                // values found, modify parameters and return
                $derived = array_combine($this->keys, $reset);
								$merged = array_merge($this->parameters, $derived);
								$fixed = array();
								
								foreach($merged as $key => $array_of_values)
								{
									$fixed[$key] = $array_of_values[0];
								}
								
                $this->parameters = $fixed;
                
                return true;
            }
            
            return false;
        }
    }
}