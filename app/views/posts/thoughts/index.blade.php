@section('page.title')
I think..
@stop
@section('content.header')
@if (!Auth::guest())
  @if (Auth::user()->isAdmin())
    <a href="/thought/create">Post something</a>
  @endif
@endif
@stop
@section('content')
<article class="base-article">
** This section is being developed.
</article>
<article class="base-article">
<a class="twitter-timeline" href="https://twitter.com/JJcoms" data-widget-id="426072931311943680">Tweets by @JJcoms</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
@stop

@section('content-2')
  @if(isset($result))
   {{ var_dump($result) }}
  @endif

  @foreach ($posts as $post)
    <p class="thought-item"> {{ $post->content }} <span class="author-info">by <em class="author">{{ $post->user_name }}</em></span></p>
    @if (!Auth::guest())
      @if (Auth::user()->isAdmin())
        <div class="admin-controls">
          <a href="/admin/posts/edit?id={{ $post->id }}">[edit]</a>
          <a href="/admin/posts/delete?id={{ $post->id }}">[delete]</a>
        </div>
      @endif
    @endif
  @endforeach
</article>
@stop