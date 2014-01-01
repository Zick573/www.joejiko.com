<?php namespace Jiko\Service\Form;

use Illuminate\Support\ServiceProvider;
use Jiko\Service\Form\Post\PostForm;
use Jiko\Service\Form\Post\PostFormLaravelValidator;

class FormServiceProvider extends ServiceProvider {
  public function register()
  {
    $app = $this->app;

    $app->bind('Jiko\Service\Form\Post\PostForm', function($app)
    {
      return new PostForm(
        new PostFormLaravelValidator( $app['validator'] ),
        $app->make('Jiko\Repo\Post\PostInterface')
      );
    });
  }
}