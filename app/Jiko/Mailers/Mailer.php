<?php namespace Jiko\Mailers;

use Mail, User;

abstract class Mailer {

  protected $view;
  protected $data;
  protected $subject;

  public function sendTo(User $user)
  {

    $view = $this->view;
    $data = $this->data;
    $subject = $this->subject;

    return Mail::queue($view, $data, function($message) use($user, $subject)
    {
      $message->to($user->email)
              ->subject($subject);
    });
  }

}