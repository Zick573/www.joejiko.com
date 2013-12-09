<?php

  $steam = new Jiko\Widget\SteamWidget();
  $presenter = new Jiko\Widget\SteamWidgetPresenter();

  echo $presenter->render($steam);