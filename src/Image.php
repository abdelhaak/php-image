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
     * @var ImagePath
     */
    protected $imagePath;

    /**
     * Process the given image.
     *
     * @param string    $img    Path|Url|Input file name
     */
    public function init($img)
    {
        $this->imagePath = $imagePath = new ImagePath($img);
        ImageValidator__validate($imagePath->getPath());
        $uuid = $this->getUuid($imagePath->getPath());
        $imagePath->copy($uuid);

        return $imagePath;
    }

    /**
     * Get all the information about the image.
     *
     * @param string    $img    Path|Url|Input file name
     * @return array
     */
    public function get($img)
    {
        $imagePath = $this->init($img);

        return [
            'path' => $imagePath->getPath(),
            'relative_path' => $imagePath->getRelativePath(),
        ];
    }

    /**
     * Get full path of the image.
     *
     * @param  string    $img
     * @return string
     */
    public function getPath($img)
    {
        return $this->get($img)['path'];
    }

    /**
     * Get image relative path.
     *
     * @param  string    $img
     * @return string
     */
    public function getRelativePath($img)
    {
        return $this->get($img)['relative_path'];
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
