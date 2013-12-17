<?php

class GoogleOAuthController extends BaseController
{

  protected $client;

  protected static $redirect_uri = 'http://local.joejiko.com/oauth/google';

  public function __construct(GoogleOAuthInterface $oauth, \Soramugi\GoogleDrive\Client $client)
  {
    $client->setRedirectUri($this->redirect_uri);
    $this->client = $client;
  }

  public function drive($client_id, $client_secret)
  {
    $client = $this->client;

    $client->setClientId('29103454985.apps.googleusercontent.com');
    $client->setClientSecret('p4tbDiXaL9_fCSGOGgRD01TJ');
    $client->setScopes([
      'https://www.googleapis.com/auth/drive'
    ]);
    $client->setUseObjects(true);

    return $this->auth($client, 'drive');
  }

  public function auth($client, $service)
  {
    if(isset($_GET['code'])):
      $client->authenticate();
    // if('drive' == $service):

      // if(isset($_GET['code'])):
      // $client->authenticate();

      // stop looking for oauth response
      Session::forget('oauth_resp');

      // set token
      Session::put('token', $client->getAccessToken());
      // echo "redirecting..";
      // $redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/api/google-drive';
      return Redirect::to('/oauth/google-drive');
      // echo $redirect;
      // header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
      // return;
    endif;

      if(Session::has('token')):
        $client->setAccessToken(Session::get('token'));
      endif;

      // $token = '{"access_token":"ya29.AHES6ZQrYD_dp7_rQqp4oe6_lKKCtqLw8CSSQZOzAHhB-bPAnM04TQ","token_type":"Bearer","expires_in":3600,"refresh_token":"1\/kFmAsX8sIvInN3RWr0ErIm0Yqo2AULNpcBCfBZeZ5mw","created":1382304355}';
      // $client->setAccessToken($token);
      if($client->getAccessToken()):
        // $service = new \GoogleApi\Contrib\apiDriveService($client);
        $files = new \Soramugi\GoogleDrive\Files($client);
        echo "<ul>";
        foreach($files->listFiles(array('q' => "'0B_9a_WMIXbTtSUxBTldmX3hNb1k' in parents"))->getItems() as $item):
          if (!$item->getLabels()->getTrashed()):
            echo "<li data-file-id=\"{$item->getId()}\"><img src=\"{$item->iconLink}\"> {$item->getTitle()}</li>";
          endif;
        endforeach;
        echo "</ul>";
        die();
      else:
        Session::put('oauth_resp', 'drive');
        echo '<a href="'.$client->createAuthUrl().'">connect to drive</a>';
      endif;
    endif;
  }

  public function get()
  {
    if(Session::has('oauth_resp')):
      if(Session::get('oauth_resp') == 'drive'):
        return self::getGoogleAuth('drive');
      endif;
    endif;
  }
}