<?php namespace Jiko\WebScraper;

interface WebScraperInterface {

  public function get($url, $params=[]);

}