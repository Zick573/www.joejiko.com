<?php
$detect = new Mobile_Detect;
if( $detect->isMobile() ) {
  header('Location: http://mobile.joejiko.com');
  die();
}

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'admin', 'before' => 'auth.admin'), function()
{
  Route::controller('/', 'AdminController');
});
/** API **/
Route::group(array('prefix' => 'api/v1', 'before' => 'auth.basic'), function()
{
  Route::resource('thoughts', 'ThoughtsController');
});
/** **/
Route::get('/', array('as' => 'home', 'uses' => 'HomeController@getIndex'));
Route::get('home', function(){ return Redirect::to('/'); });
Route::controller('api', 'ApiController');
Route::get('artwork', array('as' => 'artwork', 'uses' => 'ArtController@getIndex'));
Route::controller('books', 'BookController');
Route::controller('contact', 'ContactController');
Route::get('gaming', array('as' => 'gaming', 'uses' => 'HomeController@getGaming'));
Route::controller('photos', 'PhotoController');
Route::get('about/me', array('as' => 'about', 'uses' => 'HomeController@getAbout'));
Route::get('about/resume', array('as' => 'resume', 'uses' => 'HomeController@getResume'));
Route::get('labs', array('as' => 'pages.labs', 'uses' => 'HomeController@getLabs'));
Route::get('more', array('as' => 'pages.more', 'uses' => 'HomeController@getMore'));
Route::get('music', array('as' => 'music', 'uses' => 'HomeController@getMusic'));
Route::controller('questions', 'QuestionController');
Route::get('question/{id}', array('as' => 'question', 'uses' => 'QuestionController@getOne'));
Route::get('subscribe', array('as' => 'pages.subscribe', 'uses' => 'HomeController@getSubscribe'));
Route::get('subscribe', function() {
  View::make('forms.subscribe');
});
Route::post('subscribe', function() {
  if(Input::has('email')){
    try {
      $api = new Mailchimp\MCAPI(Config::get('mailchimp.api_key'));
      $retval = $api->listSubscribe( Config::get('mailchimp.list_ids.default'), Input::get('email'));

      if ($api->errorCode){
        echo "Unable to load listSubscribe()!\n";
        echo "\tCode=".$api->errorCode."\n";
        echo "\tMsg=".$api->errorMessage."\n";
      } else {
          echo "Subscribed - look for the confirmation email!\n";
      }
    } catch( Exception $e) {
      echo "Something went wrong. \n".$e->getMessage();
    }
  }
  else
  {
    echo "Email is required";
  }

});
Route::get('support', array('as' => 'pages.support', 'uses' => 'HomeController@getSupport'));
Route::controller('team', 'TeamController');
Route::controller('thoughts', 'ThoughtController');
Route::controller('thought', 'ThoughtController');
Route::get('user', array('as' => 'user.index', 'uses' => 'UserController@getIndex'));
Route::get('user/debug', array('as' => 'user.debug', 'uses' => 'UserController@getDebug'));
Route::get('user/connect', array('as' => 'connect', 'uses' => 'UserController@getConnect'));
Route::post('user/connect/email', array('as' => 'connect.email', 'uses' => 'UserController@doConnectEmail'));
Route::get('user/connect/{action?}', array("as" => "hybridauth", 'uses' => 'UserController@doConnect'));
Route::get('user/connected', array('as' => 'connected', 'uses' => 'UserController@getConnected'));
Route::get('user/connected/missing-required-info', array('as' => 'user.missing_required_info', 'uses' => 'UserController@getMissingInfo'));
Route::get('user/disconnect', array('as' => "disconnect", 'uses' => 'UserController@getDisconnect'));
Route::get('user/info', array('as' => 'profile', 'uses' => 'UserController@getInfo'));
Route::get('web/clips', 'HomeController@getWeb');
Route::get('{page}', array('uses' => 'HomeController@getAll'));
// Route::get('thoughts', array('as' => 'thoughts', 'uses' => 'ThoughtController@index'));
// Route::get('thought/create', array('as' => 'thoughts.create', 'uses' => 'ThoughtController@create'));
// Route::post('thought/create', array('uses' => 'ThoughtController@createPost'));
// Route::get('thoughts/{page}', 'ThoughtController@showPage');
// $app->bind('ThoughtController', function($app) {
//   $repository = new ThoughtRepository;
//   $repository->setDatapath(base_path() . '/thoughts');

//   return new ThoughtController($repository);
// });
// Route::get('user/connect/status', array('as' => 'status', 'uses' => 'UserController@check'));
// $app->bind('UserController', function($app){
//   $controller = new UserController(
//     new Response,
//     $app->make('request'),
//     $app->make('validator'),
//     $app->make('hash'),
//     new User
//   );
//   return $controller;
// });