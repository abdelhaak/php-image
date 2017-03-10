<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Thu Mar  9 09:59:14 WET 2017
//

namespace OussamaElgoumri\Components;

use ImageOptimizer\OptimizerFactory;
use OussamaElgoumri\Components\Image\ImageItem;
use OussamaElgoumri\Components\Image\ImageIterator;
use OussamaElgoumri\Components\Image\ImageResizer;
use OussamaElgoumri\Components\Image\ImageValidator;

class Image
{
    /**
     * @var string  $img    Valid image location.
     */
    private $img;

    /**
     * @var integer     $type   see: exif_imagetype()
     */
    private $type;

    /**
     * @var string  $resolved_img   Local file path to the img.
     */
    private $resolved_img;

    /**
     * @var string  $path   full path to the image inside the public folder
     */
    private $path;

    /**
     * @var string  $relative_path  will be used in <img src="...">
     */
    private $relative_path;

    /**
     * @var string  $uuid   unique identified for the image.
     */
    private $uuid;

    /**
     * @var array   $resized_imgs_paths     list of resized_images.
     */
    private $resized_imgs;

    /**
     * @var ImageIterator
     */
    private $imageIterator;

    /**
     * @var $default_config     Default image configuration.
     */
    protected $default_config = [
        'IMAGE_ALLOWED_TYPES' => '',
        'IMAGE_DENIED_TYPES' => '',
        'IMAGE_DIRS'     => 'Y/i/d',
        'IMAGE_PUBLIC'   => 'public/images',
        'IMAGE_RELATIVE' => 'images',
        'IMAGE_UUID'     => '%hash%--%time%.%ext%',
        'IMAGE_OPTIMIZE' => true,
        'IMAGE_SIZES'    => '1024x768,800x600,460x308,320x240,240x160,160x160,75x75',
    ];

    /**
     * Initialize Constructor.
     *
     * @param string    $img
     */
    public function __construct($img = null, $config = [])
    {
        if ($img) {
            $this->img = $img;
            $this->defaults($config);
        }
    }

    /**
     * Apply default configuration to the image.
     */
    private function defaults($config)
    {
        Config__load($this->getConfigFilename(), $this->getDefaultConfig($config));

        $this
            ->resolve()
            ->validate()
            ->compress()
            ->uuid()
            ->copy()
            ->resize()
            ->compile();
    }

