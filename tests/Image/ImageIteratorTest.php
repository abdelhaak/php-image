<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Fri Mar 10 16:57:14 WET 2017
//

namespace OussamaElgoumri\Components\Image;

use OussamaElgoumri\TestCommon;

class ImageIteratorTest extends TestCommon
{
    public function test_parseWidthHeight()
    {
        list($obj, $m) = $this->getMethod('parseWidthHeight');

        list($w, $h) = $m->invoke($obj, 800, 600);
        $this->assertEquals($w, 800);
        $this->assertEquals($h, 600);

        list($w, $h) = $m->invoke($obj, 800);
        $this->assertEquals($w, 800);
        $this->assertEquals($h, 800);

        list($w, $h) = $m->invoke($obj, '800', "600");
        $this->assertEquals($w, 800);
        $this->assertEquals($h, 600);

        list($w, $h) = $m->invoke($obj, '800');
        $this->assertEquals($w, 800);
        $this->assertEquals($h, 800);

        list($w, $h) = $m->invoke($obj, '800x600');
        $this->assertEquals($w, 800);
        $this->assertEquals($h, 600);
    }
}
