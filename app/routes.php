<?php
/*
|--------------------------------------------------------------------------
| Experimental Routes
|--------------------------------------------------------------------------
*/
Route::resource('inbox/sms', 'App\Inbox\SMSController');
Route::group(['prefix' => 'archive'], function(){

  Route::any('twitter', 'Archive\TwitterArchiveController@search');

});
Route::controller('oauth', 'OAuthController');
Route::controller('books', 'BookController');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group([
  'prefix' => 'api/v1',
  'before' => 'auth.basic'
], function(){
  Route::resource('thoughts', 'ThoughtsController');
});
Route::controller('api', 'ApiController');

/** ------------------------------------------
* Admin Routes
* ------------------------------------------
*/
Route::group([
  'prefix' => 'admin',
  'before' => 'auth.admin'
], function(){
  // Route::controller('', 'AdminController');
  // Route::get('content', array('as' => 'admin.content', 'uses' => 'Admin\ContentController@getIndex'));
  # Deploy
  Route::get('twitter-archive', 'Admin\Post\TwitterArchiveController@index');
  Route::post('twitter-archive', 'Admin\Post\TwitterArchiveController@dump');
  Route::controller('artwork', 'Admin\Post\ArtworkController');
  Route::controller('post', 'Admin\Post\PostController');
  Route::controller('terms', 'Admin\Post\TermController');
  Route::controller('deploy', 'Admin\DeployController');

  Route::get('/', 'HomeController@getAdmin');
  Route::get('test-mail', ['uses' => 'ContactController@send']);

  # Content management
  // Route::resource('content', 'Admin\ContentController');
  Route::resource('questions', 'Admin\QuestionController');

  # Dashboard
  // Route::controller('/', 'AdminDashboardController');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/
Route::bind('questions', function($value, $route){
  return Question::find($value);
});

Route::bind('auth_provider', function($value, $route){
  return AuthProvider::where('name', $value)->firstOrFail();
});

/**
 * Artwork *
 */
Route::get('artwork', array('as' => 'artwork', 'uses' => 'HomeController@getArtwork'));

/**
 * Contact *
 */
Route::group(['prefix' => 'contact'], function() {
  Route::get('/', ['uses' => 'ContactController@message']);
  Route::get('other', ['uses' => 'ContactController@other']);
  Route::any('message', ['uses' => 'ContactController@store']);
});

/**
 * Photos *
 */

/**
 * Thoughts *
 */

/**
 * Questions/Ask *
 */
Route::group(['prefix' => 'questions'], function() {
  Route::get('ask', 'QuestionController@create');
  Route::post('ask', 'QuestionController@store');
});
Route::resource('questions', 'QuestionController');
Route::get('question/{questions}', 'QuestionController@show');

// Primary navigation

// Gaming/Steam
Route::group(['prefix' => 'steam'], function(){
  Route::get('recently-played-games/{id?}', 'SteamController@recentlyPlayed');
});

// Route::get('home', function(){ return Redirect::to('/'); });

Route::controller('photos', 'PhotoController');

// Gaming
Route::group(['prefix' => 'gaming'], function(){
  Route::get('/', array('as' => 'gaming', 'uses' => 'GamingController@index'));
  Route::get('friends/{key}', array('as' => 'gaming/friend', 'uses' => 'GamingController@friend'));
});

Route::group(['prefix' => 'about'], function(){

  Route::get('me', ['as' => 'about/me', 'uses' => 'AboutController@me']);
  Route::get('privacy', ['as' => 'privacy', 'uses' => 'AboutController@privacy']);
  Route::get('resume', ['as' => 'resume', 'uses' => 'AboutController@resume']);

});
Route::get('labs', array('as' => 'pages.labs', 'uses' => 'HomeController@getLabs'));
Route::get('more', array('as' => 'pages.more', 'uses' => 'HomeController@getMore'));
Route::get('music', array('as' => 'music', 'uses' => 'MusicController@getIndex'));

// Route::get('subscribe', array('as' => 'pages.subscribe', 'uses' => 'HomeController@getSubscribe'));
// Route::get('subscribe', function() {
//   View::make('forms.subscribe');
// });
// Route::post('subscribe', function() {
  // if(Input::has('email')){
  //   try {
  //     $api = new Mailchimp\MCAPI(Config::get('mailchimp.api_key'));
  //     $retval = $api->listSubscribe( Config::get('mailchimp.list_ids.default'), Input::get('email'));

  //     if ($api->errorCode){
  //       echo "Unable to load listSubscribe()!\n";
  //       echo "\tCode=".$api->errorCode."\n";
  //       echo "\tMsg=".$api->errorMessage."\n";
  //     } else {
  //         echo "Subscribed - look for the confirmation email!\n";
  //     }
  //   } catch( Exception $e) {
  //     echo "Something went wrong. \n".$e->getMessage();
  //   }
  // }
  // else
  // {
  //   echo "Email is required";
  // }
// });

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@getIndex'));

Route::group(['prefix' => 'support'], function(){
  Route::get('/', array('as' => 'support', 'uses' => 'SupportController@index'));
  Route::get('wishlist', array('as' => 'wishlist', 'uses' => 'SupportController@wishlist'));
});
Route::controller('team', 'TeamController');
Route::group(['prefix' => 'thoughts'], function(){
  Route::get('/', 'ThoughtController@index');
  Route::group(['prefix' => 'on'], function() {
    Route::get('/', function(){
      header('location: /thoughts');
      exit();
    });
    Route::get('web', 'ThoughtController@onWeb');
    Route::get('design', 'ThoughtController@onDesign');
    Route::get('stuff', 'ThoughtController@onStuff');
  });
  Route::get('popular', 'ThoughtController@index');
});

Route::group(['prefix' => 'thought'], function(){
  Route::any('create', 'ThoughtController@create');
});

Route::controller('posts', 'PostController');
Route::controller('thought', 'ThoughtController');

/**
 * User
 */
Route::group(['prefix' => 'user'], function(){
  Route::group(['prefix' => 'tools'], function() {
    Route::get('twitter-archive', 'User\Tools\TwitterArchiveController@show');
    Route::post('twitter-archive', 'User\Tools\TwitterArchiveController@dump');
  });
  Route::get('debug', 'UserController@debug');
  Route::get('connect/provider', 'UserController@connectWithProvider');
  Route::get('connect/{auth_provider}', 'UserController@connectWithProvider');
  Route::get('connect', 'UserController@connect');
  Route::any('connect/email', 'UserController@connectWithEmail');
  // Route::get('connect/{action?}', 'UserController@connectWithOAuth');

  Route::get('connected', 'UserController@connected');
  Route::get('connected/missing-required-info', 'UserController@missingInfo');

  Route::get('disconnect', 'UserController@disconnect');
  Route::get('info', 'UserController@info');
  Route::get('info/import', 'UserController@infoImport');
  Route::get('/', 'UserController@index');
});

Route::get('web/clips', 'HomeController@getWeb');
Route::get('test/{label}', 'TestController@getIndex');
Route::post('/queue', function()
{
  return Queue::marshal();
});

Route::get('/{slug}', 'ContentController@page');