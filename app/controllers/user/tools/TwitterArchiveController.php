<?php namespace User\Tools;

use Keboola\Csv\CsvFile as CsvFile;
use UserConnection;
use Auth, DB, Form, Input, View;

class TwitterArchiveController extends \DefaultController {

  public function index()
  {
    // show upload form
    $html = '<h1>Upload your archive</h1>'
    . Form::open(['files'=>true])
    . Form::file('csv')
    . Form::button('submit', ['type' => 'submit'])
    . Form::close();
    return View::make('user.tools.twitter-archive')->withContent($html);
  }

  public function connect()
  {
    $html = View::make('user.tools.twitter-archive.connect')->render();
    return View::make('user.tools.twitter-archive')->withContent($html);
  }

  public function show()
  {
    // REMOVE ME
    // $user = Auth::loginUsingId(1);
    if(Auth::guest()) return $this->connect();

    // check if user has a twitter account assigned to them
    if($twitter_user_id = UserConnection::where('user_id', Auth::user()->id)
      ->where('provider_name', 'twitter')
      ->pluck('provider_uid')) {

      // check if the user has uploaded their archive already
      if(!$user_has_archive = DB::table('twitter_archive_users')->where('user_id', Auth::user()->id)->first()) {

        return $this->index();

      }

      return $this->search($twitter_user_id);
    }

    return $this->connect();
  }

  public function chunk()
  {
    // split CSV into chunks
  }

  public function read($start=0, $offset=1000)
  {
    // foreach
  }

  public function save($row=[])
  {

  }

  public function orderById()
  {
    // SELECT * FROM jiko.twitter_archive order by abs(tweet_id) asc limit 10;
  }

  public static function getLines($file)
  {
    $f = fopen($file, 'rb');
    $lines = 0;

    while (!feof($f)) {
        $lines += substr_count(fread($f, 8192), "\"\n\"");
    }

    fclose($f);

    return $lines;
  }

  public function dump()
  {
    // output CSV in readable form
    $file = Input::file('csv');
    $file_path = $file->getRealPath();

    $csvFile = new CsvFile($file_path);
    $index = 0;
    $errors = 0;

    $prepared_rows = [];
    echo "Total lines in CSV: ".self::getLines($file->getRealPath());
    echo '<div class="wrapper" style="max-height: 250px; overflow-y: auto;">';
    foreach($csvFile as $row) {
      if($index == 0) {
        // column names
        $index++;
        continue;
      }

      // $prepared_rows[] =
      // execute
      try {
        DB::table('twitter_archive')->insert([
          'tweet_id' => $row[0], // unique
          'in_reply_to_status_id' => $row[1],
          'in_reply_to_user_id' => $row[2],
          'timestamp' => $row[3],
          'source' => $row[4],
          'text' => $row[5],
          'retweeted_status_id' => $row[6],
          'retweeted_status_user_id' => $row[7],
          'retweeted_status_timestamp' => $row[8],
          'expanded_urls' => $row[9],
          'created_at' => date("c")
        ]);

        echo "OK! Added ".$row[0]."<br>";
      } catch (\Exception $e) {
        $errors++;
        // create new CSV with bad values to add later
        // echo "something went wrong! ".$row[0]."--".$e->getMessage();

      }

      if($index >= 1000) break;

      $index++;
    }
    echo '</div>';
    echo "# of rows not added: $errors<br>";
    echo "# of rows in DB: ";
    dd(DB::table('twitter_archive')->count());
    return;
  }

  public function search($twitter_user_id)
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
      ->where('twitter_user_id', $twitter_user_id)
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

    return View::make('user.tools.twitter-archive')
      ->with([
        'content' => $html,
        'tw_uid' => $twitter_user_id
      ]);
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
    return View::make('user.tools.twitter-archive')->withContent($html);
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
    return View::make('user.tools.twitter-archive')->withContent($html);
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

    return View::make('user.tools.twitter-archive')->withContent($html);
  }
}