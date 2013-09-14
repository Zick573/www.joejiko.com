<?php

use Shared\Controller as Controller;
use Framework\View as View;
use Framework\ArrayMethods as ArrayMethods;
use Framework\RequestMethods as RequestMethods;

class Share extends Controller
{

    public function index()
    {
      // save stats and redirect to service
    }

    public function facebook($url, $title)
    {
      // https://www.facebook.com/sharer/sharer.php?u={$url}&t={$title}
    }
}