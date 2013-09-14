<?php

use Shared\Controller as Controller;

class Gallery extends Controller
{
    protected _upload()
    {
        $path = '/home1/yourown1/CDN/images';
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
