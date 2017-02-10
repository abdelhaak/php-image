<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Fri Feb  10 13:19:42 CET 2017
//


namespace OussamaElgoumri\Exceptions;


class ImagePublicPathNotSetException extends \Exception
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
