<?php namespace Jiko\Mailers;

class UserMailer extends Mailer {

  public function welcome()
  {
    $this->view = 'emails.welcome';
    $this->data = [];
    $this->subject = 'Welcome to JoeJiko.com';

    return $this;
  }

  public function subscribe($events)
  {
    $events->listen('user.signup', 'Jiko\Mailers\UserMailer@welcome');
  }
}