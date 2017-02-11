<?php


//
// Oussama Elgoumri
// contact@sec4ar.com
//
// Fri Feb 10 14:48:48 WET 2017
//


namespace OussamaElgoumri;


use OussamaElgoumri\Exceptions\ImageConfigKeyNotFoundException;


class Config
{
    static $instance;
    protected $config;

    protected function __construct() {  }
    private function __clone() {  }
    private function __wakeup() {  }

    /**
     * Singleton.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
            self::$instance->load();
        }

        return self::$instance;
    }

    /**
     * Load configuration.
     */
    private function load()
    {
        exec("sed -e '/^\s*$/ d' -e '/^#/d' .env.example", $options);

        foreach ($options as $option) {
            list($key, $default) = explode('=', $option);
            $this->config[$key] = getenv($key) ?: $default;
        }

        return $this->config;
    }

    /**
     * Get configuration value.
     *
     * @param  string    $key
     * @return string
     * @throws ImageConfigKeyNotFoundException
     */
    public function get($key)
    {
        return $this->config[$this->sanitizeKey($key)];
    }

    /**
     * Set config value.
     *
     * @param string    $key
     * @param string    $value
     *
     * @return string
     */
    public function set($key, $value)
    {
        $key = $this->sanitizeKey($key);
        $this->config[$key] = $value;

        return $this;
    }

    /**
     * Sanitize key.
     *
     * @param  string    $key
     * @return string
     */
    public function sanitizeKey($key)
    {
        $key = strtoupper($key);

        if (strpos($key, 'IMAGE_') !== 0) {
            $key = "IMAGE_{$key}";
        }

        if (!isset($this->config[$key])) {
            throw new ImageConfigKeyNotFoundException($key);
        }

        return $key;
    }
}
