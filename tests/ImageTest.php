<?php

//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Thu Mar  9 10:08:54 WET 2017
//

namespace OussamaElgoumri\Components;

use OussamaElgoumri\Components\Image\ImageItem;
use OussamaElgoumri\Components\Image\ImageResizer;
use OussamaElgoumri\Exceptions\ImageException;
use OussamaElgoumri\TestCommon;

class ImageTest extends TestCommon
{
    public function test_getDefaultConfig()
    {
        list($obj, $m) = $this->getMethod('getDefaultConfig');
        $results = $m->invoke($obj, [
            'IMAGE_ALLOWED_TYPES' => 'jpg',
            'IMAGE_DENIED_TYPES' => 'psd',
            'IMAGE_UUID' => '%hash%.%ext%',
            'IMAGE_SIZES' => '',
        ]);

        $this->assertEquals($results, [
            'IMAGE_ALLOWED_TYPES' => 'jpg',
            'IMAGE_DENIED_TYPES'  => 'psd',
            'IMAGE_DIRS'          => 'Y/i/d',
            'IMAGE_PUBLIC'        => 'public/images',
            'IMAGE_RELATIVE'      => 'images',
            'IMAGE_UUID'          => '%hash%.%ext%',
            'IMAGE_OPTIMIZE'      => true,
        ]);
    }

    public function test_compile_get()
    {
        $image = new Image($this->faker->imageUrl);
        $data = $image->get();

        foreach ($data as $ii) {
            $this->assertInstanceOf(ImageItem::class, $ii);
        }
    }

    public function test_resize()
    {
        $image = new Image;
        Config__load($image->getConfigFilename(), $image->default_config);
        Config__set('IMAGE_PUBLIC', 'tests/public/images');
        $image->resolved_img = $this->faker->image('/tmp', 1, 1);
        $image
            ->uuid()
            ->copy()
            ->resize();

        $this->assertTrue(is_array($image->resized_imgs));

        foreach ($image->resized_imgs as $img) {
            $this->assertFileExists($img['path']);
            $this->assertTrue(strpos($img['path'], $img['relative_path']) > 0);
        }
    }

    public function test_copy()
    {
        $image = new Image;
        Config__load($image->getConfigFilename(), $image->default_config);
        Config__set('IMAGE_PUBLIC', 'tests/public/images');
        $image->resolved_img = $this->faker->image('/tmp', 1, 1);
        $image->uuid()->copy();
        $this->assertFileExists($image->path);
        $this->assertTrue(strpos($image->path, $image->relative_path) > 0);
    } 

    public function test_getRelativePath()
    {
        Config__load('', ['IMAGE_RELATIVE' => 'images']);
        list($obj, $m) = $this->getMethod('getRelativePath');
        $rp = $m->invoke($obj, '/a/b/c/images/d/e/f.jpeg');
        $this->assertEquals($rp, '/images/d/e/f.jpeg');
    }

    public function test_createDirs()
    {
        $image = new Image;
        Config__load($image->getConfigFilename(), $image->default_config);
        Config__set('IMAGE_PUBLIC', 'tests/public/images');
        list($obj, $m) = $this->getMethod('createDirs');
        $results = $m->invoke($obj);
        $this->assertTrue(is_dir($results));
    }

    public function test_sanitize()
    {
        list($obj, $m) = $this->getMethod('sanitize');
        $results = $m->invoke($obj, '/home', '/oussama/', '/my-folder/');
        $this->assertEquals($results, '/home/oussama/my-folder');
    }

    public function test_uuid()
    {
        $image = new Image;
        Config__load($image->getConfigFilename(), $image->default_config);
        $image->resolved_img = $this->faker->image('/tmp', 1, 1);
        $image->uuid();
        $this->assertRegExp('/[a-z0-9]{40}--[0-9]{10}\.[a-z]{3,4}/i', $image->uuid);
    }

    public function test_compress()
    {
        if (file_exists('/tmp/optimize-me.png')) {
            unlink('/tmp/optimize-me.png');
        }

        copy(base_path('tests/optimize-me.png'), '/tmp/optimize-me.png');
        $image = new Image;
        $image->resolved_img = '/tmp/optimize-me.png';
        $image->compress();
        $this->assertLessThan(filesize(base_path('tests/optimize-me.png')), '/tmp/optimize-me.png');
    }

    public function test_validate()
    {
        $image = new Image;
        $image->resolved_img = $this->faker->image('/tmp', 1, 1);
        $image->validate();
        $this->assertTrue(true);

        try {
            $image->resolved_img = 'xxx';
            $image->validate();
            $this->assertFalse(true);
        } catch (ImageException $e) {
            $this->assertTrue(true);
        }
    }

    public function test_resolve()
    {
        $image = new Image;
        $image->img = $in_tmp = $this->faker->image(sys_get_temp_dir(), 1, 1);
        $image->resolve();
        $this->assertFileExists($image->resolved_img);

        $image->img = $this->faker->imageUrl(1, 1);
        $image->resolve();
        $this->assertFileExists($image->resolved_img);

        $_FILES['imagename']['tmp_name'] = $in_tmp;
        $image->img = 'imagename';
        $this->assertFileExists($image->resolved_img);
    }

    public function test_downloadToTmp()
    {
        list($obj, $m) = $this->getMethod('downloadToTmp');
        $results = $m->invoke($obj, $this->faker->imageUrl(1, 1));
        $this->assertFileExists($results);
        $this->assertRegExp('/^' . preg_quote(sys_get_temp_dir(), '/') . '/', $results);
    }
}
