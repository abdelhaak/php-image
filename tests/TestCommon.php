<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Wed Feb  8 12:07:41 WET 2017
//


namespace OussamaElgoumri;


use Faker\Factory;
use ReflectionClass;
use ReflectionMethod;


class TestCommon extends \PHPUnit_Framework_TestCase
{
    protected $faker;

    public function setUp()
    {
        $this->faker = Factory::create();
    }

    /**
     * Set the method visibility of the given object to public.
     *
     * @param string    $obj    object name or instance
     * @param string    $m      method name
     *
     * @return array
     */
    public function getMethod($m, $obj = null)
    {
        if (!$obj) {
            $obj = $this->getObj();
        }

        $rc = new ReflectionClass($obj);
        $obj = $rc->newInstanceWithoutConstructor();

        $rm = new ReflectionMethod($obj, $m);
        $rm->setAccessible(true);

        return [ $obj, $rm ];
    }

    /**
     * Get the object name.
     *
     * @return string
     */
    private function getObj()
    {
        preg_match('/(.*\\\\.*)Test/', static::class, $m);

        return $m[1];
    }
}
