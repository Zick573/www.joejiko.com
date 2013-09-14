<?php
class Thought extends Eloquent {
  // material
  protected $table = 'thoughts';
  protected $hidden = array();
  protected $fillable = array('user_id', 'title', 'content');

  public function content() {
    return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $this->content)
  }
}