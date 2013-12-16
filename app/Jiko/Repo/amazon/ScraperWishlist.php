<?php namespace Jiko\Repo\Amazon;

class ScraperWishlist implements WishlistInterface
{

  protected $wishlist;

  public function __construct(AmazonWishlistWebScraper $wishlist)
  {
    $this->wishlist = $wishlist;
  }

  public function all()
  {
    return $this->wishlist->content();
  }

  public function byId($id)
  {
    return $this->wishlist->get($id);
  }
}