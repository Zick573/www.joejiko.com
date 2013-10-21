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

  public function taxonomy() {
    return $this->belongsToMany('TermTaxonomy', 'term_relationships', 'object_id', 'term_taxonomy_id');
  }

  public function collection() {
    return $this->taxonomy()->where('taxonomy', '=', 'collection');
  }

  public function tag() {
    return $this->taxonomy()->where('taxonomy', '=', 'tag');
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