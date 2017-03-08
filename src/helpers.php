<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 10:37:18 WET 2017
//

use OussamaElgoumri\Components\Image;
use OussamaElgoumri\Components\Image\ImageValidator;
use OussamaElgoumri\Components\Image\ImageOptimizer;
use OussamaElgoumri\Components\Image\ImageResizer;

if (!function_exists('ImageResizer__run')) {
    /**
     * Resize the given image.
     *
     * @param string    $img
     */
    function ImageResizer__run($img)
    {
        (new ImageResizer)->run($img);
    }
}

if (!function_exists('ImageOptimizer__run')) {
    /**
     * Optimize the given image in place.
     *
     * @param string    $img
     */
    function ImageOptimizer__run($img)
    {
        $imageOptimizer = new ImageOptimizer();
        $imageOptimizer->run($img);
    }
}

if (!function_exists('ImageValidator__validate')) {
    /**
     * Validate the given image.
     *
     * @param  string    $img
     * @return bool
     */
    function ImageValidator__validate($img)
    {
        return (new ImageValidator)->validate($img);
    }
}

if (!function_exists('Image__get')) {
    /**
     * Get information about the given image.
     *
     * @param  string    $image
     * @return array
     */
    function Image__get($img)
    {
        return (new Image)->get($img);
    }
}

if (!function_exists('Image__getPath')) {
    /**
     * Get the image path.
     *
     * @param  string    $img
     * @return string
     */
    function Image__getPath($img)
    {
        return (new Image)->getPath($img);
    }
}

if (!function_exists('Image__getRelativePath')) {
    /**
     * Get the image relative path.
     *
     * @param  string    $img
     * @return string
     */
    function Image__getRelativePath($img)
    {
        return (new Image)->getRelativePath($img);
    }
}

if (!function_exists('Curl__get')) {
    /**
     * Issue GET request.
     *
     * @param string    $link
     * @param array     $options
     *
     * @return mixed
     */
    function Curl__get($link, $options = [])
    {
        return (new Curl)->get($link, $options);
    }
}

if (!function_exists('downloadToTmp')) {
    /**
     * Download a file and put it in /tmp
     *
     * @param  string    $url
     * @return string
     */
    function downloadToTmp($url)
    {
        $tmpfile = tempnam('', '');
        $handle = fopen($tmpfile, 'w');
        fwrite($handle, Curl__get($url));
        fclose($handle);

        return $tmpfile;
    }
}

if (!function_exists('about')) {
    /**
     * WTF! is all this about?
     *
     * @print about&help
     */
    function about()
    {
        echo "PHP Image Library created by Oussama Elgoumri <contact@sec4ar.com> (c) 2017\n\n";
        echo "Usage:\n";
        echo "  make:exception NameException\n";
        echo "\n\n";
        exit(0);
    }
}
