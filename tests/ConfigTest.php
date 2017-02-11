<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Fri Feb 10 14:50:37 WET 2017
//


namespace OussamaElgoumri;


class ConfigTest extends TestCommon
{
    public function test_load()
    {
        putenv('IMAGE_PUBLIC=tests/foo/bar');
        list($obj, $m) = $this->getMethod('load');
        $results = $m->invoke($obj);
        $this->assertEquals($results['IMAGE_PUBLIC'], 'tests/foo/bar');
    }

    public function test_get()
    {
        putenv('IMAGE_PUBLIC=tests/foo/bar');
        $results = Config::getInstance()->get('public');
        $this->assertEquals($results, 'tests/foo/bar');

        $results = Config::getInstance()->get('IMAGE_PUBLIC');
        $this->assertEquals($results, 'tests/foo/bar');
    }

    public function test_set()
    {
        Config::getInstance()->set('public', 'tests/foo/bar/baz');
        $results = Config::getInstance()->get('public');
        $this->assertEquals($results, 'tests/foo/bar/baz');
    }

    public function test_singleton()
    {
        $i1 = spl_object_hash(Config::getInstance());
        $i2 = spl_object_hash(Config::getInstance());
        $this->assertEquals($i1, $i2);
    }

    public function test_config_function()
    {
        imageConfig('public', 'tests/foo/bar/boo');
        $results = imageConfig('public'); 
        $this->assertEquals($results, 'tests/foo/bar/boo');
    }
}
