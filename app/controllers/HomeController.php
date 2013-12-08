<?php

class HomeController extends DefaultController {

  /*
  |--------------------------------------------------------------------------
  | Default Home Controller
  |--------------------------------------------------------------------------
  |
  | You may wish to use controllers instead of, or in addition to, Closure
  | based routes. That's great! Here is an example controller method to
  | get you started. To route to this controller, just add the route:
  |
  | Route::get('/', 'HomeController@showWelcome');
  |
  */
  public function getIndex()
  {
    $viewData = array(
      'title' => 'hello jiko',
    );
    return View::make('home', $viewData);
  }

  public function getAll($page)
  {
    // 404
    return View::make('errors.not-found')->with(array('request'=>$page));
  }

  public function getAdmin()
  {
    return View::make('admin.index');
  }

  public function getAbout()
  {
    return View::make('pages.about');
  }

  public function getAboutPrivacy()
  {
    return View::make('pages.about.privacy');
  }

  public function getArtwork()
  {
    return View::make('artwork')->with(array(
      'artworks' => Post::artwork()->get()
    ));
  }

  public function getGaming()
  {
    return View::make('gaming');
  }

  public function getGamingFriend($key)
  {
    $steam = new Steam;
    $friends = $steam->friend_ids();
    if(!array_key_exists($key, $friends)):
      return Redirect::to('gaming');
    endif;

    return View::make('gaming.friend')->with(
      'games', $steam->friend_games($friends[$key])
    );
  }

  public function getMore()
  {
    return View::make('pages.more');
  }

  public function getMusic()
  {
    $tracks = Music::all();
    return View::make('pages.music');
  }

  public function getResume()
  {
    return View::make('pages.resume');
  }

  public function getSubscribe()
  {
    return View::make('pages.subscribe');
  }

  public function getSupportWishlist()
  {
    $wishlist = new Amazon\Wishlist\Wishlist();
    $wishlist_data = $wishlist->output();

    if(array_key_exists('raw', $_GET)):
      var_dump($wishlist_data);
      die();
    endif;

    $wishlist_html = View::make('pages.support.wishlist')
      ->with('wishlist_data', $wishlist_data)
      ->render();

    return
    View::make('layouts.base.html')
      ->with(array(
        'title' => "Joe Jiko's Wishlist",
        'content' => $wishlist_html
      ));
  }

  public function getSupport()
  {
    $wishlist = new Amazon\Wishlist\Wishlist();
    $wishlist_data = $wishlist->output();
    $wishlist_html = View::make('pages.support.wishlist')
      ->with('wishlist_data', $wishlist_data)
      ->render();

    return
    View::make('pages.support')->with('amazon_wishlist', $wishlist_html);
  }

  public function getTeamJiko()
  {
    return View::make('team.join');
  }

  public function getWeb()
  {
    return View::make('web.clips');
  }

  public function getLabs()
  {
    return View::make('pages.labs');
  }

  public function missingMethod($parameters)
  {
    // missing
    return Redirect::to('home');
  }

}