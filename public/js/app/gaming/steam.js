define(['jquery', 'app/ui/_global/tooltip'], function($, Tooltip){
  var steam = {
    data: {},
    endpoint: "/api/steam"
  };
  function getInfo(context, options) {
    var widget = false, $feed = context.find('.feed'), $loadMsg = context.find('.loading-message');
    $loadMsg.empty().append('Gathering data from Steam...');
    $.get(steam.endpoint, steam.data, function(json){
      var $recentlyPlayed = $("<ul />"), $game, limit;
      if(typeof(options) !== "undefined") {
        // set options
        if(typeof(options.limit) !== "undefined") {
          console.log("limit to: "+options.limit);
          limit = parseInt(options.limit);
        }
        if(typeof(options.widget) !== "undefined") {
          widget = options.widget;
        }
      }
      $(json.response.games).each(function(i, v){
        if(typeof(limit) !== "undefined") {
          if(i === limit){
            console.log("limit reached");
            return false;
          }
        }

        $game = $('<ul class="steam-game" data-tooltip="true" title="click to view game on Steam" data-app-id="'+v.appid+'" />');
        var playTimeHours = (parseInt(v.playtime_2weeks)/60).toFixed(2); /* hours */
        $game.append(
          $('<li class="steam-game-name">'+v.name+'</li>'),
          $('<li class="steam-game-time">'+playTimeHours+' hours played</li>'),
          $('<li class="steam-game-img">'+'<img src="http://media.steampowered.com/steamcommunity/public/images/apps/'+v.appid+'/'+v.img_logo_url+'.jpg">'));
        $game.appendTo($recentlyPlayed);
      });
      $feed.empty().append($recentlyPlayed);

      if(widget) {
        var footer = context.find('.count');
        footer.empty().append(json.response.total_count);
      }

      $(document).on('click', '.steam-game', function(evt) {
        window.open('http://steamcommunity.com/app/'+$(this).attr('data-app-id'));
      });

      Tooltip.start();
    });
  }

  function loadFriendGames(context) {
    $feed = context.find('.feed'), $loadMsg = context.find('.loading-message');
    $.get(steam.endpoint, {module: "friends"}, function(json){
      var $friendGames = $("<ul />"), $recentlyPlayed, $game, limit;
      $(json).each(function(index, friends){
        $(friends).each(function(j, data){
          $recentlyPlayed = $("<li />");
          $recentlyPlayed.append('<strong class="feed-title">'+data.friend+"</strong>");
          if(data.response.total_count === 0) {
            $recentlyPlayed.append('<ul><li>( no games played :[ )</li></ul>');
            $recentlyPlayed.appendTo($friendGames);
            return false;
          }
          $(data.response.games).each(function(i, g){
            // limit to 8
            if(i === 4){
              return false;
            }

            $game = $('<ul class="steam-game" data-tooltip="true" title="click to view game on Steam" data-app-id="'+g.appid+'" />');
            var playTimeHours = (parseInt(g.playtime_2weeks)/60).toFixed(2); /* hours */
            $game.append(
              $('<li class="steam-game-name">'+g.name+'</li>'),
              $('<li class="steam-game-time">'+playTimeHours+' hours played</li>'),
              $('<li class="steam-game-img">'+'<img src="http://media.steampowered.com/steamcommunity/public/images/apps/'+g.appid+'/'+g.img_logo_url+'.jpg">'));
            $game.appendTo($recentlyPlayed);
          });
          $recentlyPlayed.append('<ul><li><a class="steam-friend-games-link" data-action="steam/friend/recentlyplayed" data-friend="'+data.friend+'" href="/gaming/friends/'+data.friend+'">view all</a></li></ul>');
          $recentlyPlayed.appendTo($friendGames);
        });
      });
      $feed.empty().append($friendGames);
    });
  }

  function loadWidgets() {
    $(document).find('.steam-widget').each(function(i, e){
      var raw = "{"+$(e).attr('data-config')+"}";
      var options = JSON.parse(raw);
      getInfo($(e), options);
    });
  }

  function start() {
    getInfo( $(document).find('.steam') );
    loadFriendGames( $(document).find('.steam-friends') );
  }

  if($(document).find('.steam').length) {
    start();
  }

  if($(document).find('.steam-widget').length) {
    loadWidgets();
  }

  return {
    steam: {
      info: "loaded"
    }
  };
});