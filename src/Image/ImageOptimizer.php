<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Tue Mar  7 14:12:20 WET 2017
//

namespace OussamaElgoumri\Components\Image;

use ImageOptimizer\OptimizerFactory;

class ImageOptimizer
{
    /**
     * Run image optimizer.
     *
     * @param string    $img
     */
    public function run($img)
    {
        $factory = new OptimizerFactory;
        $optimizer = $factory->get();
        $optimizer->optimize($img);
    }
}
