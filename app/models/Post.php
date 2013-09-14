<?php
class Post extends Eloquent {
  // material
  protected $table = 'posts';

  public function author_info() {
    return $this->hasOne('User', 'post_author');
  }

  public function getContentAttribute($value) {
    $value = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">[source]</a>', $value);
    $value = nl2br($value);
    return $value;
  }

  public function newest($query)
  {
    return $query->where('*')->orderBy('created_at', 'desc');
  }
}