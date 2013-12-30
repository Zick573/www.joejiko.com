<?php namespace Admin\Post;
use Keboola\Csv\CsvFile as CsvFile;
use DB, Form, Input, View;

class TwitterArchiveController extends \DefaultController {
  public function index()
  {
    // show upload form
    echo '<!doctype html><meta charset="utf-8">';
    echo Form::open(['files'=>true]);
    echo Form::file('csv');
    echo Form::button('submit', ['type' => 'submit']);
    echo Form::close();
    return;
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
          'tweet_id' => $row[0],
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

      $index++;
    }

    dd($errors);
    return;
  }
}