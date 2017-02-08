<?php


/**
 * Oussama Elgoumri
 * contact@sec4ar.com
 *
 * Wed Feb  8 11:16:30 WET 2017
 */


namespace OussamaElgoumri;


class Curl
{
    /**
     * Get data using get request.
     *
     * @param string	$link
     *
     * @return mixed
     */
    public static function get($link, $options = [])
    {
        $c = curl_init($link);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_USERAGENT, rua());

        foreach ($options as $key => $value) {
            curl_setopt($c, $key, $value);
        }

        $data = curl_exec($c);

        if (!$data) {
            throw new \Exception(curl_error($c));
        }

        curl_close($c);

        return $data;
    }

    /**
     * Send a very basic post request.
     *
     * @param string	$link
     * @param string	$fields
     *
     * @return mixed
     */
    public static function post($link, $fields = [], $options = [])
    {
        $c = curl_init($link);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_USERAGENT, rua());
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $fields);

        foreach ($options as $key => $value) {
            curl_setopt($c, $key, $value);
        }

        $data = curl_exec($c);

        if (!$data) {
            throw new \Exception(curl_error($c));
        }

        curl_close($c);

        return $data;
    }
}
