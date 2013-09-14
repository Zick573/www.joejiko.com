<?php

use Shared\Controller as Controller;
use Framework\View as View;
use Framework\ArrayMethods as ArrayMethods;
use Framework\RequestMethods as RequestMethods;

class Search extends Controller
{
	
    public function index($query=NULL)
    {
//				$this->smarty->debugging = true;
//        $user = $this->getUser();
				if(!is_null($query))
				{
					$query = str_replace("-", " ", $query);	
				}
								
				if($this->smarty->getTemplateVars("search"))
				{
					// do something special
				}
				
				if(!is_null($query))
				{
					$this->smarty->assign(array(
						'query' => $query
					));
				} 
				elseif(RequestMethods::post("query"))
				{
					$this->smarty->assign(array(
						'query' => RequestMethods::post("query")
					));
				}
				
				$this->smarty->assign(array(
					'meta' => array(
						'title' => "Search (coming soon!)"
					)
				));
    }
		
		public function tag($query)
		{
			$this->smarty->assign(array(
				'action' => 'search/index',
				'search' => 'tags'
			));
			self::index($query);
		}
}