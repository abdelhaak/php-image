<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Fri Feb 10 10:45:22 WET 2017
//

namespace OussamaElgoumri\Components\Image;

use FileSystemIterator;
use OussamaElgoumri\Exceptions\ImagePublicPathNotSetException;

class ImagePath
{
    protected $path;
    protected $relative_path;

    /**
     * ImagePath Constructor.
     *
     * @param string    $img    Url|Path|Input file name
     */
    public function __construct($img)
    {
        $this->path = $this->getFrom($img);
    }

    /**
     * Get the image path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get image relative path.
     *
     * @return string
     */
    public function getRelativePath()
    {
        return $this->relative_path;
    }

    /**
     * Copy the image from the temp path to the new path.
     *
     * @param string    $uuid
     */
    public function copy($uuid)
    {
        $dir = $this->createDirs();
        $path = $this->sanitize($dir, $uuid);
        rename($this->path, $path);
        $this->path = $path;
        $this->relative_path = preg_replace(
            '#.*(' . getenv('IMAGE_RELATIVE') . '.*)#'
            , '$1'
            , $path
        );
    }

    /**
     * Get the image from anywhere and place it, if not already there in /tmp
     *
     * @param  string    $img
     * @return string
     */
    private function getFrom($img)
    {
        if (file_exists($img)) {
            return $img;
        }

        if (filter_var($img, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            return downloadToTmp($img);
        }

        if (isset($_FILES[$img]) && isset($_FILES[$img]['tmp_name'])) {
            return $_FILES[$img]['tmp_name'];
        }

        throw new \Exception('Image should be either: valid full path, remote url or form input file name');
    }

    /**
     * Create directories where the image will be placed.
     *
     * @return string
     */
    private function createDirs()
    {
        $dirs = Config__get('IMAGE_DIRS');
        $dirs = date($dirs);
        $public = Config__get('IMAGE_PUBLIC');

        if (!$public) {
            throw new ImagePublicPathNotSetException();
        }

        $public = $this->sanitize('/', $public);

        if (is_dir($public)) {
            $path = $this->sanitize($public, $dirs);
        } else {
            $path = base_path($public, $dirs);
        }

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    /**
     * Remove all extra slashs, and combine the given args.
     *
     * @param  ...
     * @return string
     */
    private function sanitize()
    {
        $args = func_get_args();
        $path = '';

        foreach (range(0, func_num_args() - 1) as $i) {
            if ($i == 0) {
                $path .= rtrim($args[$i], '/') . '/';
            } else {
                $path .= trim($args[$i], '/') . '/';
            }
        }

        return rtrim($path, '/');
    }
}
