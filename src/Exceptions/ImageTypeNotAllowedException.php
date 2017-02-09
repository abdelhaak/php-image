<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Thu Feb  9 00:19:48 CET 2017
//


namespace OussamaElgoumri\Exceptions;


class ImageTypeNotAllowedException extends \Exception
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
