<?php namespace Jiko\Service\Notification;

class EmailNotifier implements NotifierInterface {
  protected $to;

  protected $from;

  protected $swift_mailer;

  public function __construct(Services_Emailer $swift_mailer)
  {
    $this->swift_mailer = $swift_mailer;
  }

  public function to($to)
  {
    $this->to = $to;

    return $this;
  }

  public function from($from)
  {
    $this->from = $from;

    return $this;
  }

  public function notify($subject, $message)
  {
    $email = $this->swift_mailer;
  }
}