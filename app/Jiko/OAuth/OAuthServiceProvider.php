<?php namespace Jiko\OAuth;

use Hybrid_Auth;
use Illuminate\Support\ServiceProvider;

class OAuthServiceProvider extends ServiceProvider {

  public function register()
  {
    $app = $this->app;

    $app->bind('Jiko\OAuth\OAuthUserInterface', function($app)
    {
      return new HybridOAuthUser(new Hybrid_Auth(app_path() . '/config/hybridauth.php'));
    });
  }

}