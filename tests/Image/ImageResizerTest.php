<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Mar  8 12:18:53 WET 2017
//

namespace OussamaElgoumri\Components\Image;

use OussamaElgoumri\TestCommon;
use OussamaElgoumri\Components\Image;

class ImageResizerTest extends TestCommon
{
    public function test_resize()
    {
        $optimize_me = base_path('tests/optimize-me.png');
        $resized = base_path('tests/optimize-me--800x600.png');

        if (file_exists($resized)) {
            unlink($resized);
        }

        list($obj, $m) = $this->getMethod('resize');
        $m->invoke($obj, $optimize_me, ['width' => '800', 'height' => '600']);
        $this->assertFileExists($resized);
        $info = getimagesize($resized);
        $this->assertEquals($info[0], 800);
        $this->assertEquals($info[1], 600);

        if (file_exists($resized)) {
            unlink($resized);
        }
    } 

    public function test_getPath()
    {
        list($obj, $m) = $this->getMethod('getPath');
        $results = $m->invoke($obj, base_path('tests/optimize-me.png'), ['width' => '800', 'height' => '600']);
        $this->assertEquals($results, base_path('tests/optimize-me--800x600.png'));
    }

    public function test_getSizes()
    {
        $image = new Image;
        list($obj, $m) = $this->getMethod('getSizes');
        $this->assertTrue(is_array($m->invoke($obj)));

        Config__set('IMAGE_SIZES', '800x600, 1024,   100x100,75x74,24');
        $this->assertEquals($m->invoke($obj), [
            ['width' => '800', 'height' => '600'],
            ['width' => '1024', 'height' => '1024'],
            ['width' => '100', 'height' => '100'],
            ['width' => '75', 'height' => '74'],
            ['width' => '24', 'height' => '24'],
        ]);

        Config__set('IMAGE_SIZES', '800');
        $this->assertEquals($m->invoke($obj), [
            ['width' => '800', 'height' => '800'],
        ]);

        Config__set('IMAGE_SIZES', '800x600');
        $this->assertEquals($m->invoke($obj), [
            ['width' => '800', 'height' => '600'],
        ]);
    }
}
