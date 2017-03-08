<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Tue Mar  7 17:34:56 WET 2017
//

namespace OussamaElgoumri\Components\Image;

use OussamaElgoumri\TestCommon;

class ImageOptimizerTest extends TestCommon
{
    public function test_run()
    {
        copy(base_path('tests/optimize-me.png'), '/tmp/optimize-me.png');
        $imageOptimizer = new ImageOptimizer();
        $imageOptimizer->run('/tmp/optimize-me.png');

        $original_size = filesize(base_path('tests/optimize-me.png'));
        $optimized_size = filesize('/tmp/optimize-me.png');

        $this->assertLessThan($original_size, $optimized_size);
    }
}
