<?php
return [
  'steam' => [

    'api_key' => 'E82B48F7548BF20966AFED91C6E649FA',
    'methods' => [
      'RecentlyPlayedGames' => 'http://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/'
    ],

  // @todo move to database?
    'ids' => [
      'me' => '76561198058839919',
      'friends' => [
        'gimpy' => '76561198032148118',
        'vashton' => '76561197969364176',
        'bekah' => '76561198099283523',
        'zach' => '76561198079545715'
      ]
    ]
  ]
];