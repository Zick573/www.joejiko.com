    <nav class="site-nav">
    {{ $triangle }}
    <a class="site-nav-labs btn-labs" href="/labs">
      <i data-icon="&#xF050;" class="batch"></i> Labs
    </a>
    <a class="site-nav-more btn-more" href="{{{ URL::to('more') }}}">
      <i data-icon="&#xF14C;" class="batch"></i> More stuff
    </a>
    <a class="site-nav-close btn-close" href=".">
      <i data-icon="&#xF14E;" class="batch"></i> Close menu
    </a>
    <a class="site-nav-home" href="{{{ URL::TO('') }}}">
      <i class="batch" data-icon="&#xF161;"></i>
    </a>
    <div class="site-nav-col">
      <a class="site-nav-link" href="/about/me">
        <i data-icon="&#xF046;" class="batch"></i> About me
      </a>
      <a class="site-nav-link" href="{{{ URL::to('support') }}}">
        <i data-icon="&#xF04B;" class="batch"></i> Support me
      </a>
      <a class="site-nav-link" href="{{{ URL::to('questions') }}}">
        <i data-icon="&#63;" class="modernpics"></i> Ask a Question
      </a>
      <a class="site-nav-link site-nav-link--sub" href="{{{ URL::to('questions') }}}">
        View FAQs
      </a>
      <a class="site-nav-link site-nav-link--sub" href="/about/resume">View Resume</a>
    </div><!--
    --><div class="site-nav-col">
      <a class="site-nav-link" href="{{{ URL::to('contact') }}}">
        <i data-icon="&#xF0CD;" class="batch"></i> Contact me
      </a>
      <a class="site-nav-link" href="{{{ URL::to('contact/message') }}}">
        <i data-icon="&#xF0CA;" class="batch"></i> Send message
      </a>
      <a class="site-nav-link site-nav-link--sub" href="{{{ URL::to('contact/feedback') }}}">
        <i data-icon="&#x270d;" class="modernpics"></i> Send feedback
      </a>
      <a class="site-nav-link site-nav-link--sub" href="{{{ URL::to('contact/request-new-project') }}}">
        <i data-icon="&#x1f527;" class="modernpics"></i> <span class="site-nav-link--smaller">Request new project</span>
      </a>
      <a class="site-nav-link site-nav-link--sub" href="{{{ URL::to('contact/report') }}}">
        <i data-icon="&#x1f4e3;" class="modernpics"></i> Report an issue
      </a>
    </div><!--
    --><div class="site-nav-col site-nav-col--primary">
    <a class="site-nav-link" href="/artwork">
      <i data-icon="&#xF139;" class="batch"></i> My Art
    </a>
    <a class="site-nav-link" href="{{{ URL::to('web/clips') }}}">
      <i data-icon="&#xF10E;" class="batch"></i> Bookmarks
    </a>
    <a class="site-nav-link" href="{{{ URL::to('contact') }}}">
      <i data-icon="&#xF0CD;" class="batch"></i> Contact
    </a>
    <a class="site-nav-link" href="{{{ URL::to('photos') }}}">
      <i data-icon="&#xF07A;" class="batch"></i> Photos
    </a>
    <a class="site-nav-link" href="{{{ URL::to('thoughts') }}}">
      <i data-icon="&#xF12C;" class="batch"></i> Thoughts
    </a>
    </div><!--
    --><div class="site-nav-col">
      <a class="site-nav-link" href="{{{ URL::to('photos') }}}">
        <i data-icon="&#xF07A;" class="batch"></i> Photos
      </a>
      <a class="site-nav-link site-nav-link--sub" href="{{{ URL::to('photos') }}}">&mdash; of Me</a>
      <a class="site-nav-link site-nav-link--sub" href="{{{ URL::to('photos/of/friends') }}}">&mdash; of Friends</a>
      <a class="site-nav-link site-nav-link--sub" href="{{{ URL::to('photos/of/zeah') }}}">&mdash; of Zeah</a>
      <a class="site-nav-link" href="{{{ URL::to('music') }}}">
        <i data-icon="&#9835;" class="batch"></i> Music
      </a>
    </div><!--
    --><div class="site-nav-col">
      <a class="site-nav-link" href="{{{ URL::to('thoughts') }}}">
        <i data-icon="&#xF12C;" class="batch"></i> Thoughts
      </a>
      <a class="site-nav-link" href="{{{ URL::to('thoughts/on/web') }}}">&mdash; on Web</a>
      <a class="site-nav-link" href="{{{ URL::to('thoughts/on/design') }}}">&mdash; on Design</a>
      <a class="site-nav-link site-nav-link--sub" href="{{{ URL::to('thoughts/on/stuff') }}}">&mdash; on Stuff</a>
      <a class="site-nav-link" href="{{{ URL::to('thoughts/popular') }}}">
        <i data-icon="&#xF15C;" class="batch"></i> most Popular
      </a>
    </div>
    </nav>
    <span class="helpful-hint">touch below here or move your pointer down to <em>close the menu</em></span>