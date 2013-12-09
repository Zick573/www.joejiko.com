<?php
class OAuthController extends BaseController
{
  protected function getGoogleAuth($service)
  {
    $client = new \Soramugi\GoogleDrive\Client();
    $client->setClientId('29103454985.apps.googleusercontent.com');
    $client->setClientSecret('p4tbDiXaL9_fCSGOGgRD01TJ');
    // $client->setDeveloperKey('AIzaSyCTaM2WANR7FkYrCBMFYJWBqlleuh9AL_g');
    $client->setRedirectUri('http://local.joejiko.com/oauth/google');

    if('drive' == $service):
      $client->setScopes(array('https://www.googleapis.com/auth/drive'));
      $client->setUseObjects(true);

      if(isset($_GET['code'])):
        $client->authenticate();

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

  public function getGoogle()
  {
    if(Session::has('oauth_resp')):
      if(Session::get('oauth_resp') == 'drive'):
        return self::getGoogleAuth('drive');
      endif;
    endif;
  }

  public function getGoogleDrive()
  {
    return self::getGoogleAuth('drive');

    // $this->service = new \GoogleApi\Contrib\Google_DriveService($this->client);
    // $authUrl = $client->createAuthUrl();

    // //Request authorization
    // print "Please visit:\n$authUrl\n\n";
    // print "Please enter the auth code:\n";
    // $authCode = trim(fgets(STDIN));

    // // Exchange authorization code for access token
    // $accessToken = $client->authenticate($authCode);
    // $client->setAccessToken($accessToken);
  }
}