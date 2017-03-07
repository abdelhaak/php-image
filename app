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

if (count($argv) == 1 || preg_match('/\b[-]?[-]?h[e]?[l]?[p]?\b/', $argv[1])) {
    about();
}

switch ($argv[1]) {
case 'make:exception':
    if (!isset($argv[2])) {
        echo "I need a name for the exception: Name[Exception]\n\n";
        exit(1);
    }

    Exception::create($argv[2]);
    break;
}
