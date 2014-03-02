<?php

return array(

  "debug_mode" => true,
  "debug_file" => app_path().'\storage\logs\hybridauth.txt',

  "base_url"   =>  Request::root()."/user/connect/provider",

  "providers"  => array (

    "Google"     => array(
      "enabled"    => true,
      "keys"       => array( "id" => "29103454985.apps.googleusercontent.com", "secret" => "p4tbDiXaL9_fCSGOGgRD01TJ" ),
      "scope" =>  "https://www.googleapis.com/auth/plus.login ". // optional
                  "https://www.googleapis.com/auth/plus.profile.emails.read", // optional
      "access_type" => "offline", // optional
      "approval_prompt" => "force" //optional
      ),

    "Facebook"   => array(
      "enabled"    => true,
      "keys"       => array( "id" => "160372647359458", "secret" => "93346ea35ab25f183a47ac95c4d7989a" ),
      "scope" => "email, user_about_me, user_birthday, user_hometown, user_location, user_interests, user_activities, user_website, user_likes, read_stream, offline_access, publish_stream, publish_actions, read_friendlists, friends_online_presence, manage_notifications" // optional
      // "display" => "popup" // optional
      ),

    "Twitter"    => array(
      "enabled"    => true,
      "keys"       => array( "key" => "tcnXFyUTQqE7qXni8gAg", "secret" => "ntXJMFkeTwNHsKdlr4vfSDT6IWOtzGXCxPkAuXijI" )
      ),

    "LinkedIn" => array( "enabled" => false, "keys" => array()),

    "Github" => array( "enabled" => false, "keys" => array()),

    "LastFM" => array( "enabled" => false, "keys" => array()),

    "Instagram" => array( "enabled" => false, "keys" => array()),

    "Tumblr" => array( "enabled" => false, "keys" => array()),

    "Steam Community" => array( "enabled" => false, "keys" => []),

    "TwitchTV" => [
      'enabled' => true,
      'keys' => [
        "id" => "f91l572aesk8byv4aa5mt8q392zn5fb",
        'secret' => '31g60pzr2e713b4qmxnl1d7c60ldafa',
        'redirect_uri' => 'local.joejiko.com/user/connect/twitchtv'
      ]
    ]

  ),
);