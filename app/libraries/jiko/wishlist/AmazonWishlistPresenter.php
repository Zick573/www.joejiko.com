<?php namespace Jiko\WebScraper;

use phpQuery;

class AmazonWishlistPresenter
{
  public function __construct(AmazonWishlistInterface $wishlist)
  {
    $this->wishlist = $wishlist;
  }

  public function render()
  {
    return $this->wishlist->content;
  }
}