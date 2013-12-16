<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/
/** ------------------------------------------
* Route model binding
* ------------------------------------------
*/
// Route::model('user', 'User');
// Route::model('comment', 'Comment');
// Route::model('post', 'Post');
// Route::model('role', 'Role');

/** ------------------------------------------
* Admin Routes
* ------------------------------------------
*/
Route::group(array('prefix' => 'admin', 'before' => 'auth.admin'), function()
{
  // Route::controller('', 'AdminController');
  // Route::get('content', array('as' => 'admin.content', 'uses' => 'Admin\ContentController@getIndex'));
  # Deploy
  Route::get('/', 'HomeController@getAdmin');
  Route::controller('artwork', 'Admin\Post\ArtworkController');
  Route::controller('post', 'Admin\Post\PostController');
  Route::controller('terms', 'Admin\Post\TermController');
  Route::controller('deploy', 'Admin\DeployController');

  # Content management
  Route::resource('content', 'Admin\ContentController');
  Route::resource('questions', 'Admin\QuestionController');

  # Dashboard
  // Route::controller('/', 'AdminDashboardController');
});
/** API **/
Route::group(array('prefix' => 'api/v1', 'before' => 'auth.basic'), function()
{
  Route::resource('thoughts', 'ThoughtsController');
});
/** **/
Route::get('home', function(){ return Redirect::to('/'); });
Route::controller('api', 'ApiController');
Route::controller('oauth', 'OAuthController');
Route::controller('books', 'BookController');
Route::controller('contact', 'ContactController');
Route::controller('photos', 'PhotoController');
Route::controller('questions', 'QuestionController');
Route::get('question/{id}', array('as' => 'question', 'uses' => 'QuestionController@getOne'));
Route::get('artwork', array('as' => 'artwork', 'uses' => 'HomeController@getArtwork'));
Route::get('gaming', array('as' => 'gaming', 'uses' => 'HomeController@getGaming'));
Route::get('gaming/friends/{key}', array('as' => 'gaming/friend', 'uses' => 'HomeController@getGamingFriend'));
Route::get('about/me', array('as' => 'about', 'uses' => 'HomeController@getAbout'));
Route::get('about/privacy', array('as' => 'privacy', 'uses' => 'HomeController@getAboutPrivacy'));
Route::get('about/resume', array('as' => 'resume', 'uses' => 'HomeController@getResume'));
Route::get('labs', array('as' => 'pages.labs', 'uses' => 'HomeController@getLabs'));
Route::get('more', array('as' => 'pages.more', 'uses' => 'HomeController@getMore'));
Route::get('subscribe', array('as' => 'pages.subscribe', 'uses' => 'HomeController@getSubscribe'));
Route::get('music', array('as' => 'music', 'uses' => 'MusicController@getIndex'));
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
Route::get('/', array('as' => 'home', 'uses' => 'HomeController@getIndex'));
Route::get('support', array('as' => 'support', 'uses' => 'HomeController@getSupport'));
Route::get('support/wishlist', array('as' => 'support/wishlist', 'uses' => 'HomeController@getSupportWishlist'));
Route::controller('team', 'TeamController');
Route::controller('thoughts', 'ThoughtController');
Route::controller('posts', 'PostController');
Route::controller('thought', 'ThoughtController');
Route::get('user', array('as' => 'user.index', 'uses' => 'UserController@getIndex'));
Route::get('user/debug', array('as' => 'user.debug', 'uses' => 'UserController@getDebug'));
Route::get('user/connect', array('as' => 'connect', 'uses' => 'UserController@getConnect'));
Route::post('user/connect/email', array('as' => 'connect.email', 'uses' => 'UserController@doConnectEmail'));
Route::any('user/register/email', array('as' => 'register.email', 'uses' => 'UserController@doRegisterEmail'));
Route::get('user/connect/{action?}', array("as" => "hybridauth", 'uses' => 'UserController@doConnect'));
Route::get('user/connected', array('as' => 'connected', 'uses' => 'UserController@getConnected'));
Route::get('user/connected/missing-required-info', array('as' => 'user.missing_required_info', 'uses' => 'UserController@getMissingInfo'));
Route::get('user/disconnect', array('as' => "disconnect", 'uses' => 'UserController@getDisconnect'));
Route::get('user/info', array('as' => 'profile', 'uses' => 'UserController@getInfo'));
Route::get('web/clips', 'HomeController@getWeb');
Route::get('test/{label}', 'TestController@getIndex');
Route::post('/queue', function()
{
  return Queue::marshal();
}

Route::get('/{slug}', array('uses' => 'ContentController@page'));