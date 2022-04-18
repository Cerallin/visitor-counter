<?php

namespace lib;

use lib\Abstracts\Singleton;

/**
 * Config manager.
 *
 * @method static string debug()
 *
 * @final
 */
class Config extends Singleton
{
    private $config;

    public function __construct()
    {
        $this->config = array_merge(
            // default
            [
                'all' => ['debug' => false],
                'log' => ['directory' => 'logs'],
            ],
            parse_ini_file(__DIR__ . '/../config.ini', true)
        );
    }

    /**
     * Get value of a key.
     *
     * @param string $section
     * @param string $key
     *
     * @return string|null
     */
    public static function get($section, $key)
    {
        return self::getInstance()->config[$section][$key] ?? null;
    }

    /**
     * Get all key-values under a section.
     *
     * @param string $section
     *
     * @return array
     */
    public static function getAll($section)
    {
        $config = self::getInstance()->config ?? null;
        return ($config[$section] ?? []) + ($config['all'] ?? []);
    }

    /**
     * Search key globally.
     *
     * @param string $key
     * @return string|null
     */
    public static function __callStatic($key, $arguments)
    {
        $config = self::getInstance()->config ?? null;
        if ($value = ($config['all'][$key] ?? null)) {
            return $value;
        }

        foreach ($config as $hash) {
            if ($value = ($hash[$key] ?? null)) {
                return $value;
            }
        }
    }
}
