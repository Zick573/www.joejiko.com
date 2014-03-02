<?php namespace Jiko\OAuth;

use User, UserConnection, Hybrid_Auth;
use Jiko\Repo\User\HybridAuthUser;
use Illuminate\Support\ServiceProvider;

class OAuthServiceProvider extends ServiceProvider {

  public function register()
  {
    $app = $this->app;

    $app->bind('Jiko\OAuth\OAuthUserInterface', function($app)
    {
      return new HybridAuthUser(
        new User,
        new Hybrid_Auth(app_path() . '/config/hybridauth.php'),
        new UserConnection
      );
    });
  }

}