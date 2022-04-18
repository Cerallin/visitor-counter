<?php

namespace lib\Abstracts;

class Singleton
{
    protected static $instance;

    protected function __clone()
    {
        // Disable cloning
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof static)
            self::$instance = new static(...func_get_args());

        return self::$instance;
    }
}