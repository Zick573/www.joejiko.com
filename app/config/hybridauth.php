<?php

return array(

  "base_url"   => "http://joejiko.com/user/connect/provider",

  "providers"  => array (

    "Google"     => array(
      "enabled"    => true,
      "keys"       => array( "id" => "29103454985.apps.googleusercontent.com", "secret" => "p4tbDiXaL9_fCSGOGgRD01TJ" ),
      "scope" =>  "https://www.googleapis.com/auth/userinfo.profile ". // optional
                  "https://www.googleapis.com/auth/userinfo.email", // optional
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

    "Steam Community" => array( "enabled" => false, "keys" => array())

  ),
);