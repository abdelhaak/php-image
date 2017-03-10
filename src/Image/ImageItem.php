<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Fri Mar 10 11:14:17 WET 2017
//

namespace OussamaElgoumri\Components\Image;

class ImageItem
{
    private $path;
    private $relative_path;
    private $width;
    private $height;
    private $size;
    private $mime;
    private $type;

    /**
     * You can also get properties by name.
     *
     * @param  string    $prop
     * @return mixed
     */
    public function __get($prop)
    {
        if (property_exists($this, $prop)) {
            $m = 'get' . ucfirst($prop);
            return $this->$m();
        }

        return null;
    }

    /**
     * Set type of the image, see: exif_imagetype().
     *
     * @param string    $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get the type.
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the mime type of the image.
     *
     * @return string
     */
    public function getMime()
    {
        if (!$this->mime) {
            $this->mime = image_type_to_mime_type($this->type);
        }

        return $this->mime;
    }

    /**
     * Get the image width.
     *
     * @return integer
     */
    public function getWidth()
    {
        if (!$this->width) {
            $this->setWidthHeight();
        }

        return $this->width;
    }

    /**
     * Get the image height.
     *
     * @return integer
     */
    public function getHeight()
    {
        if (!$this->height) {
            $this->setWidthHeight();
        }

        return $this->height;
    }

    /**
     * Get image size.
     *
     * @return integer
     */
    public function getSize()
    {
        if ($this->size) {
            return $this->size;
        }

        return $this->size = filesize($this->path);
    }

    /**
     * Set image width and height.
     */
    public function setWidthHeight()
    {
        $results = getimagesize($this->path);
        $this->width = $results[0];
        $this->height = $results[1];
    }

    /**
     * Set the full path to the image.
     *
     * @param string    $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get full path to the image.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set image relative path.
     *
     * @param string    $relative_path
     */
    public function setRelativePath($relative_path)
    {
        $this->relative_path = $relative_path;
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
     * Convert image to base64 encoding.
     *
     * @return string
     */
    public function toBase64()
    {
        $ext = pathinfo($this->path, PATHINFO_EXTENSION);
        $content = file_get_contents($this->path);

        return 'data:image/' . $ext . ';base64,' . base64_encode($content);
    }
}
