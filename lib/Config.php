<?php

namespace lib;

use lib\Abstracts\Singleton;

/**
 * 配置管理器。
 *
 * @method static string debug()
 *
 * @final
 */
class Config extends Singleton
{
    /**
     * @var array 存储配置信息的键值对
     */
    private $config;

    /**
     * 实例化方法，
     */
    public function __construct()
    {
        // 默认设置可被覆盖，配置文件缺失时默认值仍存在。
        $this->config = array_merge(
            [
                'all' => ['debug' => false],
                'log' => ['directory' => 'logs'],
            ],
            // 第三个参数的true意即保留ini文件分段。
            parse_ini_file(__DIR__ . '/../config.ini', true)
        );
    }

    /**
     * 获取某段某键的值，没有则返回NULL。
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
     * 获取某段下全部键值对，没有则返回空数组。
     *
     * @param string $section
     *
     * @return array
     */
    public static function getAll($section)
    {
        $config = self::getInstance()->config ?? [];
        return ($config[$section] ?? []) + ($config['all'] ?? []);
    }

    /**
     * 实现键的全局查找，没有则返回NULL。
     *
     * @param string $key
     * @return string|null
     */
    public static function __callStatic($key, $arguments)
    {
        $config = self::getInstance()->config ?? null;
        // 首先查找all段
        if ($value = ($config['all'][$key] ?? null)) {
            return $value;
        }

        // all段没有就便利所有段
        foreach ($config as $hash) {
            if ($value = ($hash[$key] ?? null)) {
                return $value;
            }
        }
    }
}
