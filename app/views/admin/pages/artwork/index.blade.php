@extends('layouts.admin.master')
@section('page.title')
  Artwork management
@stop
@section('page.styles')
<style>
* { font-family: arial; }
.main { width: 100%; }
.admin-controls, .content { width: 50%;}
ul { list-style: none; margin: 0; padding: 0;}
.drive-item {
  display: inline-block;
  vertical-align: top;
  width: 25%;
}
.drive-img {
  vertical-align: top;
  width: 100%;
}
.item-title { font-size: .8rem; vertical-align: top; }

.artwork-controls { border-bottom: 1px solid; padding: 1rem; }
.form-item, .form-item label {
  display: block;
}
.properties { width: 280px; }
.col input { width: 100%; }
.placeholder {
  width: 220px;
  background: #ccc;
  min-height: 250px;
}
</style>
@stop
@section('content')
<div class="artwork">
<header class="artwork-controls">
{{ Form::open() }}
{{ Form::hidden('id') }}
{{ Form::hidden('title') }}
{{ Form::hidden('mimeType') }}
{{ Form::hidden('thumbnailLink') }}
<div class="col placeholder"></div>
<div class="col properties">
  <div class="form-item"><span id="id">(id)</span></div>
  <div class="form-item"><span id="filename">(filename)</span></div>
  <div class="form-item"><span id="mimetype">(mime/type)</span></div>
  <div class="form-item">
    {{ Form::label('collection') }}
    {{ Form::text('collection') }}
  </div>

  <div class="form-item">
    {{ Form::label('tags') }}
    {{ Form::text('tags') }}
  </div>

  <div class="form-item">
    {{ Form::label('description') }}
    {{ Form::textarea('description') }}
  </div>
</div>

<div class="form-item">
  {{ Form::button('save', array('type' => 'submit')) }}
</div>

{{ Form::close() }}
</header>
  @if(count($artwork))
    <h2>Collection</h2>
    {{ $artwork->collection()->first()->terms() }}
  @else
    <p><em>no artwork added..</em></p>
  @endif
</div>
@stop

@section('page.scripts')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
  $(document).ready(function(){
    function artwork_add() {

    }

    function artwork_refresh() {

    }

    function drive_filelist_refresh() {

    }

    $('.drive-img').on('click', function(evt){
      var $img = $(this).clone();
      $('.placeholder').empty().append($img);
      $('[name=id]').prop('value', $img.attr('data-item-id'));
      $('[name=title]').prop('value', $img.prop('alt'));
      $('[name=mimeType]').prop('value', $img.attr('data-item-type'));
      $('[name=thumbnailLink]').prop('value', $img.prop('src'));
      $('#id').empty().append($img.attr('data-item-id'));
      $('#filename').empty().append($img.prop('alt'));
      $('#mimetype').empty().append($img.attr('data-item-type'));
    });

  });
</script>
@stop

@section('controls')
<button>upload artwork to drive</button>
<button>add folder</button>
<div class="drive">
  <ul class="drive-items">
  @foreach($drive_items as $i => $item)
  @if (!$item->getLabels()->getTrashed())
    @if (0==$i)
    <li class="drive-item" data-item-id="{{ $item->getId() }}" data-title="{{ $item->getTitle() }}" data-mime-type="{{ $item->mimeType }}">
    @else
    --><li class="drive-item" data-item-id="{{ $item->getId() }}" data-title="{{ $item->getTitle() }}" data-mime-type="{{ $item->mimeType }}">
    @endif
      {{-- drive folder --}}
      @if('application/vnd.google-apps.folder' == $item->mimeType)
      <img src="{{ $item->iconLink}}"> <span class="item-title">{{ $item->getTitle() }}</span>
      @else {{-- drive file --}}
      <img class="drive-img"
        src="{{ $item->thumbnailLink }}"
        alt="{{ $item->getTitle() }}"
        data-item-id="{{ $item->getId() }}"
        data-item-type="{{ $item->mimeType }}">
      @endif
    @if ( count($drive_items) !== ($i+1))
    </li><!--
    @else
    </li>
    @endif
  @endif
  @endforeach
  </ul>
</div>
@stop