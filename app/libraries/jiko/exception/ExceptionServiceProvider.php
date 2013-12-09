<?php namespace Jiko\Exception;

use Illuminate\Support\ServiceProvider;

class ExceptionServiceProvider extends ServiceProvider
{
  public function register()
  {
    $app = $this->app;

    $app['jiko.exception'] = $app->share(function($app)
    {
      return new NotifyHandler( $app['jiko.notifier'] );
    });
  }

  /**
   * boostrap the application events
   *
   * @return void
   */
  public function boot()
  {
    $app = $this->app;

    $app->error(function(JikoException $e) use ($app)
    {
      $app['jiko.exception']->handler($e);
    });
  }
}