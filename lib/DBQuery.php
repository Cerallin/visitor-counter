<?php

namespace lib;

class DBQuery {
    public $string;
    public $params;

    public function __construct($string = "", $params = [])
    {
        $this->string = $string;
        $this->params = $params;
    }
}