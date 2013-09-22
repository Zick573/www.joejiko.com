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

return $app;