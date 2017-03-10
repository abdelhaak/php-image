<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Thu Mar  9 13:18:45 CET 2017
//


namespace OussamaElgoumri\Exceptions;


class ImageException extends \Exception
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
