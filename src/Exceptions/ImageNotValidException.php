<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 14:52:11 CET 2017
//

namespace OussamaElgoumri\Exceptions;

class ImageNotValidException extends ImageException
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
