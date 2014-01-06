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
Route::post('inbox/sms', function(){
  var_dump(Input::get());
});
Route::get('inbox/sms', function($count=4){
  $sms = imap_open('{imap.gmail.com:993/imap/ssl}Personal/SMS', "joejiko@gmail.com", '$Goo2189$', OP_READONLY);
  $date = date("Y-m-d", strtotime("-1 week"));
  $emails = imap_search($sms, 'SINCE "'.$date.'"');
  rsort($emails, 1);
  $index=0; $output="";
  foreach($emails as $email_number) {
    if($index==$count) break;

    $overview = imap_fetch_overview($sms, $email_number, 0);
    $data = [
      'date_received' => $overview[0]->date,
      'from' => null,
      'from_phone' => '',
      'reply_to' => '',
      'missing' => false
    ];

    try {
      # parse subject
      preg_match('/\[?(\([0-9]{3}\) [0-9]{3}\-[0-9]{4})\]?/', $overview[0]->subject, $matches);
      if(!count($matches) > 1) throw new Exception('No match on subject.');
      $data['from_phone'] = $matches[1];
    } catch (Exception $e) {
      $data['missing']['message'][] = $e->getMessage();
      $data['missing']['subject'] = $overview[0]->subject;
    }

    try {
      # parse sender
      preg_match('/"([A-Za-z\" ]+)/', $overview[0]->from, $matches);
      if(!count($matches) > 1) throw new Exception('No match on from.');
      $data['from'] = $matches[1];
    } catch (Exception $e) {
      $data['missing']['message'][] = $e->getMessage();
      $data['missing']['from'][] = $overview[0]->from;
    }

    try {
      preg_match('/<(.*)>/', $overview[0]->from, $matches);
      $data['reply_to'] = $matches[1];
    } catch (Exception $e) {
      $data['missing']['message'][] = $e->getMessage();
      $data['missing']['from'][] = $overview[0]->from;
    }
    // $message = imap_fetchbody($sms, $email_number, "2");
    $data['body'] = imap_body($sms, $email_number);

    // var_dump(imap_body($sms, $email_number));
    // var_dump(imap_fetchstructure($sms, $email_number));

    $output .= View::make('sms.inbox.message')->with($data)->render();

    $index++;
  }
  imap_close($sms);

  $totals = "Displaying $count of ".count($emails)." SMS from the past 1 week";

  return View::make('sms.inbox')->with([
    'output' => $output,
    'totals' => $totals
  ]);
});
Route::group(['prefix' => 'steam'], function(){
  Route::get('recently-played-games/{id?}', 'SteamController@recentlyPlayed');
});

/** ------------------------------------------
* Admin Routes
* ------------------------------------------
*/
Route::group(['prefix' => 'admin', 'before' => 'auth.admin'], function()
{
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

Route::group(['prefix' => 'archive'], function(){

  Route::any('twitter', 'Archive\TwitterArchiveController@search');

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

Route::group(['prefix' => 'contact'], function() {
  Route::get('/', ['uses' => 'ContactController@message']);
  Route::get('other', ['uses' => 'ContactController@other']);
  Route::any('message', ['uses' => 'ContactController@store']);
});

Route::controller('photos', 'PhotoController');
Route::controller('questions', 'QuestionController');
Route::get('question/{id}', array('as' => 'question', 'uses' => 'QuestionController@getOne'));
Route::get('artwork', array('as' => 'artwork', 'uses' => 'HomeController@getArtwork'));

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

Route::group(['prefix' => 'user'], function(){
  Route::get('tools/twitter-archive', 'User\Tools\TwitterArchiveController@show');
  Route::post('tools/twitter-archive', 'User\Tools\TwitterArchiveController@dump');
  Route::get('debug', array('as' => 'user.debug', 'uses' => 'UserController@getDebug'));
  Route::get('connect', array('as' => 'connect', 'uses' => 'UserController@getConnect'));
  Route::post('connect/email', array('as' => 'connect.email', 'uses' => 'UserController@doConnectEmail'));
  Route::any('register/email', array('as' => 'register.email', 'uses' => 'UserController@doRegisterEmail'));
  Route::get('connect/{action?}', array("as" => "hybridauth", 'uses' => 'UserController@doConnect'));
  Route::get('connected', array('as' => 'connected', 'uses' => 'UserController@getConnected'));
  Route::get('connected/missing-required-info', array('as' => 'user.missing_required_info', 'uses' => 'UserController@getMissingInfo'));
  Route::get('disconnect', array('as' => "disconnect", 'uses' => 'UserController@getDisconnect'));
  Route::get('info', array('as' => 'profile', 'uses' => 'UserController@getInfo'));
  Route::get('/', array('as' => 'user.index', 'uses' => 'UserController@getIndex'));
});
Route::get('web/clips', 'HomeController@getWeb');
Route::get('test/{label}', 'TestController@getIndex');
Route::post('/queue', function()
{
  return Queue::marshal();
});
Route::get('/{slug}', array('uses' => 'ContentController@page'));