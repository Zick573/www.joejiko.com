<?php
$app = new Illuminate\Foundation\Application;

$env = $app->detectEnvironment([
  'pcm-local' => ['gojira'],
  'local' => ['Jiko-PC'],
  'staging' => ['staging.*'],
  'development' => ['198.20.249.169']
]);

// changed in 4.1
// $env = $app->detectEnvironment(function()
// {

//   return getenv('JIKO_ENV') ?: 'local';

// });

$app->bindInstallPaths(require __DIR__.'/paths.php');

$framework = $app['path.base'].'/vendor/laravel/framework/src';

require $framework.'/Illuminate/Foundation/start.php';

if(!Session::has('isMobile')):
  try {
    $detect = new Mobile_Detect;
    Session::put('isMobile', $detect->isMobile());
  } catch ( Exception $e ) {
    echo 'Mobile detection exception: ' . $e->getMessage();
  }
endif;

App::bind('PostRepositoryInterface', function(){
  return new DbPostRepository;
});

// Session::put('isMobile', true);

// if(Session::get('isMobile')):
//   $app->bindInstallPaths(array(
//     'app' => __DIR__.'/../appm',
//     'public' => __DIR__.'/../public',
//     'base' => __DIR__.'/..',
//     'storage' => __DIR__.'/../app/storage',
//   ));
// endif;

return $app;