<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Fri Feb  10 16:26:13 CET 2017
//

namespace OussamaElgoumri\Exceptions;

class ImageConfigKeyNotFoundException extends ImageException
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
