<?php
class ContactController extends DefaultController {
  public function __construct()
  {
    parent::__construct();
  }
  public function send()
  {
    // dd(mail('me@joejiko.com', 'subject', 'message'));
    $store = [
      'from' => 'Joe Jiko <me@joejiko.com>',
      'subject' => 'testing',
      'body' => 'test body'
    ];
    $sent = Mail::queue('emails.contact.message', $store, function($message) use($store)
    {
      $message->to('me@joejiko.com')
              ->subject("[Contact] ".$store['subject']);
    });
    dd($sent);
  }
  /**
   * Send email to me from form
   * @return JSON [description]
   */
  public function store()
  {
    if(Request::ajax()) {
      $input = Input::get('data');

      // normalize input
      foreach($input as $index => $values):
        $data[$values['name']] = $values['value'];
      endforeach;

      $store = [
        'to' => "Joe Jiko <me@joejiko.com>",
        'from' => sprintf("%s <%s>", $data['sender[name]'], $data['sender[email]']),
        'subject' => $data['message[subject]'],
        'body' => $data['message[body]']
      ];

      $sent = Mail::queue('emails.contact.message', $store, function($message) use($store)
      {
        $message->to('me@joejiko.com')
                ->subject("[Contact] ".$store['subject']);
      });

      return Response::json([
        "response" => $sent
      ]);
    }

    if(Input::has('sender[email]')) {
      // Mail::send('emails.default', $data, function($message)
      // {
      //   $message
      //     ->from('bot@joejiko.com', 'JikoBot')
      //     ->to('me@joejiko.com', 'joe jiko')
      //     ->subject('message from contact form');
      // });
      return "could be legit";
    }

    return "not legit.";
  }

  public function other()
  {
    return View::make('contact.other');
  }

  public function message()
  {
    return View::make('contact');
  }

  public function missingMethod($method, $parameters=[])
  {
    return Redirect::to('contact');
  }
}