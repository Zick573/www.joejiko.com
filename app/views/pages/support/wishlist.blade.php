<style>
.amazon-wishlist { letter-spacing: -1em; letter-spacing: -1rem; background: #eee; }
.amazon-wishlist .item-link { letter-spacing: normal; }
.item-link { display: inline-block; width: 24.99%; vertical-align: top; padding: 1em; padding: 1rem;}
.item-prop-list { list-style: none; padding: 0; margin: 0; }
</style>
<h1><a href="/support/wishlist">Wishlist</a> (on <a href="http://www.amazon.com/registry/wishlist/10KWZ5ON6VU4N" target="_blank">Amazon</a>) [{{ count($wishlist_data) }} items] updated {{ $wishlist_data[0]['date-added'] }}</h1>
<div class="amazon-wishlist">
@foreach($wishlist_data as $item)
<a class="item-link" href="{{ $item['link'] }}" target="_blank">
  <ul class="item item-prop-list">
    <li class="prop date">
      {{ $item['date-added'] }}
    </li>
    <li class="prop name">
      {{ $item['name'] }}
    </li>
    <li class="prop price">
      {{ $item['price'] }}
    </li>
    <li class="prop img">
      <img class="item-img" src="{{ $item['picture'] }}" alt="{{ $item['name'] }}">
    </li>
  </ul>
</a>
@endforeach
</div>