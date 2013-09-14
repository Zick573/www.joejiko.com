<?php



// define routes

$regexRoutes = array(

	array(
		"pattern" => "api/(.*)?",
		"keys" => array("all"),
		"controller" => "api",
		"action" => "index"
	),

	array(
		"pattern" => "apps/love-calculator(.*)?",
		"keys" => array("request"),
		"controller" => "apps",
		"action" => "lovecalculator"
	),

  array(
    "pattern" => "apps/twitter-manager(.*)?",
    "keys" => array("request"),
    "controller" => "apps",
    "action" => "twittermanager"
  ),

	array(
		"pattern" => "login/([a-z]*)/?([a-z]*)?",
		"keys" => array("service", "request"),
		"controller" => "users",
		"action" => "login"
	),

  array(
    'pattern' => 'user/login(.*)?',
    'keys' => array("redirect"),
    'controller' => 'home',
    'action' => 'login'
  )

);

$routes = array(
  array(
    "pattern" => "team",
    "controller" => "home",
    "action" => "team"
  ),

    array(
      "pattern" => "p/:id",
      "controller" => "photos",
      "action" => "view"
    ),

    array(

      "pattern" => "photos",
      "controller" => "photos",
      "action" => "index"

    ),

    array(
      "pattern" => "photos/manage",
      "controller" => "photos",
      "action" => "manage"
    ),

    array(

      "pattern" => "upload",
      "controller" => "photos",
      "action" => "upload"

    ),

    array(

      "pattern" => "resume",
      "controller" => "home",
      "action" => "resume"

    ),

    array(

      "pattern" => "apps",
      "controller" => "home",
      "action" => "apps"

    ),

    array(

      'pattern' => 'privacy-policy',
      'controller' => 'home',
      'action' => 'privacy'

    ),

    array(

      'pattern' => 'privacy',
      'controller' => 'home',
      'action' => 'privacy'

    ),

    array(
      "pattern" => "more/subscribe",
      "controller" => "home",
      "action" => "subscribe"
    ),

    array(
        "pattern" => "more",
        "controller" => "home",
        "action" => "more"
    ),

    array(
        "pattern" => "contact",
        "controller" => "contact",
        "action" => "index"
    ),

    array(
        "pattern" => "contact/:type",
        "controller" => "contact",
        "action" => "index"
    ),

    array(
        "pattern" => "music",
        "controller" => "music",
        "action" => "tracker"
    ),

    array(
        "pattern" => "music/view",
        "controller" => "music",
        "action" => "view"
    ),

    array(
        "pattern" => "register",
        "controller" => "users",
        "action" => "register"
    ),

    array(
        "pattern" => "login",
        "controller" => "users",
        "action" => "login"
    ),

    array(
        "pattern" => "user/logout",
        "controller" => "users",
        "action" => "logout"
    ),

    array(
        "pattern" => "profile",
        "controller" => "users",
        "action" => "profile"
    ),

    array(
        "pattern" => "settings",
        "controller" => "users",
        "action" => "settings"
    ),

    array(
        "pattern" => "unfriend/:id",
        "controller" => "users",
        "action" => "friend"
    ),

    array(
        "pattern" => "friend/:id",
        "controller" => "users",
        "action" => "friend"
    ),

    array(
        "pattern" => "fonts/:id",
        "controller" => "files",
        "action" => "fonts"
    ),

    array(
        "pattern" => "thumbnails/:id",
        "controller" => "files",
        "action" => "thumbnails"
    ),

    array(
        "pattern" => "users/edit/:id",
        "controller" => "users",
        "action" => "edit"
    ),

    array(
        "pattern" => "users/delete/:id",
        "controller" => "users",
        "action" => "delete"
    ),

    array(
        "pattern" => "users/undelete/:id",
        "controller" => "users",
        "action" => "undelete"
    ),

    array(
        "pattern" => "files/delete/:id",
        "controller" => "files",
        "action" => "delete"
    ),

    array(
        "pattern" => "files/undelete/:id",
        "controller" => "files",
        "action" => "undelete"
    ),

    array(
        "pattern" => "ask",
        "controller" => "questions",
        "action" => "ask"
    ),

    array(
        "pattern" => "questions",
        "controller" => "questions",
        "action" => "index"
    ),

    array(
      "pattern" => "questions/vote",
      "controller" => "questions",
      "action" => "vote"
    ),

    array(
      "pattern" => "question/:id",
      "controller" => "questions",
      "action" => "viewOne"
    ),

    array(
      "pattern" => "questions/ask",
      "controller" => "questions",
      "action" => "ask"
    ),

    array(
      "pattern" => "search",
      "controller" => "search",
      "action" => "index"
    ),

    array(
      "pattern" => "search/:query",
      "controller" => "search",
      "action" => "index"
    ),

    array(
      "pattern" => "tag/:query",
      "controller" => "search",
      "action" => "tag"
    ),

    array( /* user routes */
      'pattern' => 'user/login',
      'controller' => 'home',
      'action' => 'login'
    )

);



// add defined routes
foreach ($routes as $route)
{
    $router->addRoute(new Framework\Router\Route\Simple($route));
}

foreach ($regexRoutes as $route)
{
	$router->addRoute(new Framework\Router\Route\Regex($route));
}

// unset globals
unset($routes);

