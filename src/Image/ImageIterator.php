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

    /**
     * Initialize Constructor.
     *
     * @param array     $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Search for item with height.
     *
     * @param  string|integer    $height
     * @return mixed
     */
    public function whereHeight($height)
    {
        $results = [];

        foreach ($this->data as $item) {
            if ($item->height == (int) $height) {
                $results[] = $item; 
            }
        }

        return $this->checkResults($results);
    }

    /**
     * Search for items with the width.
     *
     * @param  string|integer    $width
     * @return mixed
     */
    public function whereWidth($width)
    {
        $results = [];

        foreach ($this->data as $item) {
            if ($item->width == (int) $width) {
                $results[] = $item;
            }
        }

        return $this->checkResults($results);
    }

    /**
     * Get the item with the requested width and height.
     *
     * @param string|integer    $width
     * @param string|integer    $height
     */
    public function whereWidthAndHeight($width, $height = null)
    {
        list($width, $height) = $this->parseWidthHeight($width, $height);
        $results = [];

        foreach ($this->data as $item) {
            if ($item->width == $width && $item->height == $height) {
                $results[] = $item;
            }
        }

        return $this->checkResults($results);
    }

    /**
     * Alias: whereWidthHeight()
     *
     * @param string|integer    $width
     * @param string|integer    $height
     *
     * @return mixed
     */
    public function whereWidthHeight($width, $height = null)
    {
        return $this->whereWidthAndHeight($width, $height);
    }

    /**
     * Parse the width and height.
     *
     * @param string|integer    $width
     * @param string|integer    $height
     *
     * @return array
     */
    private function parseWidthHeight($width, $height = null)
    {
        if (is_null($height)) {
            if (is_string($width)) {
                if (strpos($width, 'x') > 0 || strpos($width, 'X') > 0) {
                    preg_match('/(\d+) *x *(\d+)/i', $width, $m);

                    if (isset($m[1])) {
                        $width = (int) $m[1];
                    }

                    if (isset($m[2])) {
                        $height = (int) $m[2];
                    }
                } else {
                    $width = $height = (int) $width;
                }
            } elseif (is_integer($width)) {
                $height = $width;
            }
        } else {
            $width = (int) $width;
            $height = (int) $height;
        }

        return [$width, $height];
    }

    /**
     * Return the right results.
     *
     * @param array     $results
     * @return mixed
     */
    private function checkResults($results)
    {
        if (count($results) == 1) {
            return $results[0];
        }

        if (empty($results)) {
            return null;
        }

        return $results;
    }
}
