<?php

namespace lib\Exceptions;

use Exception;

/**
 * 源网站解析异常。
 *
 * @final
 */
class HostUndeclaredException extends Exception
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
