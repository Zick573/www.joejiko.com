<?php namespace Amazon\Wishlist;
use phpQuery;

/**
 * @todo cache data for at least 6 hours
 */
class Wishlist
{
  protected $config;
  protected $wishlist;

  /**
   * [defaultConfig description]
   * @return [type] [description]
   * @todo move to laravel app/config
   */
  public function defaultConfig()
  {
    return [
      'endpoint' => 'http://www.amazon.com/registry/wishlist',
      'id' => '10KWZ5ON6VU4N',
      'params' => [
        'reveal' => 'all',
        'sort' => 'updated'
      ],
      'format' => 'json'
    ];
  }

  /**
   * [setConfig description]
   */
  public function setConfig()
  {
    $sort_whitelist = ['date-added', 'priority','universal-title', 'universal-price', 'universal-price-desc', 'last-updated', 'date-added'];
  }

  public function __construct($config=array())
  {
    // error_reporting(0);
    // set_time_limit(60);
    // require_once('phpquery.php');
    $this->config = self::defaultConfig();
    $this->wishlist = $this->scrape();

    return $this;
  }

  /**
   * Updated Dec. 1, 2013
   * @return array of wishlist items
   */
  protected function scrape()
  {
    $config = $this->config;
    $url = sprintf("%s/%s?%s", $config['endpoint'], $config['id'], http_build_query($config['params']));
    $wishlist = [];

    // ok go!
    try {
      if(!$contents = phpQuery::newDocumentFile($url)) throw new Exception("No contents.");

      // // how many pages are there anyway?
      // $pages = count(pq('#wishlistPagination li[data-action="pag-trigger"]'));

      // // only one page
      // if(empty($pages)) $pages=1;

      // for($page_num=1; $page_num <= $pages; $page_num++)
      // {
        // "$baseurl/registry/wishlist/$amazon_id?$reveal&$sort&layout=standard&page=$page_num"
      // $page_url = sprintf("%s%s", $url, "page=".$page_num);
      // if(!$contents = phpQuery::newDocumentFile($page_url)) throw new Exception("No contents.");

      $items = pq('.g-items-section div[id^="item_"]');
      foreach($items as $item):
        $name = trim(htmlentities(pq($item)->find('a[id^="itemName_"]')->html(), ENT_COMPAT|ENT_HTML401, 'UTF-8', FALSE));
        $link = pq($item)->find('a[id^="itemName_"]')->attr('href');

        if(!empty($name) && !empty($link)):
          // $total_ratings = pq($item)->find('div[id^="itemInfo_"] div:a-spacing-small:first a.a-link-normal:last')->html();
          // $total_ratings = trim(str_replace(array('(', ')'), '', $total_ratings));
          // $total_ratings = is_numeric($total_ratings) ? $total_ratings : '';

          //$array[$i]['array'] = pq($item)->html();
          $item = [
            'name'          => $name,
            'link'          => sprintf('%s%s', "http://www.amazon.com", $link),
            'price'         => trim(pq($item)->find('div.a-spacing-small div.a-row span.a-color-price')->html()),
            'date-added'    => trim(str_replace('Added', '', pq($item)->find('div[id^="itemAction_"] .a-size-small')->html())),
            // 'priority'      => trim(pq($item)->find('span[id^="itemPriorityLabel_"]')->html()),
            // 'comment'       => trim(pq($item)->find('span[id^="itemComment_"]')->html()),
            'picture'       => pq($item)->find('div[id^="itemImage_"] img')->attr('src')
            // 'page'          => $page_num
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
  /**
   * [output description]
   * @param  string $format [description]
   * @return [type]         [description]
   */
  public function output()
  {
    // return json_encode($this->wishlist);
    return $this->wishlist;
  }
}