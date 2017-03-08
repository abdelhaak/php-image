<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 10:40:25 WET 2017
//

namespace OussamaElgoumri\Components;

use OussamaElgoumri\Components\Image\ImagePath;
use OussamaElgoumri\Exceptions\ImageNotValidException;
use FileSystemIterator;

class Image
{
    /**
     * @var ImagePath
     */
    protected $imagePath;

    /**
     * Initialize Constructor.
     */
    public function __construct()
    {
        Config__load('images', [
            'IMAGE_ALLOWED_TYPES',
            'IMAGE_DENIED_TYPES',
            'IMAGE_DIRS'     => 'Y/i/d',
            'IMAGE_PUBLIC'   => 'public/images',
            'IMAGE_RELATIVE' => 'images',
            'IMAGE_UUID'     => '%hash%--%time%.%ext%',
            'IMAGE_OPTIMIZE' => true,
            'IMAGE_SIZES'    => '1024x768,800x600,460x308,320x240,240x160,160x160,75x75',
        ]);
    }

    /**
     * Process the given image.
     *
     * @param string    $img    Path|Url|Input file name
     */
    public function run($img)
    {
        // Move image to /tmp
        $this->imagePath = $imagePath = new ImagePath($img);

        // Make sure we have a valid image:
        ImageValidator__validate($imagePath->getPath());

        // Optimize the image:
        ImageOptimizer__run($imagePath->getPath());

        // Generate unique id for the image:
        $uuid = $this->getUuid($imagePath->getPath());

        // Copy the image to the proper location:
        $imagePath->copy($uuid);

        // Resize the image:
        ImageResizer__run($imagePath->getPath());

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
