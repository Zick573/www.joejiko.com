<?php
class GoogleController extends BaseController
{
  protected $client;

  public function getDrive()
  {
    $this->service = new \GoogleApi\Contrib\Google_DriveService($this->client);
    $authUrl = $client->createAuthUrl();

    //Request authorization
    print "Please visit:\n$authUrl\n\n";
    print "Please enter the auth code:\n";
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for access token
    $accessToken = $client->authenticate($authCode);
    $client->setAccessToken($accessToken);
  }
  public function __construct()
  {
    $client = new \GoogleApi\Client();
    $client->setClientId('29103454985.apps.googleusercontent.com');
    $client->setClientSecret('p4tbDiXaL9_fCSGOGgRD01TJ');
    $client->setRedirectUri('http://local.joejiko.com/api/google');
    $client->setScopes(array('https://www.googleapis.com/auth/drive'));
    $client->setUseObjects(true);

    $this->client = $client;
  }
}