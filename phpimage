#!/usr/bin/php

<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 12:48:02 WET 2017
//

require_once __DIR__ . '/autoload.php';

use OussamaElgoumri\Console\Exception;
use OussamaElgoumri\Components\Image;

if (count($argv) == 1 || preg_match('/\b[-]?[-]?h[e]?[l]?[p]?\b/', $argv[1])) {
    Image__help();
}

switch ($argv[1]) {
case 'make:exception':
    if (!isset($argv[2])) {
        echo "I need a name for the exception: Name[Exception]\n\n";
        exit(1);
    }

    Exception::create($argv[2]);
    break;

case 'config:export':
    $image = new Image;
    $configfile = base_path("config/{$image->getConfigFilename()}.php");

    if (file_exists($configfile)) {
        unlink($configfile);
        echo "Deleting config file..\n";
    }

    Config__load($image->getConfigFilename(), $image->default_config);
    echo "Config file created!\n";
    break;

default:
    Image__help($argv[1]);
}
