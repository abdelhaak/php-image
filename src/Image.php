<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 10:40:25 WET 2017
//


namespace OussamaElgoumri;


use OussamaElgoumri\Exceptions\ImageNotValidException;
use FileSystemIterator;


class Image
{
    /**
     * Process the given image.
     *
     * @param string    $img    Path|Url|Input file name
     */
    public function get($img)
    {
        $img = new ImagePath($img);
        ImageValidator__validate($img->getPath());
        $uuid = $this->getUuid($img->getPath());
        $img->copy($uuid);

        return [
            'path' => $img->getPath(),
            'relative_path' => $img->getRelativePath(),
        ];
    }

    /**
     * Generate unique identifier for the image.
     *
     * @param  string    $path to the image
     * @return string
     */
    private function getUuid($path)
    {
        $uuid = getenv('IMAGE_UUID') ?: '%hash%--%time%.%ext%';
        $uuid = str_replace('%hash%', sha1_file($path), $uuid);
        $uuid = str_replace('%time%', time(), $uuid);

        return str_replace('.%ext%', image_type_to_extension(exif_imagetype($path)), $uuid);
    }
}
