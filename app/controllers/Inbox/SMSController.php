<?php namespace App\Inbox;

class SMSController extends \Controller
{
    public function __construct()
    {

    }

    public function store()
    {
      return Input::get();
    }

    public function index($count=4)
    {
      $sms = imap_open('{imap.gmail.com:993/imap/ssl}Personal/SMS', "joejiko@gmail.com", '$Goo2189$', OP_READONLY);
      $date = date("Y-m-d", strtotime("-1 week"));
      $emails = imap_search($sms, 'SINCE "'.$date.'"');
      rsort($emails, 1);
      $index=0; $output="";
      foreach($emails as $email_number) {
        if($index==$count) break;

        $overview = imap_fetch_overview($sms, $email_number, 0);
        $data = [
          'date_received' => $overview[0]->date,
          'from' => null,
          'from_phone' => '',
          'reply_to' => '',
          'missing' => false
        ];

        try {
          # parse subject
          preg_match('/\[?(\([0-9]{3}\) [0-9]{3}\-[0-9]{4})\]?/', $overview[0]->subject, $matches);
          if(!count($matches) > 1) throw new Exception('No match on subject.');
          $data['from_phone'] = $matches[1];
        } catch (Exception $e) {
          $data['missing']['message'][] = $e->getMessage();
          $data['missing']['subject'] = $overview[0]->subject;
        }

        try {
          # parse sender
          preg_match('/"([A-Za-z\" ]+)/', $overview[0]->from, $matches);
          if(!count($matches) > 1) throw new Exception('No match on from.');
          $data['from'] = $matches[1];
        } catch (Exception $e) {
          $data['missing']['message'][] = $e->getMessage();
          $data['missing']['from'][] = $overview[0]->from;
        }

        try {
          preg_match('/<(.*)>/', $overview[0]->from, $matches);
          $data['reply_to'] = $matches[1];
        } catch (Exception $e) {
          $data['missing']['message'][] = $e->getMessage();
          $data['missing']['from'][] = $overview[0]->from;
        }
        // $message = imap_fetchbody($sms, $email_number, "2");
        $data['body'] = imap_body($sms, $email_number);

        // var_dump(imap_body($sms, $email_number));
        // var_dump(imap_fetchstructure($sms, $email_number));

        $output .= \View::make('sms.inbox.message')
          ->with($data)->render();

        $index++;
      }
      imap_close($sms);

      $totals = "Displaying $count of ".count($emails)." SMS from the past 1 week";

      return \View::make('sms.inbox')
        ->with([
          'output' => $output,
          'totals' => $totals
        ]);
    }
}