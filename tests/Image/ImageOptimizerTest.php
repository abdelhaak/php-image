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
        copy(base_path('tests/optimize-me.jpg'), '/tmp/optimize-me.jpg');
        $imageOptimizer = new ImageOptimizer();
        $imageOptimizer->run('/tmp/optimize-me.jpg');

        $original_size = filesize(base_path('tests/optimize-me.jpg'));
        $optimized_size = filesize('/tmp/optimize-me.jpg');

        $this->assertLessThan($original_size, $optimized_size);
    }
}
