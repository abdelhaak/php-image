<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 11:57:11 WET 2017
//


namespace OussamaElgoumri;


use ReflectionClass;
use ReflectionMethod;
use FileSystemIterator;


class ImageTest extends TestCommon
{
    public function test_get()
    {
        putenv('IMAGE_PUBLIC=/tests/public/images');
        $results = Image__get($this->faker->imageUrl);

        var_dump($results);
    }

    public function test_getUuid()
    {
        list($obj, $m) = $this->getMethod('getUuid');

        $image = $this->faker->image('/tmp', 1, 1);
        $ext = image_type_to_extension(exif_imagetype($image));
        $hash = sha1_file($image);
        $results = $m->invoke($obj, $image);
        $this->assertTrue(strpos($results, $hash) === 0);

        putenv("IMAGE_UUID=%time%.%ext%");
        $results = $m->invoke($obj, $image);
        $this->assertRegExp("/\d+{$ext}$/", $results);
    }
}
