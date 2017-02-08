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
    public function __construct()
    {
        $this->setBasePath(__DIR__);
    }

    public function get($img)
    {
        $path = $this->getPath($img);
        $this->validate($path);
        $uuid = $this->getUUID($path);
        $ext = $this->getExtension($path);
        $dir = $this->createDirs();
        $newPath = $this->buildPath($dir, $uuid . $ext);
        rename($path, $newPath);
        return preg_replace('#.*(' . getenv('IMAGE_RELATIVE') . '.*)#', '$1', $newPath);
    }

    /**
     * Create the directory to host the image.
     *
     * @param  string    $dirs
     * @return string
     */
    private function createDirs($dirs = '')
    {
        if (!$dirs) {
            $dirs = getenv('IMAGE_DIRS') ?: "Y/m/d";
        }

        $dirs = date($dirs);
        $public = getenv('IMAGE_PUBLIC');

        if (is_dir($public)) {
            $path = $this->buildPath($public, $dirs);
        } else {
            $path = base_path($this->buildPath($public, $dirs));
        }

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    /**
     * Build the given path (unlimited number of params).
     *
     * @return string
     */
    private function buildPath()
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

    /**
     * Set the base path of the host application.
     *
     * @param  string    $dir
     * @return string
     */
    private function setBasePath($dir)
    {
        global $base_path;

        if (empty($base_path)) {
            $i = new FileSystemIterator($dir, FileSystemIterator::SKIP_DOTS);

            foreach ($i as $file) {
                if ($file->isDir() && $file->getFilename() === "vendor") {
                    return $base_path = $dir;
                }
            }

            return $this->setBasePath(dirname($dir));
        }
    }

    /**
     * Get the image extension.
     *
     * @param  string    $path
     * @return string
     */
    private function getExtension($path)
    {
        $type = exif_imagetype($path);

        return image_type_to_extension($type);
    }

    /**
     * Generate unique identifier for the image.
     *
     * @param  string    $path to the image
     * @return string
     */
    private function getUUID($path)
    {
        $hash = sha1_file($path);

        return $hash . '--' . time();
    }

    /**
     * Make sure we have an image.
     *
     * @param  string    $img
     * @return bool
     */
    private function validate($img)
    {
        $results = exif_imagetype($img);

        if ($results === FALSE) {
            throw new ImageNotValidException();
        }

        return true;
    }

    /**
     * Get path to the image.
     *
     * @param  string    $img
     * @return string
     * @throws Exception
     */
    private function getPath($img)
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
}
