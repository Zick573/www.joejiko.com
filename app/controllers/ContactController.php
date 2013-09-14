<?php
class ContactController extends BaseController {
  public function __construct()
  {
    parent::__construct();
  }
  public function postSendMessage()
  {
    if(Input::has('email')) {
      Mail::send('emails.default', $data, function($message)
      {
        $message
          ->from('bot@joejiko.com', 'JikoBot')
          ->to('me@joejiko.com', 'joe jiko')
          ->subject('message from contact form');
      });
    }
    // return JSON
  }

  public function getMessage()
  {
    return View::make('contact.message');
  }

  public function getOther()
  {
    return View::make('contact.other');
  }

  public function getIndex()
  {
    if($this->user->role < 2):
      return View::make('contact.dev');
    endif;

    return View::make('contact');
  }

  public function missingMethod($parameters)
  {
    return Redirect::to('contact');
  }
}