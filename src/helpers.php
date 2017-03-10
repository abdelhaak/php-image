<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 10:37:18 WET 2017
//

use OussamaElgoumri\Components\Image;
use OussamaElgoumri\Components\Image\ImageValidator;
use OussamaElgoumri\Components\Image\ImageOptimizer;
use OussamaElgoumri\Components\Image\ImageResizer;

if (!function_exists('Image__help')) {
    /**
     * WTF! is all this about?
     *
     * @print about&help
     */
    function Image__help($arg = null)
    {
        if ($arg) {
            echo "Unknown option: {$arg}\n\n";
        }

        echo <<<IMAGE__HELP
Created by Oussama Elgoumri (c) 2017 - <contact@sec4ar.com>

    config:export   export default configuration to 'config/image.php'

Enjoy!

IMAGE__HELP;

        exit;
    }
}
