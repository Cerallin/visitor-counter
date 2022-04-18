<?php

namespace lib;

use Exception;
use lib\Abstracts\Singleton;

/**
 * Log provider.
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
    const logLevels = ['debug', 'info', 'warning', 'error'];

    private $config;

    private $logFile;

    public function __construct()
    {
        $this->config = Config::getAll('log');

        $this->logFile = 'log-' . date('Y-m-d') . '.log';
    }

    public static function __callStatic($level, $arguments)
    {
        $level = strtolower($level);
        if (in_array($level, self::logLevels, true)) {
            if ($level === 'debug' && self::isDebug())
                self::writeLog($level, ...$arguments);
            else
                self::writeLog($level, ...$arguments);
        }
    }

    private static function isDebug()
    {
        return self::$config['debug'] ?? false;
    }

    private static function writeLog($level, $message)
    {
        $instance = self::getInstance();
        $dir = __DIR__ . "/../" . $instance->config['directory'];
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777))
                throw new Exception("Cannot create directory: " . $dir);
        }

        $time = date('G:i:s');
        $header = "[{$time}] [{$level}] ";
        file_put_contents($dir . '/' . $instance->logFile, $header . $message . "\n", FILE_APPEND | LOCK_EX);
    }
}
