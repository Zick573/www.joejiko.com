<?php namespace Archive;
use DB, Form, Input, View;

class TwitterArchiveController extends \DefaultController {
  public function search()
  {
    if(Input::has('q')) return $this->searchQuery(Input::get('q'));

    if(Input::has('screen_name')) return $this->searchScreenName(Input::get('screen_name'));

    if(Input::has('show')) {
      if(Input::get('show') == 'mentions'):
        return $this->showMentions();
      endif;

      return $this->showLastYear();
    }

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

  public function showLastYear()
  {
    $result = DB::table('twitter_archive')
      ->whereRaw(DB::raw("str_to_date(`timestamp`, '%Y-%m-%d %H:%i:%s+0000') BETWEEN '2013-01-01 00:00:00' AND '2013-12-31 00:00:00'"))
      ->orderBy('timestamp', 'asc')
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
    $html .= '</ul>';
    return View::make('archive.index')->withContent($html);
  }

  public function searchById($id)
  {

  }

  public function showMentions()
  {
    $result = DB::table('twitter_archive')
      ->whereRaw("`text` LIKE '%@%'")
      ->whereRaw(DB::raw("str_to_date(`timestamp`, '%Y-%m-%d %H:%i:%s+0000') BETWEEN '2013-01-01 00:00:00' AND '2013-12-31 00:00:00'"))
      ->get();
    $mentions = [];

    foreach($result as $tweet) {
      preg_match("/(?<=^|\s)@([a-z0-9_]+)/i", $tweet->text, $matches);
      // hash tags
      // preg_match("$(?<=^|(?<=[^a-zA-Z0-9-\.]))#([A-Za-z]+[A-Za-z0-9-]+)$", $tweet->text, $matches);
      if(count($matches)) {
        $mentions[] = $matches[0];
      }
    }

    $count = [];

    foreach($mentions as $user) {
      if(!array_key_exists($user, $count)):
        $count[$user] = 1;
        continue;
      endif;

      $count[$user]++;
    }
    arsort($count);
    var_dump($count);
    exit();

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

  public function searchQuery($text, $html='')
  {
    if(Input::has('sort')):
      $result = DB::table('twitter_archive')
        ->whereRaw("MATCH(text) AGAINST ('".$text."')")
        ->orderBy( DB::raw("str_to_date(`timestamp`, '%Y-%m-%d %H:%i:%s+0000')"), 'asc')
        ->get();
    else:
      $result = DB::table('twitter_archive')
        ->whereRaw("MATCH(text) AGAINST ('".$text."')")
        ->get();
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