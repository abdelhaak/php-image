<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 10:37:18 WET 2017
//


use OussamaElgoumri\Image;
use OussamaElgoumri\RandomUserAgent;
use OussamaElgoumri\Curl;


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

if (!function_exists('Curl__post')) {
    /**
     * Issue POST request.
     *
     * @param string    $link
     * @param array     $fields
     * @param array     $options
     *
     * @return mixed
     */
    function Curl__post($link, $fields = [], $options = [])
    {
        return (new Curl)->post($link, $fields, $options);
    }
}

if (!function_exists('rua')) {
    /**
     * Get random user agent.
     *
     * @param  array     $list of languages to choose from.
     * @return string
     */
    function rua($lang = ['en-US'])
    {
        return RandomUserAgent::getInstance()
            ->get($lang);
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

if (!function_exists('base_path')) {
    /**
     * Get base path.
     *
     * @param  string    $thing
     * @return string
     */
    function base_path($thing = '')
    {
        global $base_path;

        if ($thing) {
            $thing = '/' . trim($thing, '/');
        }

        return $base_path . $thing;
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
