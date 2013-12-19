<?php

class WidgetController extends DefaultController
{
  public function __construct(Jiko\Widget\WidgetInterface $widget)
  {

  }

  public function steam()
  {
    return 'hello!';
  }

  public function steamFriends()
  {

  }
}