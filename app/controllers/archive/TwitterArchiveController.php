<?php namespace Archive;
use DB, Form, Input, View;

class TwitterArchiveController extends \DefaultController {
  public function search()
  {
    if(Input::has('q')) return $this->searchQuery(Input::get('q'));

    $result = DB::table('twitter_archive')
      ->orderby('timestamp', 'desc')
      ->take(250)
      ->get();
    $html = '<ul class="tweets">';
    foreach($result as $row) {
      $html .= '<li class="tweet">'
      . '<span class="col text">'.$row->text.'</span><!--'
      . '--><a class="col link-tweet" href="http://twitter.com/JoeJiko/status/'.$row->tweet_id.'">'
      . '<time class="timestamp">'.date("Y-m-d", strtotime($row->timestamp)).'</time>'
      . '</a>'
      . '</li>';
    }
    $html .= '<li class="tweet end-of-tweets">Older tweets are currently unavailable.. trying <a href="#top">searching</a> instead?';
    $html .= '</ul>';
    return View::make('archive.index')->withContent($html);
  }

  public function filterYear()
  {
    $sql = "SELECT tweet_id, text, timestamp FROM twitter_archive
    WHERE  str_to_date(`timestamp`, '%Y-%m-%d %H:%i:%s+0000')
    BETWEEN  '2008-01-01 00:00:00'
    AND '2013-12-31 00:00:00'
    ORDER BY str_to_date(`timestamp`, '%Y-%m-%d %H:%i:%s+0000') ASC
    LIMIT 10";
  }

  public function searchQuery($text, $html='')
  {
    if(Input::has('sort')):
      $result = DB::table('twitter_archive')
        ->whereRaw("MATCH(text) AGAINST ('".$text."')")
        ->orderBy( DB::raw("str_to_date(`timestamp`, '%Y-%m-%d %H:%i:%s+0000')"), 'asc')
        ->get();
    else:
      $result = DB::table('twitter_archive')->whereRaw("MATCH(text) AGAINST ('".$text."')")->get();
    endif;
    $html .= '<header class="content-header"><h1 class="view-title">Twitter archive</h1>'
    . '<h2 class="info-matches">'.count($result).' matches for <em>'.$text.'</em>&nbsp;</h2>'
    . '<p class="info-total">of '.DB::table('twitter_archive')->count().' total tweets</p></header>';
    $html .= '<ul class="tweets">';
    foreach($result as $row) {
      $html .= '<li class="tweet">'
      . '<span class="col text">'.$row->text.'</span><!--'
      . '--><a class="col link-tweet" href="http://twitter.com/JoeJiko/status/'.$row->tweet_id.'">'
      . '<time class="timestamp">'.date("Y-m-d", strtotime($row->timestamp)).'</time>'
      . '</a>'
      . '</li>';
    }
    $html .= '</ul>';

    return View::make('archive.index')->withContent($html);
  }
}