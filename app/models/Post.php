<?php
class Post extends Eloquent {
  // material
  protected $table = 'posts';

  /**
   * The attributes that are mass assignable
   *
   * @var array
   */
  protected $fillable = [
    'content',
    'title',
    'category',
    'excerpt',
    'status',
    'comment_status',
    'password',
    'name',
    'parent',
    'guid',
    'menu_order',
    'type',
    'mime_type',
    'comment_count'
  ];

  public function author() {
    // return $this->hasOne('User', 'user_id');
    return $this->belongsTo('User');
  }

  public function category() {
    // return $this->hasOne('Category', 'category');
    return $this->belongsTo('Category');
  }

  public function taxonomy() {
    return $this->belongsToMany('TermTaxonomy', 'term_relationships', 'object_id', 'term_taxonomy_id');
  }

  public function term() {
    return $this->taxonomy()->hasOne('Term');
  }

  public function collection() {
    if($collection = $this->taxonomy()->where('taxonomy', '=', 'collection')->first()):
      return $collection->name();
    endif;

    return NULL;
  }

  public function tags() {
    return $this->taxonomy()->where('taxonomy', '=', 'tag')->get();
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

  public function scopeMicro($query)
  {
    return $query->where('type', '=', 'artwork')->where('type', '=', 'thought')->orderBy('created_at', 'desc');
  }

  public function scopeArtwork($query)
  {
    return $query->where('type', '=', 'artwork');
  }

  public function scopeThoughts($query)
  {
    return $query->where('type', '=', 'thought');
  }

  public function scopeOfType($query, $type)
  {
    return $query->where('type', '=', $type);
  }
}