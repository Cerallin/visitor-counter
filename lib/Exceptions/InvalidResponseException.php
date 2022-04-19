<?php

namespace lib\Exceptions;

use Exception;

/**
 * 返回格式异常。
 *
 * @final
 */
class InvalidResponseException extends Exception
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
