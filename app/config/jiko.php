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
  ],
  'amazon' => [
    'wishlist' => [
      'endpoint' => 'http://www.amazon.com/registry/wishlist',
      'id' => '10KWZ5ON6VU4N',
      'params' => [
        'reveal' => 'all',
        'sort' => 'updated'
      ],
      'sort_options' => [
        'added' => 'date-added',
        'priority' => 'priority',
        'title' => 'universal-title',
        'price high-to-low' => 'universal-price',
        'price low-to-high' => 'universal-price-desc',
        'updated' => 'last-updated'
      ]
    ]
  ]
];