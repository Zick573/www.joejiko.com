<?php namespace Jiko\Service\Notification;

interface NotifierInterface {

  /**
   * recipients of notification
   * @param  [type] $to [description]
   * @return [type]     [description]
   */
  public function to($to);

  /**
   * sender of notification
   * @param  [type] $from [description]
   * @return [type]       [description]
   */
  public function from($from);

  /**
   * send notification
   *
   * @param  [type] $subject [description]
   * @param  [type] $message [description]
   * @return [type]          [description]
   */
  public function notify($subject, $message);

}