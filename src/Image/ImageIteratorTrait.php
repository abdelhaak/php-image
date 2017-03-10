<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Fri Mar 10 16:01:55 WET 2017
//

namespace OussamaElgoumri\Components\Image;

trait ImageIteratorTrait
{
    public function rewind()
    {
        reset($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function next()
    {
        return next($this->data);
    }

    public function valid()
    {
        $key = key($this->data);

        return ($key !== NULL && $key !== FALSE);
    }
}
