<?php namespace Archive;
use DB, Form, Input, View;

class TwitterArchiveController extends \DefaultController {
  public function search()
  {
    if(Input::has('q')) return $this->searchQuery(Input::get('q'));

    echo '<!doctype html><meta charset="utf-8">';
    echo Form::open(['method' => 'GET']);
    echo Form::text('q');
    echo Form::button('search', ['type' => 'submit']);
    echo Form::close();
    return;
  }

  public function searchQuery($text)
  {
    // SELECT * FROM jiko.twitter_archive where text like "%test%" order by abs(tweet_id) desc limit 20
    $result = DB::table('twitter_archive')->whereRaw("MATCH(".$text.") AGAINST ('text')")->get();
    foreach($result as $row) {
      echo $row->text."<br>";
    }

    return;
  }
}