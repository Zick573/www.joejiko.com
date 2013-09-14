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
    protected function _uploadFromImport($category)
    {
        $queue = self::readUploadFiles($category);
        if(count($queue) > 0)
        {
            foreach($queue as $image)
            {
                self::_upload($image);
            }
        }
    }

    protected function _upload($import=null)
    {
        if($import !== null)
        {
            $upload['name'] = $import;
            $upload['size'] = filesize($import);
            $upload['tmp_name'] = $import;
            $upload['type'] = filetype($import);
        }
        else
        {
            if(RequestMethods::files('image'))
            {
                $upload = RequestMethods::files('image');
            }
            else
            {
                exit();
            }
        }

        /* @todo: handle multiple file upload */
        try
        {
            $source = ImageWorkshop::initFromPath($upload['tmp_name']);
        }
        catch (Exception $e)
        {
            echo "Image isn't valid";
        }

        $layer = clone $source;

        // setup
        $ext = pathinfo($upload['name'], PATHINFO_EXTENSION);
        $filename = sha1_file($upload['tmp_name']);
        $filenamext = $filename.".{$ext}";
        $metadata = exif_read_data($upload['tmp_name']);

        // make sure file isn't already uploaded
        $image = Image::first(array(
            'filename = ?' => $filenamext
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

            // save original
            $original = $layer->save($dirPath, $filenamext, $c['createFolders'], $c['background'], $c['quality']);

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
            if(RequestMethods::post('parent'))
            {
                $parent = RequestMethods::post('parent');
            }
            else
            {
                // default no parent
                $parent = 0;
            }

            $image = new Image(array(
                'filename' => "{$filenamext}",
                'size' => $upload['size'],
                'width' => $width,
                'height' => $height,
                'metadata' => json_encode($metadata),
                'mime_type' => $upload['type'],
                'caption' => RequestMethods::post('caption'),
                'uploaded_by' => RequestMethods::post('user'),
                'parent' => $parent
            ));

            $image->save();

            // output result
            $result = file_exists($dirpath.$filenamext);
        }

        if($result !== false && $import === null)
        {
            // @todo keep or delete
            $this->smarty->assign(array(
                'result' => array(
                    'name' => $filename,
                    'ext' => $ext
                )
            ));
        }
        else
        {
            if($import !== null && file_exists($import))
            {
                // delete file from queue
                unlink($import);
            }
            // link to existing file
        }
    }
    public function view($id)
    {

        $i = Image::first(array(
            'id = ?' => $id
        ));

        list($imgname, $imgext) = explode('.', $i->filename);
        $part1 = substr($i->filename, 0, 2);
        $part2 = substr($i->filename, 2, 2);
        $dirPath = "/home1/yourown1/CDN/images/{$part1}/{$part2}";
        $sizing = getimagesize($dirPath.'/'.$imgname.'_m.'.$imgext);
        $image = array(
            'id' => $i->id,
            'filename' => $imgname,
            'ext' => $imgext,
            'url' => '//cdn.joejiko.com/images/'.$i->filename,
            'caption' => $i->caption,
            'parent' => $i->parent,
            'children' => $children,
            'width' => $sizing[0],
            'height' => $sizing[1]
        );

        $this->smarty->assign(array(
            'view' => true,
            'image' => $image,
            'action' => 'photos/index',
            'meta' => array(
                'title' => $image->caption
            )
        ));
    }

    /**
    * @before _secure
    */
    public function index()
    {
        // gallery index
        // for now, show all..
        $gallery = Image::all(array(
            'live = ?' => 1,
            'deleted = ?' => 0,
            'parent = ?' => 0
        ), array("*"), "id", "desc", 25, 1);
        // @todo upgrade ORM to one that supports relationships

        // @todo order sets
        // add a set reference to database

        $images = array();
        foreach($gallery as $index => $image)
        {
            /* @todo author name/photo from database
            $user = User::first(array(
                'id = ?' => $image->uploaded_by
            ));

            if($user)
            {
                $uploader = array(

                );
            }
            */

            // check for children
            $children = array();
            $row = Image::all(array(
                'parent = ?' => $image->id
            ), array("*"), "id", "desc", 6, 1);

            if($row)
            {
                foreach($row as $cIndex => $child)
                {
                    list($childname, $childext) = explode('.', $child->filename);
                    $children[$cIndex] = array(
                        'id' => $child->id,
                        'filename' => $childname,
                        'ext' => $childext
                    );
                }
            }
            list($imgname, $imgext) = explode('.', $image->filename);
            $images[$index] = array(
                'id' => $image->id,
                'filename' => $imgname,
                'ext' => $imgext,
                'url' => '//cdn.joejiko.com/images/'.$image->filename,
                'caption' => $image->caption,
                'parent' => $image->parent,
                'children' => $children
            );
        }
        $this->assets->set(array(
            'scripts' => array(),
            'styles' => array()
        ));
        $this->smarty->assign(array(
            'meta' => array(
                'title' => 'Photo gallery'
            ),
            'images' => $images
        ));
    }

    /**
     * @before _secure
     */
    public function upload()
    {
        // @todo authorize admin/user only
        if(RequestMethods::files('image'))
        {
            self::_upload();
        }
        elseif(RequestMethods::post('import'))
        {
            // @todo select folder to import from
            self::_uploadFromImport('instagram/');
        }
        $this->smarty->assign(array(
            'queue' => self::readUploadFiles('instagram/')
        ));
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

    protected function readUploadFiles($category = ''){

        return glob("/home1/yourown1/UPLOAD/".$category."*"); // "*.jpg" add condition

    }

    /**
    * @before _secure, _admin
    */
    public function manage()
    {
        echo "manage photos";
    }
}
