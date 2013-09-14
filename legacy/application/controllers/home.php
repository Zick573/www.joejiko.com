<?php

use Shared\Controller as Controller;
use Framework\View as View;
use Framework\ArrayMethods as ArrayMethods;
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;

class Home extends Controller
{

    public function index()
    {
		$gallery = Image::all(array(
		    'live = ?' => 1,
		    'deleted = ?' => 0,
		    'parent = ?' => 0
		), array("*"), "id", "desc", 11, 1);
		$images = array();
		foreach($gallery as $image)
		{
		  list($imagename, $imageext) = explode('.', $image->filename);
			$images[] = array(
				'id' => $image->id,
				'filename' => "{$imagename}_s.{$imageext}",
				'caption' => $image->caption
			);
		}
		
		$this->smarty->assign(array(
			'gallery' => $images
		));

		$this->_assets->set(array(
			'scripts' => array(
				'libraries/selectivizr/selectivizr-min',
				'libraries/jquery/jquery.colorbox.min',
				'libraries/jquery/jquery.spritely-0.6.1',
				'libraries/jquery/jquery.timeago',
				'libraries/jquery/jquery.waypoints.min'
			),
			'styles' => array(
				'../scripts/libraries/jquery/jquery.colorbox' => 'all',
			)
		));

		$this->smarty->assign(array(
			'action' => 'home/index',
			'meta' => array(
				'title' => "Cute is a word for things that can't kill you"
			)
		));
    }

    public function privacy()
    {
    	$this->assets->set(array(
    		'scripts' => array(
    			'home' => 'none'
    		),
    		'styles' => array(
    			'home' => 'none'
    		)
    	));
    }

	public function more()
	{
			$this->_assets->set(array(
				'scripts' => array(
					'home' => 'none'
				),
				'styles' => array(
					'home' => 'none'
				)
			));

			$this->smarty->assign(array(
				'action' => 'home/more',
				'meta' => array(
					'title' => "More..."
				)
			));
	}

	public function apps()
	{
		$this->_assets->set(array(
			'scripts' => array(),
			'styles' => array(
				'home' => 'none'
			)
		));

		$this->smarty->assign(array(
			'action' => 'home/apps',
			'meta' => array(
				'title' => "Applications..."
			)
		));
	}

	public function resume()
	{
		$this->assets->set(array(
				'scripts' => array(
					'home' => 'none',
					'home/resume'
				),
				'styles' => array(
					'home' => 'none',
					'home/resume' => 'all'
				)
		));

		$this->smarty->assign(array(
			'meta' => array(
				'title' => "Tampa Bay web developer resume"
			)
		));
	}

	public function subscribe()
	{
			$this->_assets->set(array(
				'scripts' => array(
					'home' => 'none'
				),
				'styles' => array(
					'home' => 'none'
				)
			));

			$this->smarty->assign(array(
				'action' => 'home/more/subscribe',
				'meta' => array(
					'title' => "More..."
				)
			));
	}

	public function wishlist()
	{
			$this->_assets->set(array(
				'scripts' => array(
					'home' => 'none'
				),
				'styles' => array(
					'home' => 'none'
				)
			));

			$this->smarty->assign(array(
				'action' => 'home/more/wishlist',
				'meta' => array(
					'title' => "Wishlist"
				)
			));
	}

	public function legacy()
	{
		// @todo: legacy content

	}

    public function login()
    {
      $session = Registry::get("session");
      $continue = $session->get("continue");
      // display login
      $this->assets->set(array(
          'scripts' => array(
            'home' => 'none',
            'home/login'
          ),
          'styles' => array(
            'home' => 'none',
            'home/login' => 'all'
          )
      ));

      $this->smarty->assign(array(
        'meta' => array(
          'title' => "Log in to continue.."
        ),

        'continue' => $router->url
      ));
    }

    public function team()
    {
      $this->assets->set(array(
        'scripts' => array(
          'home/login'
        ),
        'styles' => array(
          'home/login' => 'all'
        )
      ));
      $this->smarty->assign(array(
        'action' => 'team/questionaire',
        'meta' => array(
          'title' => "Join the team"
        )
      ));
    }
}