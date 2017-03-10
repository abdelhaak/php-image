<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Fri Mar 10 15:54:23 WET 2017
//

namespace OussamaElgoumri\Components\Image;

class ImageIterator implements \Iterator
{
    use ImageIteratorTrait; 

    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }
}
