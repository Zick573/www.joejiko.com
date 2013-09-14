<?php
class Files
{
    public function thumbnails($id)
    {
        $path = APP_PATH."/public/uploads";

        $file = File::first(array(
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

                header("Location: /user/uploads/{$thumbnail}");
                exit();
            }

            header("Location: /user/uploads/{$name}");
            exit();
        }
    }

    public function view()
    {
        $this->actionView->set("files", File::all());
    }

    public function delete($id)
    {
        $file = File::first(array(
            "id = ?" => $id
        ));

        if ($file)
        {
            $file->deleted = true;
            $file->save();
        }

        self::redirect("/files/view.html");
    }

    public function undelete($id)
    {
        $file = File::first(array(
            "id = ?" => $id
        ));
        if ($file)
        {
            $file->deleted = false;
            $file->save();
        }
        self::redirect("/files/view.html");
    }
}
