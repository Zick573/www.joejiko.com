<?php
namespace Jcom;
class Files
{
  protected $cdndir = "/home1/yourown1/CDN/";

  public function upload($type)
  {
    // @todo or method_exists
    // if(is_callable("upload{$type}", $this)) self::upload{$type}();
  }

  public function uploadImage()
  {
    $cdndir = $this->cdndir;
    $upload = $_files['image'];

    /**
     * @todo drag and drop file upload
     * @todo handle multiple files
     */
    $layer = ImageWorkshop::initFromPath($upload['tmp_name']);

    // setup
    $ext = pathinfo($upload['name'], PATHINFO_EXTENSION);
    $filename = sha1_file($upload['tmp_name']).".{$ext}";

    /**
     * @todo change model
     * make sure file isn't already uploaded
     */
    $image = Image::first(array(
        'filename = ?' => $filename
    ));

    if($image){
        // this image already exists in the database
        throw new Exception("This file already exists. ".$image['filename']);
    }

    // folder tree
    $part1 = substr($filename, 0, 2);
    $part2 = substr($filename, 2, 2);
    $dirPath = sprintf("%s/images/%s/%s", $cdn, $part1, $part2);

    // @todo generate filename hash
    // what? ^
    $c = array(
        'createFolders' => true,
        'background' => null,
        'quality' => 95
    );

    $createFolders = true;

    // transparent, only for PNG (otherwise it will be white if set null)
    $backgroundColor = null;

    // useless for GIF, useful for PNG and JPEG (0 to 100%)
    $imageQuality = 95;

    // resize image to max 2048 width
    $width = $layer->getWidth();
    $height = $layer->getHeight();
    $original = $layer->save(
      $dirPath,
      $filename,
      $c['createFolders'],
      $c['background'],
      $c['quality']
    );

    /**
     * resize small, medium, large, thumbnail
     * save and store reference
     */
    $image = new Image(array(
        'filename' => "{$filename}",
        'size' => '',
        'width' => $width,
        'height' => $height,
        'metadata' => '',
        'mime_type' => '',
        'caption' => RequestMethods::post('caption'),
        'uploaded_by' => RequestMethods::post('user')
    ));
    $image->save();

    // output result
    $result = array(
      'original' => $original, // true|false
      'small' => $small,
      'medium' => $medium,
      'large' => $large,
      'thumbnail' => $thumbnail
    );
  }
}