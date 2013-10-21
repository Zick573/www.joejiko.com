<?php
class Post extends Eloquent {
  // material
  protected $table = 'posts';

  public function author() {
    return $this->hasOne('User', 'user_id');
  }

  public function category() {
    return $this->hasOne('Category', 'category');
  }

  public function terms() {
    return $this->hasMany('Terms');
  }

  public function getContentAttribute($value) {
    $value = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">[source]</a>', $value);
    $value = nl2br($value);
    return $value;
  }

  public function scopeRecent($query)
  {
    return $query->orderBy('created_at', 'desc');
  }

  public function scopeThoughts($query)
  {
    return $query->where('type', '=', 'thought');
  }

  public function scopeArtwork($query)
  {
    return $query->where('type', '=', 'artwork');
  }
}