<?php namespace User\Tools;

use Keboola\Csv\CsvFile as CsvFile;
use AuthSession;
use UserConnection;
use Auth, Config, DB, Form, Input, Request, Session, View;

class TwitterArchiveController extends \DefaultController {

  public function index($identifier)
  {
    // show upload form
    $html = '<div class="tweet"><h1>Import your archive</h1>'
    . "<p>Choose a file and then click import<br><br> This might take awhile.. don't leave or do anything unnecessary.</p></div>"
    . Form::open(['files'=>true])
    . Form::hidden('twitter_uid', $identifier)
    . Form::file('csv')
    . Form::button('import your archive', ['type' => 'submit'])
    . Form::close();
    return View::make('user.tools.twitter-archive')->withContent($html);
  }

  public function missingInfo()
  {
    Session::set('missing_email_checkpoint', true);
    $html = View::make('user.tools.twitter-archive.update-email')->render();
    return View::make('user.tools.twitter-archive')->withContent($html);
  }

  public function connect()
  {
    Session::put('connected_from_url', Request::url());
    $html = View::make('user.tools.twitter-archive.connect')->render();
    return View::make('user.tools.twitter-archive')->withContent($html);
  }

  public function show()
  {
    # require authentication
    if(Auth::guest()) return $this->connect();

    # require email
    if(Auth::user()->status == 'limited') {
      if(!Session::has('missing_email_checkpoint')) {
        return $this->missingInfo();
      }
    }

    # check if user has a twitter account assigned to them
    if($twitter_user_id = UserConnection::where('user_id', Auth::user()->id)
      ->where('provider_name', 'twitter')
      ->pluck('provider_uid')) {

      # check if the user has uploaded their archive already
      if(!$user_has_archive = DB::table('twitter_archive_users')->where('user_id', Auth::user()->id)->first()) {

        $hybridauth = new \Hybrid_Auth(Config::get('hybridauth'));
        $twitter = $hybridauth->getAdapter("Twitter");
        $profile = $twitter->getUserProfile();

        return $this->index($profile->identifier);

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
    # verify twitter user assigned
    if(!Input::has('twitter_uid')) return "Missing twitter user..";
    $twitter_uid = Input::get('twitter_uid');

    # verify file is CSV
    $file = Input::file('csv');
    if('csv' != $file->getClientOriginalExtension()) return "Not a CSV file..";

    # process file and store in database
    $file_path = $file->getRealPath();
    $csvFile = new CsvFile($file_path);

    $index=0;
    $errors=0;
    $duplicates=0;

    $prepared_rows = [];
    echo "Total lines in CSV: ".self::getLines($file->getRealPath());
    echo '<div class="wrapper" style="max-height: 250px; overflow-y: auto;">';
    foreach($csvFile as $row) {

      # skip first row (column list)
      if($index == 0) {
        # next
        $index++; continue;
      }

      # execute
      try {
        if(
          DB::table('twitter_archive')->where('twitter_user_id', $twitter_uid)
            ->where('tweet_id', $row[0])
            ->count()
        ){
          # next
          # mark as duplicate
          $index++;$duplicates++;continue;
        }

        DB::table('twitter_archive')->insert([
          'twitter_user_id' => Input::get('twitter_uid'),
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

        # mark as error
        $index++; $errors++;

        /**
         * @todo create new CSV with bad values to add later
         */

        # output error message
        echo "something went wrong!<br>"
        .$row[0]."--".$e->getMessage()
        ."<br><br>";

        # next
        continue;

      }

      # stop at 1000
      if($index >= 7000) break;

      # next
      $index++;
    }
    echo '</div>';
    echo "# of rows not added: $errors<br>";
    echo "# of rows in DB: ";
    echo DB::table('twitter_archive')->count();

    # mark as twitter-archive user
    DB::table('twitter_archive_users')->insert([
      'user_id' => Auth::user()->id,
      'twitter_user_id' => Input::get('twitter_uid'),
      'created_at' => date('c')
    ]);
    return;
  }

  public function search($twitter_user_id)
  {
    if(Input::has('q')) return $this->searchQuery($twitter_user_id, Input::get('q'));

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

  public function searchQuery($twitter_user_id, $text, $html='')
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
    . '<p class="info-total">of '.DB::table('twitter_archive')->where('twitter_user_id', $twitter_user_id)->count().' total tweets</p></header>';
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