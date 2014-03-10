<?php namespace Jiko\Amazon\Wishlist;

class Wishlist
{
  public $items;
  public function __construct()
  {
    $scraper = new Scraper;
    $this->items = $scraper->get();
  }
}