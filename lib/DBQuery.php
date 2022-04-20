<?php

namespace lib;

/**
 * SQL查询语句和绑定的变量。
 *
 * @property string $string
 * @property array $params
 */
class DBQuery {
    public $string;

    public $params;

    public function __construct($string = "", $params = [])
    {
        $this->string = $string;
        $this->params = $params;
    }
}