    /**
     * Get default configuration.
     *
     * @param  array     $config
     * @return array
     */
    private function getDefaultConfig($config)
    {
        $defaults = [];

        foreach ($this->default_config as $key => $value) {
            if (isset($config[$key]) && !empty($config[$key])) {
                $defaults[$key] = $config[$key];
            } elseif (isset($config[$key]) && empty($config[$key])) {
                continue;
            } else {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

    /**
     * Get all the information.
     *
     * @return array
     */
    public function get()
    {
        return $this->imageIterator;
    }

    /**
     * Get the configuration filename.
     *
     * @return string
     */
    public function getConfigFilename()
    {
        return 'image';
    }

    /**
     * Get image full path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Compile images.
     *
     * @return $this
     */
    public function compile()
    {
        $imageItem = new ImageItem;
        $imageItem->setPath($this->path);
        $imageItem->setRelativePath($this->relative_path);
        $imageItem->setType($this->type);
        $data[] = $imageItem;

        foreach ($this->resized_imgs as $img) {
            $imageItem = new ImageItem;
            $imageItem->setPath($img['path']);
            $imageItem->setRelativePath($img['relative_path']);
            $imageItem->setType($this->type);
            $data[] = $imageItem;
        }

        $this->imageIterator = new ImageIterator($data);
    }

    /**
     * Serialize the results.
     *
     * @return string
     */
    public function serialize()
    {
        $data = [];
        $data[] = [
            'path' => $this->path,
            'relative_path' => $this->relative_path,
            'type' => $this->type,
        ];

        foreach ($this->resized_imgs as $img) {
            $data[] = [
                'path' => $img['path'],
                'relative_path' => $img['relative_path'],
            ];
        }

        return serialize($data);
    }

    /**
     * Unserialize already serialized results.
     *
     * @param  string    $results
     * @return array
     */
    public function unserialize($results)
    {
        $this->resized_imgs = unserialize($results);
        $item = array_shift($this->resized_imgs);
        $this->path = $item['path'];
        $this->relative_path = $item['relative_path'];
        $this->type = $item['type'];
        $this->compile();

        return $this->get();
    }

    /**
     * Resize the image.
     *
     * @return $this
     */
    public function resize()
    {
        $imageResizer = new ImageResizer;
        $resized_imgs = $imageResizer->run($this->path);

        foreach ($resized_imgs as $img) {
            $this->resized_imgs[] = [
                'path' => $img,
                'relative_path' => $this->getRelativePath($img),
            ];
        }

        return $this;
    }

    /**
     * Copy the image to it's final location.
     *
     * @return $this
     */
    public function copy()
    {
        $dir = $this->createDirs();
        $path = $this->sanitize($dir, $this->uuid);
        rename($this->resolved_img, $path);
        $this->path = $path;
        $this->relative_path = $this->getRelativePath($path);
        return $this;
    }

    /**
     * Get relative image path.
     *
     * @param  string    $img
     * @return string
     */
    public function getRelativePath($img = null)
    {
        if (!$img) {
            return $this->relative_img;
        }

        return preg_replace(
            '#.*(' . Config__get('IMAGE_RELATIVE') . '.*)#'
            , '$1'
            , $img
        );
    }

    /**
     * Create directories to host image.
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

    /**
     * Generate unique identifier for the image.
     *
     * @param string    $img
     * @param string    $uuid
     *
     * @return this
     */
    public function uuid()
    {
        $img = $this->resolved_img;
        $uuid = Config__get('IMAGE_UUID');

        $uuid = str_replace('%hash%', sha1_file($img), $uuid);
        $uuid = str_replace('%time%', time(), $uuid);
        $this->uuid 
            = $uuid 
            = str_replace('.%ext%', image_type_to_extension(exif_imagetype($img)), $uuid);

        return $this;
    }

    /**
     * Compress the image.
     *
     * @param  string    $img
     * @return $this
     */
    public function compress()
    {
        $img = $this->resolved_img;
        $factory = new OptimizerFactory;
        $optimizer = $factory->get();
        $optimizer->optimize($img);

        return $this;
    }

    /**
     * Alias self::compress()
     *
     * @param string    $img
     * @return $this
     */
    public function optimize()
    {
        return $this->compress();
    }

    /**
     * Validate the given image.
     *
     * @param  string    $img
     * @return $this
     * @throws OussamaElgoumri\Exceptions\ImageException
     */
    public function validate()
    {
        $img = $this->resolved_img;
        $imageValidator = new ImageValidator();
        $imageValidator->validate($img);
        $this->type = $imageValidator->getType();

        return $this;
    }

    /**
     * Get the image from anywhere to local file.
     *
     * @return $this
     * @throws Exception
     */
    public function resolve()
    {
        $img = $this->img;

        if (file_exists($img) && is_writable($img)) {
            $this->resolved_img = $img;
            return $this;
        }

        if (filter_var($img, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            $this->resolved_img = $this->downloadToTmp($img);
            return $this;
        }

        if (isset($_FILES[$img]) && isset($_FILES[$img]['tmp_name'])) {
            $this->resolved_img = $_FILES[$img]['tmp_name'];
            return $this;
        }

        throw new \Exception('Image should be either: valid full path, remote url or form input file name');
    }

    /**
     * Download the image to /tmp.
     *
     * @param string    $img
     * @reutrn $tmpfile
     */
    private function downloadToTmp($url)
    {
        $tmpfile = tempnam('', '');
        $handle = fopen($tmpfile, 'w');
        fwrite($handle, Curl__get($url));
        fclose($handle);

        return $tmpfile;
    }

    /**
     * To ease testing.
     *
     * @param string    $prop
     */
    public function __get($prop)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        }

        throw new \Exception(self::class . " has no property with the name: {$prop}");
    }

    /**
     * To ease testing.
     *
     * @param string    $prop
     * @param string    mixed
     */
    public function __set($prop, $value)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop = $value;
        }

        throw new \Exception(self::class . " has no property with the name: {$prop}");
    }
}
