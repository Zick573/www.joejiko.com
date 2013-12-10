<?php namespace Jiko\Exception;

use Jiko\Service\Notification\NotifierInterface;

class NotifyHandler implements HandlerInterface {
  protected $notifier;

  public function __construct(NotifierInterface $notifier)
  {
    $this->notifier = $notifier;
  }

  /**
   * handle jiko exceptions
   *
   * @param  \Jiko\Exception\JikoException
   * @return void
   */
  public function handle(JikoException $exception)
  {
    $this->sendException($exception);
  }

  /**
   * send exception to notifier
   *
   * @param  \Exception $exception send notification of exception
   * @return void
   */
  protected function sendException(\Exception $e)
  {
    $this->notifier->notify('Error: '.get_class($e), $e->getMessage());
  }
}