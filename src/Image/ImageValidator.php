<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 22:56:52 WET 2017
//

namespace OussamaElgoumri\Components\Image;

use OussamaElgoumri\Exceptions\ImagePathNotValidException;
use OussamaElgoumri\Exceptions\ImageTypeNotValidException;
use OussamaElgoumri\Exceptions\ImageTypeNotAllowedException;
use OussamaElgoumri\Exceptions\ImageTypeIsDeniedException;
use OussamaElgoumri\Exceptions\ImageTypeNotSupportedBy__exif_imagetype__Exception;
use OussamaElgoumri\Exceptions\ImageTypeSeparatorNotSupportedException;

class ImageValidator
{
    private $allowed_types;
    private $denied_types;
    private $type;

    /**
     * Initialize Constructor.
     */
    public function __construct()
    {
        $this->allowed_types = $this->getTypes('IMAGE_ALLOWED_TYPES') ?: $this->getAllTypes();
        $this->denied_types = $this->getTypes('IMAGE_DENIED_TYPES') ?: [];
    }

    /**
     * Validate the given image.
     *
     * @param  string    $path
     * @return string
     * @throws ImageTypeNotValidException
     */
    public function validate($path)
    {
        if (!file_exists($path)) {
            throw new ImagePathNotValidException($path);
        }

        $this->type = $type = exif_imagetype($path);

        if ($type === FALSE) {
            throw new ImageTypeNotValidException();
        }

        if (!in_array($type, $this->allowed_types)) {
            throw new ImageTypeNotAllowedException($type);
        }

        if (in_array($type, $this->denied_types)) {
            throw new ImageTypeIsDeniedException($type);
        }

        return true;
    }

    /**
     * Get the image type.
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Build the list of types.
     *
     * @param  string    $item
     * @return array
     * @throws ImageTypeNotSupportedBy__exif_imagetype__Exception
     */
    private function getTypes($item)
    {
        $types = Config__get($item);

        if (!$types) {
            return false;
        }

        if (strpos($types, ',') !== FALSE) {
            $sep = ',';
        } elseif (strpos($types, ';') !== FALSE) {
            $sep = ';';
        } elseif (strpos($types, ' ') !== FALSE) {
            $sep = ' ';
        } else {
            $sep = ',';
        }

        $types = array_filter(explode(
            $sep, str_replace($sep === ' ' ? '' : ' ', '', $types))
        );
        $collect = [];

        foreach ($types as $type) {
            if (preg_match('/^imagetype_/i', $type)) {
                $type = strtoupper($type);
            } else {
                $type = strtoupper("IMAGETYPE_{$type}");
            }

            if (defined($type)) {
                array_push($collect, constant($type)); 
            } else {
                throw new ImageTypeNotSupportedBy__exif_imagetype__Exception($type);
            }
        }

        return $collect;
    }

    /**
     * Get list of all supported image types.
     *
     * @return array
     */
    private function getAllTypes()
    {
        return range(1, 17);
    }
}
