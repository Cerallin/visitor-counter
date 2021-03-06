<?php

namespace lib\Exceptions;

use Exception;

/**
 * 数据库连接异常。
 *
 * @final
 */
class DBConnectException extends Exception
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
