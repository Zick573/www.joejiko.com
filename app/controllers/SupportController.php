<?php

use Jiko\Repo\Amazon\WishlistInterface;
use Jiko\WebScraper\AmazonWishlistWebScraper;

class SupportController extends DefaultController
{
  public function __construct()
  {
    $this->wishlist = new Jiko\Amazon\Wishlist\Wishlist();
  }

  public function index()
  {
    $wishlist_html = View::make('pages.support.wishlist')
      ->with('wishlist_data', $this->wishlist->items)
      ->render();
    $this->layout->content = View::make('pages.support')->with('amazon_wishlist', $wishlist_html);
  }

  public function wishlist()
  {
    $wishlist_html = View::make('pages.support.wishlist')
      ->with('wishlist_data', $this->wishlist->items)
      ->render();

    return
    View::make('layouts.base.html')
      ->with(array(
        'title' => "Joe Jiko's Wishlist",
        'content' => $wishlist_html
      ));
  }
}