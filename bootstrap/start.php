<?php
$app = new Illuminate\Foundation\Application;
$app->redirectIfTrailingSlash();

$env = $app->detectEnvironment([
  'local' => ['local.*', '127.0.0.1', 'jjcom.dev'],
  'staging' => ['staging.*'],
  'development' => ['198.20.249.169']
]);

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