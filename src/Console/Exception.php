<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 13:26:50 WET 2017
//


namespace OussamaElgoumri\Console;


class Exception
{
    /**
     * Create the exception file.
     *
     * @param string    $class
     */
    public static function create($class)
    {
        $class = ucfirst(preg_replace('/(.*)exception/i', '$1', $class)) . 'Exception';
        $path = base_path("src/Exceptions/{$class}.php");

        if (file_exists($path)) {
            echo "Exception file already exists: {$path}\n\n";
            exit(1);
        }

        $content = file_get_contents(base_path('stubs/exception.txt'));
        $content = str_replace('%date%', date('D M  j H:i:s T Y'), $content);
        $content = str_replace('%class%', $class, $content);


        file_put_contents($path, $content);
        echo "Exception created: {$path}\n\n";
    }
}
