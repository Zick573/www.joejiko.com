<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\Events as Events;
    use Framework\Assets\Exception as Exception;
    
    class Assets extends Base
    {     	   			
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
            
            Events::fire("framework.assets.construct.before", array('placeholder'));
						
						// do nothing
            
            Events::fire("framework.assets.construct.after", array('placeholder'));
        }
        
        public function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }
        
				public function setScripts($file)
				{
					$scripts = $this->_scripts;
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
							
							$valid = file_exists(APP_PATH.'/joejiko.com/'.$filepath);
							if($location == "none")
							{
								if(isset($scripts["footer"][$filepath]))
								{
									unset($scripts["footer"][$filepath]);
								} 
								elseif(isset($scripts["head"][$filepath]))
								{
									unset($scripts["head"][$filepath]);
								} 
							}
							else
							{
								$scripts[$location][$filepath] = $valid;
							}
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
					
					$styles = $this->styles;
										
					if(is_array($file))
					{
						
						foreach($file as $filename => $media)
						{
							if(!$media){ $media = "all"; }
							if($media != "none")
							{
								$styles['/styles/'.$filename.'.css'] = $media;
							}
							else
							{
								$filepath = '/styles/'.$filename.'.css';
								if(array_key_exists($filepath, $styles))
								{
									unset($styles[$filepath]);
								}
							}
						}
						
					} else {
						if(!$media){ $media = "all"; }
						$filepath = '/styles/'.$file.'.css';
						// string
						if($media != "none")
						{
							$styles[$filepath] = $media;
						} else {
							if(array_key_exists($filepath, $styles))
							{
								unset($styles[$filepath]);
							}
						}
					}
					
					$this->_styles = $styles;
					return $this;
				}
				
				public function _set($files)
				{
				}
				
				public function set($files)
				{
					// to do:
					// foreach $files as $function => $filenames
					// if function exists $this->set{$function}
					if(array_key_exists("scripts", $files))
					{
						$this->setScripts($files['scripts']);
					}
					
					if(array_key_exists("styles", $files))
					{
						$this->setStyles($files['styles']);
					}
					
					return $this;
				}
    }    
}