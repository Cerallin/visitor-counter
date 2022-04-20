<?php

namespace lib\Abstracts;

/**
 * 单例模式的一种PHP实现。
 *
 * 继承此类者可以通过self::getInstance()获取自身的一个单例。
 */
class Singleton
{
    /**
     * @var self 存储自身的唯一的实例（单例）
     */
    protected static $instance;

    /**
     * 禁用clone魔术方法。
     *
     * @return void
     */
    protected function __clone()
    {
        // Disable cloning
    }

    /**
     * 获取自身单例。
     *
     * 注意new关键字后面是static而不是self，否则实例化的不是子类而永远是Singleton。
     *
     * @return static
     */
    public static function getInstance()
    {
        /* 若self::$instance尚未存储自身的一个单例，则实例化并存储。 */
        if (!self::$instance instanceof static)
            self::$instance = new static(...func_get_args());

        /* 同时返回该实例 */
        return self::$instance;
    }
}