<?php namespace App\Site;

class SiteServiceProvider extends \Illuminate\Support\ServiceProvider {
  public function boot()
  {
    $this->package('app/site', 'site', public_path() . '/spine');
  }

  public function register()
  {
    require public_path() . '/spine/routes.php'
  }
}