<?php
class ContactController extends DefaultController {
  public function __construct()
  {
    parent::__construct();
  }
  /**
   * Send email to me from form
   * @return JSON [description]
   */
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
    if(!Auth::guest()):
      return View::make('contact');
    endif;

    return View::make('contact');
  }

  public function missingMethod($parameters)
  {
    return Redirect::to('contact');
  }
}