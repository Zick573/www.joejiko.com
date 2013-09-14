<?php

use Shared\Controller as Controller;
use Framework\ArrayMethods as ArrayMethods;
use Framework\RequestMethods as RequestMethods;
use Framework\View as View;

class Music extends Controller
{

		public function view()
		{
			
			if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {
				
					if(RequestMethods::post("tracks"))
					{
						$this->smarty->assign('tracks', RequestMethods::post("tracks"));
					}
					
					if(RequestMethods::post("track"))
					{
						$this->smarty->assign('track', RequestMethods::post("track"));
					}

					$output = $this->smarty->fetch('music/tracker/feed.tpl');
					
					$this->willRenderLayoutView = false;
					$this->willRenderActionView = false;
					$this->willRenderJSON = true;
					
					header("Content-type: application/json");
					$this->smarty->assign('content', json_encode( array('html' => $output) ));
					$this->smarty->assign('layoutTpl', 'layouts/blank');
			} else {

				// normal request
				$this->_assets->set(array(
					'scripts' => array(
						'libraries/jquery/jquery.timeago',
						'music/tracker'
					),
					'styles' => array(
						'music/tracker' => 'all'
					)
				));
							
				$this->smarty->assign(array(
					'action' => 'music/tracker',
					'meta' => array(
						'title' => "Music tracker"
					)
				));
			}
		
		}
	
    public function tracker($page=NULL)
    {
			// last FM
			$o = array(
				'method' => 'user.getRecentTracks',
				'user' => 'joejiko',
				'page' => 1
			);
			$music = new \Lastfm\Api($o);
			$data = $music->output('tracker');
			$data = json_decode($data, true);
			$this->_assets->set(array(
				'scripts' => array(
					'libraries/jquery/jquery.timeago',
					'music/tracker'
				),
				'styles' => array(
					'music/tracker' => 'all'
				)
			));
					
			$this->smarty->assign(array(
				'action' => 'music/tracker',
				'meta' => array(
					'title' => "Music tracker"
				),
				'tracks' => $data['tracks'],
				'stats' => $data['stats']
			));
    }

}