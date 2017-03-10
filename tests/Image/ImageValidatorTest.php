<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 23:03:41 WET 2017
//

namespace OussamaElgoumri\Components\Image;

use OussamaElgoumri\TestCommon;
use OussamaElgoumri\Components\Image\ImageValidator;

class ImageValidatorTest extends TestCommon
{
    public function test_validate()
    {
        $f = $this->faker;

        $results = (new ImageValidator)
            ->validate($f->image(sys_get_temp_dir(), 1, 1));

        $this->assertTrue($results);
    }

    /**
     * @expectedException OussamaElgoumri\Exceptions\ImagePathNotValidException
     */
    public function test_fail_1_validate()
    {
        (new ImageValidator)->validate('not/valid/thing');
    }

    /**
     * @expectedException OussamaElgoumri\Exceptions\ImageTypeNotValidException
     */
    public function test_fail_2_validate()
    {
        (new ImageValidator)->validate(__FILE__);
    }

    /**
     * @expectedException OussamaElgoumri\Exceptions\ImageTypeNotAllowedException
     */
    public function test_fail_3_validate()
    {
        Config__set('IMAGE_ALLOWED_TYPES', 'psd,jp2');
        (new ImageValidator)->validate($this->faker->image('/tmp', 1, 1));
    }

    /**
     * @expectedException OussamaElgoumri\Exceptions\ImageTypeIsDeniedException
     */
    public function test_fail_4_validate()
    {
        Config__set('IMAGE_ALLOWED_TYPES', '');
        Config__set('IMAGE_DENIED_TYPES', 'png,jpeg');
        (new ImageValidator)->validate($this->faker->image('/tmp', 1, 1));
    }

    public function test_getTypes()
    {
        list($obj, $m) = $this->getMethod('getTypes');

        $results = $m->invoke($obj, 'not_exists', '');
        $this->assertFalse($results);

        Config__set('IMAGE_ALLOWED_TYPES', 'gif,png,jpeg');
        $results = $m->invoke($obj, 'IMAGE_ALLOWED_TYPES', '');
        $this->assertEquals($results, [IMAGETYPE_GIF, IMAGETYPE_PNG, IMAGETYPE_JPEG]);

        Config__set('IMAGE_ALLOWED_TYPES', 'gif png jpeg   psd');
        $results = $m->invoke($obj, 'IMAGE_ALLOWED_TYPES', '');
        $this->assertEquals($results, [IMAGETYPE_GIF, IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_PSD]);

        Config__set('IMAGE_ALLOWED_TYPES', 'imagetype_psd, jpeg,  IMAGETYPE_PNG');
        $results = $m->invoke($obj, 'IMAGE_ALLOWED_TYPES', '');
        $this->assertEquals($results, [IMAGETYPE_PSD, IMAGETYPE_JPEG, IMAGETYPE_PNG]);

        try {
            Config__set('IMAGE_ALLOWED_TYPES', 'png,jpegpsd', '');
            $m->invoke($obj, 'IMAGE_ALLOWED_TYPES', '');
            $this->assertFalse(true);
        } catch (\OussamaElgoumri\Exceptions\ImageTypeNotSupportedBy__exif_imagetype__Exception $e) {
            $this->assertTrue(true);
        }
    }
}
