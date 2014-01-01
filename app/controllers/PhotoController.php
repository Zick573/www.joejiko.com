<?php
class PhotoController extends FileController {

  private $category = "images";

  /**
   * [upload description]
   * @param  array $images file properties for upload
   * @param  array $config
   * @return [type]         [description]
   */
  protected function upload($images, $config)
  {
    // $config
    // key: parent
    if(!$images):
      return false;
    endif;

    $upload = $images;

    /* @todo: handle multiple file upload */
    try
    {
        $source = ImageWorkshop::initFromPath($upload['tmp_name']);
    }
    catch (Exception $e)
    {
      return "Image isn't valid";
    }

    $layer = clone $source;

    // setup
    $ext = pathinfo($upload['name'], PATHINFO_EXTENSION);
    $filename = sha1_file($upload['tmp_name']);
    $filename_ext = sprintf("%s.%s", $filename, $ext);
    // $filenamext = $filename.".{$ext}";
    $metadata = exif_read_data($upload['tmp_name']);

    // make sure file isn't already uploaded
    $image = Image::first(array(
        'filename = ?' => $filename_ext
    ));
    if($image)
    {
        if($import === null)
        {
            // this image already exists in the database
            $original = "This file already exists. ".$image['filename'];
            $result = false;
        }
        else
        {
            // delete image from folder
            unlink($import);
            return true; // next image in queue
        }
    }
    else
    {
        // continue
        // folder tree
        $part1 = substr($filename, 0, 2);
        $part2 = substr($filename, 2, 2);
        // $dirPath = "/home1/yourown1/CDN/images/{$part1}/{$part2}";
        $this_img_path = sprintf("%s/%s/%s/%s", $this->upload_dir, $this->category, $part1, $part2);

        // @todo generate filename hash
        $c = array(
            'createFolders' => true,
            'background' => null,
            'quality' => 95
        );
        $createFolders = true;
        $backgroundColor = null; // transparent, only for PNG (otherwise it will be white if set null)
        $imageQuality = 95; // useless for GIF, usefull for PNG and JPEG (0 to 100%)

        // resize image to max 2048 width
        /*
        $thumbWidth = 125; // px
        $thumbHeight = null;
        $conserveProportion = true;
        $positionX = 0; // px
        $positionY = 0; // px
        $position = 'MM';

        $layer->resizeInPixel($thumbWidth, $thumbHeight, $conserveProportion, $positionX, $positionY, $position);
        */
        $width = $layer->getWidth();
        $height = $layer->getHeight();

        // save original
        $original = $layer->save(
          $this_img_path,
          $filename_ext,
          $c['createFolders'],
          $c['background'],
          $c['quality']
        );

        // resize small, medium, large, thumbnail
        // @todo create image size settings in database
        // reference that instead of hard coding
        //
        // @todo move this to ajax (possibly schedule)
        $large = clone $source;
        $medium = clone $source;
        $small = clone $source;
        $smaller = clone $source;
        $thumb = clone $source;
        $square = clone $source;

        $large->resizeInPixel(720, null, true);
        $large->save($dirPath, $filename.'_l.'.$ext, $c['createFolders'], $c['background'], $c['quality']);

        $medium->resizeInPixel(600, null, true);
        $medium->save($dirPath, $filename.'_m.'.$ext, $c['createFolders'], $c['background'], $c['quality']);

        $small->resizeInPixel(480, null, true);
        $small->save($dirPath, $filename.'_s.'.$ext, $c['createFolders'], $c['background'], $c['quality']);

        $smaller->resizeInPixel(320, null, true);
        $smaller->save($dirPath, $filename.'_ss.'.$ext, $c['createFolders'], $c['background'], $c['quality']);

        $thumb->resizeInPixel(180, null, true);
        $thumb->save($dirPath, $filename.'_t.'.$ext, $c['createFolders'], $c['background'], $c['quality']);

        $square->cropMaximumInPixel(0, 0, "MM");
        $square->resizeInPixel(75, 75);
        $square->save($dirPath, $filename.'_sq.'.$ext, $c['createFolders'], $c['background'], $c['quality']);

        // save images
        // store reference to database

        // if(RequestMethods::post('parent'))
        // {
        //     $parent = RequestMethods::post('parent');
        // }
        // else
        // {
        //     // default no parent
        //     $parent = 0;
        // }

        // default no parent
        $parent = $config['parent'] ? $config['parent'] : 0;

        $image = new Image(array(
          'filename' => $filename_ext,
          'size' => $upload['size'],
          'width' => $width,
          'height' => $height,
          'metadata' => json_encode($metadata),
          'mime_type' => $upload['type'],
          'caption' => $config['caption'],
          'uploaded_by' => $config['user'],
          'parent' => $parent
        ));

        $image->save();

        // output result
        $result = file_exists($dirpath.$filename_ext);
    }

    // if($result !== false && $config[import === null)
    if($result && is_null($config['import'])):
      // @todo keep or delete
      self::result(array(
        'name' => $filename,
        'ext' => $ext
      ));
    endif;

    if(!is_null($import) && file_exists($import)):
      // delete file from queue
      unlink($import);

      // link to existing file
    endif;
  }

  public function getIndex()
  {
    return View::make('photos');
  }

  public function getUpload()
  {
    return View::make('photos.upload');
  }

  public function postUpload()
  {
    $config = array();
    $images = $_FILES;
    self::upload($images, $config);
  }

  public function getList()
  {
    $photos = Photo::all();
    var_dump($photos);
  }

  public function getThumbnail($id)
  {
    $thumbnail = Photo::find($id);
    var_dump($thumbnail);
  }

  public function missingMethod($parameters=[])
  {
    // missing
    return Redirect::to('photos');
  }
}