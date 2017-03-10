<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Tue Mar  7 17:52:51 WET 2017
//

namespace OussamaElgoumri\Components\Image;

use SplFileInfo;
use Intervention\Image\ImageManagerStatic as Image;

class ImageResizer
{
    public function __construct()
    {
        Image::configure();
    }

    /**
     * Run the image resizer.
     *
     * @param string    $img
     */
    public function run($img)
    {
        $sizes = $this->getSizes();
        $paths = [];

        foreach ($sizes as $size) {
            $paths[] = $this->resize($img, $size);
        }

        return $paths;
    }

    /**
     * Resize the image.
     *
     * @param string    $img
     * @param array     $size
     */
    private function resize($img, $size)
    {
        $image = Image::make($img);
        $image->resize($size['width'], $size['height']);
        $path = $this->getPath($img, $size);
        $image->save($path);
        return $path;
    }

    /**
     * Get the new path to the image.
     *
     * @param string    $img
     * @param array     $size
     *
     * @return string
     */
    private function getPath($img, $size)
    {
        $file = new SplFileInfo($img);
        $img = str_replace(
            '.' . $file->getExtension(),
            '--' . $size['width'] . 'x' . $size['height'] . '.' . $file->getExtension(),
            $img
        );

        return $img;
    }

    /**
     * Extract sizes from configuartion.
     *
     * @return array
     */
    private function getSizes()
    {
        $_sizes = [];
        $sizes = explode(',', Config__get('IMAGE_SIZES'));

        foreach ($sizes as $size) {
            if (strpos($size, 'x') > 0) {
                preg_match('/(\d+)x(\d+)/', $size, $m);

                if (isset($m[1])) {
                    $width= $m[1];
                }

                if (isset($m[2])) {
                    $height = $m[2];
                }
            } else {
                preg_match('/(\d+)/', $size, $m);

                if (isset($m[1])) {
                    $width = $height = $m[1];
                }
            }

            if (!isset($width) || !isset($height)) {
                continue;
            }

            $_sizes[] = [
                'width' => $width,
                'height' => $height,
            ];

        } 

        return $_sizes;
    }
}
