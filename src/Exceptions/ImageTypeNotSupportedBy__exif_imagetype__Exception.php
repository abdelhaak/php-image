<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Thu Feb  9 00:37:02 CET 2017
//


namespace OussamaElgoumri\Exceptions;


class ImageTypeNotSupportedBy__exif_imagetype__Exception extends \Exception
{
    /**
     * Initialize Constructor.
     *
     * @param string    $msg    Optional|message to write
     */
    public function __construct($msg = null)
    {
        parent::__construct($msg);
    }
}
