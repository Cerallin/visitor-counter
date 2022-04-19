<?php

namespace lib\Exceptions;

use Exception;

/**
 * 请求解析异常。
 *
 * @final
 */
class InvalidRequestException extends Exception
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
