<?php namespace Jiko\Service\Steam;

class SteamServiceProvider extends ServiceProvider {

  public function register()
  {
    $app = $this->app;

    $app['jiko.api.steam'] = $app->share(function() use ($app)
    {
      $config = $app['config'];

      $steam = new SteamApi(
        $config->get('jiko::steam.api_key'),
        $config->get('jiko::steam.endpoints.recently_played_games')
      );

      return $steam;
    });
  }

}