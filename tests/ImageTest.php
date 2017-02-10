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
        $results = Image__get($this->faker->imageUrl);

        var_dump($results);
    }

    public function test_createDirs()
    {
        list($obj, $m) = $this->getMethod('createDirs');

        $dir = $m->invoke($obj);
        $this->assertFileExists($dir);
    }

    public function test_buildPath()
    {
        list($obj, $m) = $this->getMethod('buildPath');

        $results = $m->invoke($obj, '/test/file/', '/another/one', 'and/another/');
        $this->assertEquals($results, '/test/file/another/one/and/another');
    }

    public function test_setBasePath()
    {
        list($obj, $m) = $this->getMethod('setBasePath');

        $m->invoke($obj, __DIR__);
        $i = new FileSystemIterator(base_path(), FileSystemIterator::SKIP_DOTS);
        $yep = false;
        foreach ($i as $file) {
            if ($file->getFilename() === 'vendor') {
                $yep = true;
            }
        }

        $this->assertTrue($yep);
    }

    public function test_getPath()
    {
        $f = $this->faker;
        list($obj, $m) = $this->getMethod('getPath');

        // Test existing file:
        $file = tempnam('', '');
        $results = $m->invoke($obj, $file);
        $this->assertEquals($file, $results);

        // Test remote file
        $results = $m->invoke($obj, $f->imageUrl(1, 1));
        $this->assertFileExists($results);

        // Test uploaded file
        $_FILES = ['image'];
        $_FILES['image'] = [
            'tmp_name' => $file,
        ];
        $results = $m->invoke($obj, 'image');
        $this->assertEquals($results, $file);
    }

    /**
     * @expectedException Exception
     */
    public function test_fail_getPath()
    {
        list($obj, $m) = $this->getMethod('getPath');

        $m->invoke($obj, '/fake/path/to/file.jpg');
    }

    public function test_validate()
    {
        list($obj, $m) = $this->getMethod('validate');

        $results = $m->invoke($obj, $this->faker->imageUrl(1, 1));
        $this->assertTrue($results);
    }

    /**
     * @expectedException OussamaElgoumri\Exceptions\ImageNotValidException
     */
    public function test_fail_validate()
    {
        list($obj, $m) = $this->getMethod('validate');

        $m->invoke($obj, __FILE__);
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
