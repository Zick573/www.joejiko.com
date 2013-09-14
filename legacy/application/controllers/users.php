<?php

use Shared\Controller as Controller;
use tmhOAuth\Proxy as TwitterProxy;
use Framework\Registry as Registry;
use Framework\RequestMethods as RequestMethods;

class Users extends Controller
{
    public function register()
    {
        $user = User::first(array(
            "{$service_label} = ?" => $service_id
        ));

        if ($user)
        {
            $user->live = false;
            $user->save();
        }
    }

    public function twitterLookup($id)
    {
      $twitter = TwitterUser::first(array(
        "id = ?" => $id
      ));

      if($twitter)
      {
        return $twitter;
      }

      return false;
    }

    public function userLookup($credentials = array())
    {
        list($service, $id, $email) = $credentials;
        // lookup user in database
        $user = User::first(array(
            "{$service} = ?" => $id
        ));

        // user found
        if($user)
        {
          // check email
          if(is_null($user->email) || trim($user->email) == "")
          {
            // no email in user data
            // use posted email
            if(!is_null($email))
            {
              $user->email = $email;
              $user->ip = $_SERVER['REMOTE_ADDR'];
              $result = $user->save();
            }
          }
          return $user;
        }
        // no user found
        else
        {
          // could be a different service
          // find by email?
          if($email)
          {
            $user = User::first(array(
              "email = ?" => $email
            ));
          }

          // user found by email
          if($user)
          {
            $user->$service = $id;
            $user->ip = $_SERVER['REMOTE_ADDR'];
            $user->save();
          }
          // no user found
          else
          {
            // register new user
            $user = new User(array(
              'email' => $email,
              "{$service}" => $id,
              'name' => RequestMethods::post('name'),
              'ip' => $_SERVER['REMOTE_ADDR']
            ));
            $user->save();
          }
          // resulting user (either new or selected from DB)
          if($user)
          {
            return $user;
          }
          else
          {
            // houston we have a problem
            return "No result! {$result}";
          }
        }
    }

    public function login()
    {
      $this->willRenderLayoutView = false;
      $this->willRenderActionView = false;
      $this->willRenderJSON = false;
      if(RequestMethods::post('service'))
      {

        if(RequestMethods::post('id'))
        {

          $user = self::userLookup(array(
            RequestMethods::post('service'),
            RequestMethods::post('id'),
            RequestMethods::post('email')
          ));

          if(RequestMethods::post('service') === "twitter")
          {
            $twitterUser = self::twitterLookup(RequestMethods::post('id'));
            if($twitterUser !== false)
            {
              if($twitterUser->token !== RequestMethods::post('token'))
              {
                // update twitter creds
                $twitterUser->token = RequestMethods::post('token');
                if($twitterUser->secret !== RequestMethods::post('secret'))
                {
                  $twitterUser->secret = RequestMethods::post('secret');
                }
                $twitterUser->save();
              }
            }
            else
            {
              // insert new twitter creds
              $twitterUser = new TwitterUser(array(
                'id' => 17309749,
                'token' => RequestMethods::post('token'),
                'secret' => RequestMethods::post('secret')
              ));
              $twitterUser->save();
            }
          }

          if($user!==false)
          {
            // user found!
            // make cookies
            $U = array(
              'id' => $user->id,
              'key' => $user->name
            );
            setcookie("jjcom_user", implode("|", $U), time() + 1209600, '/', 'joejiko.com'); // two weeks

            // save to session
            $this->user = $user;
          }
        }

        // login request from service
        $content = array(
          'result' => true,
          'status' => "logged in",
          'admin' => false,
          'email' => $user->email ? true : false,
          'message' => "HI :)"
        );
        if($twitterUser){ $content['twitter'] = true; }
        header("Content-type: application/json");
        $this->willRenderJSON = true;
        $content = json_encode($content);
      }
      else
      {
        // REST
        $params = $this->_parameters;
        $service = NULL;
        $request = NULL;

        if(array_key_exists('service', $params))
        {
          $service = $params["service"];
        }

        if(array_key_exists('request', $params))
        {
          $request = $params["request"];
        }

        if(!is_null($service))
        {
          switch($service)
          {
            case "twitter":
            {
              $user = new TwitterProxy();
              if($request == "callback")
              {
                $content = $user->callback();
              }
              elseif($request == "verify")
              {
                $verified = $user->verify_credentials();
                $this->smarty->assign('twitter', $verified);
                $content = $this->smarty->fetch('users/login/twitter.tpl');
              }
              else
              {
                if (RequestMethods::get('url') == "login/twitter/wipe" || RequestMethods::post('wipe'))
                {
                  $content = $user->wipe();
                }
                else
                {
                  var_dump($user);
                  $content = $user->request_token();
                }
              }

              break;
            }
            case "google":
            {
              if(RequestMethods::post('access_token'))
              {
                header("HTTP/1.1 200 OK");
                $content = "OK";
              } else {
                $content = $this->smarty->fetch('users/login/google.tpl');
              }
            }
            default:
            {
              $user = null;
            }
          }
        }
        else
        {
          // service not defined
          $user = null;
        }

        if(count($user->error))
        {
          $content = $user->error;
        }

        if (!$content){
          /*
          if(isset($_SESSION['access_token'])){
          $content = 'There appears to be some credentials already stored in this browser session. Do you want to <form method="post"><input type="hidden" name="verify" value="true"><button type="submit">verify</button></form> the credentials? or  <form method="post"><input type="hidden" name="wipe" value="true"><button type="submit">wipe</button></form> them and start again.';
          }
          */
          $content = array(
            'errors' => array('something went wrong')
          );
          $content = json_encode($content);
        }
      }

      $this->smarty->assign('content', $content );
      $this->smarty->assign('layoutTpl', 'layouts/blank');
    }

    public function profile()
    {}

    public function search()
    {}

    /**
    * @before _secure
    */
    public function settings()
    {}

    public function logout()
    {
      setcookie("jjcom_user", '', time()-36000);
      $session = Registry::get("session");
      $session->erase("user");
      self::redirect('/');
    }

    /**
    * @before _secure
    */
    public function friend($id)
    {}

    /**
    * @before _secure
    */
    public function unfriend($id)
    {}

    protected function _upload($name, $user)
    {}

    /**
    * @before _secure, _admin
    */
    public function edit($id)
    {}

    /**
    * @before _secure, _admin
    */
    public function view()
    {}

    /**
    * @before _secure, _admin
    */
    public function delete($id)
    {
        $user = User::first(array(
            "id = ?" => $id
        ));

        if ($user)
        {
            $user->live = false;
            $user->save();
        }

        self::redirect("/users/view.html");
    }

    /**
    * @before _secure, _admin
    */
    public function undelete($id)
    {
        $user = User::first(array(
            "id = ?" => $id
        ));

        if ($user)
        {
            $user->live = true;
            $user->save();
        }

        self::redirect("/users/view.html");
    }
}
