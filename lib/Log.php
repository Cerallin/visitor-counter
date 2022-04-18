<?php

namespace lib;

use Exception;
use lib\Abstracts\Singleton;

/**
 * 记录日志。
 *
 * @method static void debug(string $message)
 * @method static void info(string $message)
 * @method static void warning(string $message)
 * @method static void error(string $message)
 *
 * @final
 */
class Log extends Singleton
{
    /**
     * @var array 日志等级，因为 PHP 7 没有 enum
     */
    const logLevels = ['debug', 'info', 'warning', 'error'];

    /**
     * @var array 日志的配置信息
     */
    private $config;

    /**
     * @var string 日志文件名，根据日期自动生成
     */
    private $logFile;

    /**
     * 实例化函数，加载配置且生成日志文件名。
     */
    public function __construct()
    {
        $this->config = Config::getAll('log');
        $this->logFile = 'log-' . date('Y-m-d') . '.log';
    }

    /**
     * 实现一套日志记录方法。
     */
    public static function __callStatic($level, $arguments)
    {
        $level = strtolower($level);
        if (in_array($level, self::logLevels, true)) {
            // 日志等级为debug的信息，只有在打开调试的时候记录。
            if ($level === 'debug' && self::isDebug())
                // 三个点意为把 $arguments 展开作为函数参数
                self::writeLog($level, ...$arguments);
            else
                self::writeLog($level, ...$arguments);
        }
    }

    /**
     * 判断是否打开调试。
     *
     * @return bool
     */
    private static function isDebug()
    {
        return self::$config['debug'] ?? false;
    }

    /**
     * 写入日志。
     *
     * @param string $level     日志等级
     * @param string $message   日志信息
     */
    private static function writeLog($level, $message)
    {
        $instance = self::getInstance();
        $dir = __DIR__ . "/../" . $instance->config['directory'];
        // 如果日志路径不存在
        if (!is_dir($dir)) {
            // 试图自己创建路径
            if (!mkdir($dir, 0777))
                // TODO 改用新的Exception
                throw new Exception("Cannot create directory: " . $dir);
        }

        // 日志信息开头有事件和日志等级信息
        $time = date('G:i:s');
        $header = "[{$time}] [{$level}] ";
        // 写入文件
        file_put_contents($dir . '/' . $instance->logFile, $header . $message . "\n", FILE_APPEND | LOCK_EX);
    }
}
