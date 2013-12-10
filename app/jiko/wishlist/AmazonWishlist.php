<?php namespace Jiko\Wishlist;

use Config;
use phpQuery;

class AmazonWishlist implements WishlistInterface
{
  protected $url;
  protected $items;

  public function __construct()
  {
    $this->url = Config::get('jiko::amazon.wishlist.endpoint');
    var_dump($this->url);
    exit();
  }

  /**
   * get wishlist by id
   *
   * @param  string wishlist id
   * @param  array  URL query string
   * @return array of wishlist items
   */
  public function byId($id, $params=array())
  {
    $url = sprintf("%s/%s?%s", $this->url, $id, http_build_query($params));
    return $this->getContent($url);
  }

  protected function getContent($url)
  {
    $wishlist = [];

    try {

      if(!$contents = phpQuery::newDocumentFile($this->url)) throw new Exception("No contents.");

      $items = pq('.g-items-section div[id^="item_"]');
      foreach($items as $item):
        $name = trim(htmlentities(pq($item)->find('a[id^="itemName_"]')->html(), ENT_COMPAT|ENT_HTML401, 'UTF-8', FALSE));
        $link = pq($item)->find('a[id^="itemName_"]')->attr('href');

        if(!empty($name) && !empty($link)):

          $item = [
            'name'          => $name,
            'link'          => sprintf('%s%s', "http://www.amazon.com", $link),
            'price'         => trim(pq($item)->find('div.a-spacing-small div.a-row span.a-color-price')->html()),
            'date-added'    => trim(str_replace('Added', '', pq($item)->find('div[id^="itemAction_"] .a-size-small')->html())),
            'picture'       => pq($item)->find('div[id^="itemImage_"] img')->attr('src')
          ];

          $wishlist[] = $item;

        endif;
      endforeach;

    } catch (Exception $e) {
      return $e->getMessage();
    }

    return $wishlist;
  }
}