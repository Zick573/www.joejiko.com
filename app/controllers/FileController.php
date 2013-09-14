<?php
class FileController extends BaseController
{
  protected $upload_dir = "/home1/yourown1/UPLOAD/";

  /**
   * [uploadFromImport description]
   * @param  [type] $params [description]
   * @return [type]         [description]
   */
  protected function uploadFromImport($params)
  {
    // setup
    $queue = self::readUploadFiles($params['category']);

    if(!count($queue)):
      return false;
    endif;

    foreach($queue as $item):
      $data['name'] = $item;
      $data['size'] = filesize($item);
      $data['tmp_name'] = $item;
      $data['type'] = filetype($item);

      // pass
      self::upload($data);
    endforeach;
  }

  /**
   * [readUploadFiles description]
   * @param  string $category [description]
   * @return [type]           [description]
   */
  protected function readUploadFiles($category = ''){

    // "*.jpg" add condition
    return glob($this->upload_dir.$category."*");

  }
}