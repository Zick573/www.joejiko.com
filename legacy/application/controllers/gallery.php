<?php

use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;
use PHPImageWorkshop\ImageWorkshop;
/*
    @note
    deliver files with X-SendFile or readfile()
    if not x-sendfile, use htaccess
    rewriterule /images/$hash/$prettyname.$ext to /image/a/b/c/$hash.$ext

    @note
    header('Content-Type: $MIME_TYPE')
    readfile()
    get an extension: pathinfo($path, PATHINFO_EXTENSION)

*/
class Photos extends Controller
{
    protected function _upload()
    {
        $upload = RequestMethods::files('image');

        /* @todo: handle multiple file upload */
        $layer = ImageWorkshop::initFromPath($upload['tmp_name']);

        // setup
        $ext = pathinfo($upload['name'], PATHINFO_EXTENSION);
        $filename = sha1_file($upload['tmp_name']).".{$ext}";

        // make sure file isn't already uploaded
        $image = Image::first(array(
            'filename = ?' => $filename
        ));
        if($image)
        {
            // this image already exists in the database
            $original = "This file already exists. ".$image['filename'];
        }
        else
        {
            // continue
            // folder tree
            $part1 = substr($filename, 0, 2);
            $part2 = substr($filename, 2, 2);
            $dirPath = "/home1/yourown1/CDN/images/{$part1}/{$part2}";

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
            $original = $layer->save($dirPath, $filename, $c['createFolders'], $c['background'], $c['quality']);

            // resize small, medium, large, thumbnail
            // save images
            // store reference to database
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

        }

        $this->smarty->assign(array(
            'result' => array(
                'original' => $original, // true|false
                'small' => $small,
                'medium' => $medium,
                'large' => $large,
                'thumbnail' => $thumbnail
            )
        ));
    }

    public function index()
    {
        // gallery index
        $gallery = Image::all(array(
            'live = ?' => 1,
            'deleted = ?' => 0
        ), array("*"), "id", "desc", 10, 1);

        $images = array();
        foreach($gallery as $index => $image)
        {
            $images[$index] = array(
                'filename' => $image->filename,
                'url' => '//cdn.joejiko.com/images/'.$image->filename,
                'caption' => $image->caption
            );
        }

        $this->smarty->assign(array(
            'images' => $images
        ));
    }

    public function uploadForm()
    {
//              $this->smarty->debugging = true;
//        $user = $this->getUser();
        $this->_assets->set(array(
            'scripts' => array(

            ),
            'styles' => array(
            )
        ));

        $this->smarty->assign(array(
            'upload' => true,
            'action' => 'gallery/uploadForm',
            'meta' => array(
                'title' => "Upload image to gallery"
            )
        ));
    }

    public function upload()
    {
        // @todo authorize admin/user only
        if(RequestMethods::files('image'))
        {
            self::_upload();

            echo "post and files";
        }
        else
        {
            echo "no post";
        }
    }

    public function thumbnails($id)
    {


        $image = Image::first(array(
            "id = ?" => $id
        ));

        if ($file)
        {
            $width = 64;
            $height = 64;

            $name = $file->name;
            $filename = pathinfo($name, PATHINFO_FILENAME);
            $extension = pathinfo($name, PATHINFO_EXTENSION);

            if ($filename && $extension)
            {
                $thumbnail = "{$filename}-{$width}x{$height}.{$extension}";

                if (!file_exists("{$path}/{$thumbnail}"))
                {
                    $imagine = new Imagine\Gd\Imagine();

                    $size = new Imagine\Image\Box($width, $height);
                    $mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

                    $imagine
                        ->open("{$path}/{$name}")
                        ->thumbnail($size, $mode)
                        ->save("{$path}/{$thumbnail}");
                }

                header("Location: /uploads/{$thumbnail}");
                exit();
            }

            header("Location: /uploads/{$name}");
            exit();
        }
    }
}
