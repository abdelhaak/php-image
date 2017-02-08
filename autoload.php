<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 21:08:54 WET 2017
//


require_once __DIR__ . '/vendor/autoload.php';


global $base_path;
$base_path = getcwd();

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
