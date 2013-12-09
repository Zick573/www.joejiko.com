<?php namespace Jiko\Service\Notification;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider {
  public function register()
  {
    $app = $this->app;

    $app['jiko.notifier'] = $app->share(function() use ($app)
    {
      $config = $app['config'];

      $emailer = new Services_Emailer();

      $notifier = new EmailNotifier( $emailer );

      $notifier->from( $config['emailer.from'] )
        ->to( $config['emailer.to'] );

      return $notifier;
    })
  }

  public function provides()
  {
    return ['jiko.notifier'];
  }
}