<?php
use \Michelf\Markdown;

class ThoughtRepository {

  protected $datapath;

  public function __construct()
  {
    $this->datapath = base_path() . '/thoughts';
  }

  public function setDatapath($newpath)
  {
    $this->datapath = $newpath;
  }

  public function getPage($page)
  {
    $path = $this->datapath . '/' . $page . '.md';
    $pagehtml = '<h2>' . $path . ' not found!</h2>';
    if(file_exists($path)) {

      $pagehtml = Markdown::defaultTransform(file_get_contents($path));

    }

    $page = new Thought($page, $pagehtml);
    return $page;
  }
}