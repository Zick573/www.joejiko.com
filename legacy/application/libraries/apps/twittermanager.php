<?php
namespace Apps
{
  use Shared\Controller as Controller;
  use Framework\Registry as Registry;
  use Framework\Session as Session;
  use Framework\RequestMethods as RequestMethods;
  use tmhOAuth\Proxy as TwitterProxy;

  class TwitterManager extends Controller
  {
      public function getFollowers()
      {
        // look up user tokens
        if($this->user){
          $twitter = "user found ".$this->user->id;
        }
        else
        {
          $twitter = "get user..";
        }
        // request follower list from twitter
        // save to database
        // output
        $this->smarty->assign(array(
          'twitter' => $twitter
        ));
      }

      public function index($request=NULL)
      {
        switch($request)
        {
          case "/followers":
            self::getFollowers();
            break;
        }

        if($request === NULL || trim($request) == "")
        {
          $request = "/login";
        }

        $this->assets->set(array(
          'scripts' => array(),
          'styles' => array(
            'layouts/default/styles' => 'none',
            'apps/twitter-manager' => 'all'
          )
        ));

        $this->smarty->assign(array(
          'action' => 'apps/twitter-manager/index',
          //'method' => $this->smarty->fetch("apps/twitter-manager/{$request}.tpl"),
          'method' => $this->smarty->fetch("apps/twitter-manager/login.tpl"),
          'meta' => array(
            'title' => "Twitter Manager"
          )
        ));
      }
  }
}