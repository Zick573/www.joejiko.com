<?php namespace Jiko\Amazon\Wishlist;
use Config;
use phpQuery;
class Scraper
{
  protected $config = [];
  public function __construct($params=[])
  {
    $this->config = Config::get('jiko.amazon.wishlist');
  }

  /**
   * get wishlist by id
   *
   * @param  string wishlist id
   * @param  array  URL query string
   * @return array of wishlist items
   */
  public function byId($endpoint, $id, $params=array())
  {
    $url_with_params = sprintf("%s/%s?%s", $endpoint, $id, http_build_query($params));
    return $url_with_params;
  }

  /**
   * Updated Mar. 10, 2013
   *
   * @return array of wishlist items
   */
  public function get($endpoint='')
  {
    $url_with_params = static::byId($this->config['endpoint'], $this->config['id'], $this->config['params']);
    $wishlist = [];
    try {
      if(!$contents = phpQuery::newDocumentFile($url_with_params)) throw new Exception("No contents.");
      $items = pq('.g-items-section > div[id^="item_"]');
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
      // }
    } catch (Exception $e) {
      return $e->getMessage();
    }

    return $wishlist;
  }
}