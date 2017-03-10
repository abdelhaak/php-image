<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Fri Mar 10 11:18:42 WET 2017
//

namespace OussamaElgoumri\Components\Image;

use OussamaElgoumri\TestCommon;

class ImageItemTest extends TestCommon
{
    public function test_workflow()
    {
        $path = base_path('tests/optimize-me.png');
        $relative_path = '/tests/optimize-me.png';

        // Set Usage:
        $imageItem = new ImageItem;
        $imageItem->setPath($path);
        $imageItem->setRelativePath($relative_path);
        $imageItem->setType(exif_imagetype($path));
           
        // Request Info:
        $width = $imageItem->getWidth();
        $this->assertEquals(getimagesize($path)[0], $width);

        $height = $imageItem->getHeight();
        $this->assertEquals(getimagesize($path)[1], $height);

        $mime = $imageItem->getMime();
        $this->assertEquals($mime, 'image/png');

        $size = $imageItem->getSize();
        $this->assertEquals(filesize($path), $size);

        $this->assertEquals($imageItem->getPath(), $path);
        $this->assertEquals($imageItem->getRelativePath(), $relative_path);
        $this->assertEquals(exif_imagetype($path), $imageItem->getType());

        $this->assertRegExp('/^data:image\/png;base64,iVBORw0K/', $imageItem->toBase64());
    }
}
