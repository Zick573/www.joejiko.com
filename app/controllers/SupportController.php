<?php

use Jiko\Repo\Amazon\WishlistInterface;
use Jiko\WebScraper\AmazonWishlistWebScraper;

class SupportController extends DefaultController
{
  public function index()
  {
    // $wishlist = new Amazon\Wishlist\Wishlist();
    // $wishlist_data = $wishlist->output();
    // $wishlist_html = View::make('pages.support.wishlist')
    //   ->with('wishlist_data', $wishlist_data)
    //   ->render();

    // return
    // View::make('pages.support')->with('amazon_wishlist', $wishlist_html);
  }

  public function wishlist()
  {
    // $wishlist = new Jiko\Repo\Amazon\ScraperWishlist(new AmazonWishlistWebScraper);
    // $wishlist_id = Config::get("jiko.amazon.wishlist.default_id");
    // $contents = $wishlist->byId($wishlist_id);

    // var_dump($contents);
    // exit();

    // $wishlist = new Amazon\Wishlist\Wishlist();
    // $wishlist_data = $wishlist->output();

    // if(array_key_exists('raw', $_GET)):
    //   var_dump($wishlist_data);
    //   die();
    // endif;

    // $wishlist_html = View::make('pages.support.wishlist')
    //   ->with('wishlist_data', $wishlist_data)
    //   ->render();

    // return
    // View::make('layouts.base.html')
    //   ->with(array(
    //     'title' => "Joe Jiko's Wishlist",
    //     'content' => $wishlist_html
    //   ));
  }
}