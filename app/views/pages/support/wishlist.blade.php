<style>
  * { box-sizing: border-box; -moz-box-sizing: border-box; }
  body { margin: 0; }
  .amazon-wishlist { letter-spacing: -.25rem; }
  .amazon-wishlist .item-link { letter-spacing: normal; }
  .item-link { display: inline-block; width: 25%; vertical-align: top; background: #eee; padding: 1em; padding: 1rem; }
  .item-prop-list { list-style: none; padding: 0; margin: 0; }
</style>
<h1>Wishlist ({{ count($wishlist_data) }} items)</h1>
<div class="amazon-wishlist">
@foreach($wishlist_data as $item)
<a class="item-link" href="{{ $item['link'] }}">
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