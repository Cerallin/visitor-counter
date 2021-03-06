<?php

namespace lib\Exceptions;

use Exception;

/**
 * 数据库查询异常。
 *
 * @final
 */
class DBQueryException extends Exception
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
