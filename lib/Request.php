<?php

namespace lib;

use Exception;
use lib\Exceptions\HostUndeclaredException;

class Request
{
    private $method;

    private $referPage;

    public function __construct($method)
    {
        $method = strtoupper($method);
        if (!in_array($method, ['GET', 'POST']))
            throw new Exception("Undefined request method: " . $method);

        $this->method = $method;
    }

    public function input($key, $default = null)
    {
        if ($this->method === "GET")
            return $_GET[$key] ?? null;

        if ($this->method === "POST")
            // TODO handle complex conditions, e.g., HTTP_RAW_POST_DATA
            return $_POST[$key] ?? null;
    }

    public function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];

        return $_SERVER['REMOTE_ADDR'];
    }

    public function host()
    {
        $referPage = $this->refer();
        preg_match('/^https?\:\/\/([^\/]+)/i', $referPage, $matched);
        return $matched[1] ?? null;
    }

    public function page()
    {
        return $this->refer();
    }

    private function refer()
    {
        $this->referPage = $_SERVER['HTTP_REFERER'] ?? null;

        if (Config::debug()) {
            $this->referPage = 'http://localhost';
        }

        if (!$this->referPage)
            throw new HostUndeclaredException("Host not declared as REFER.");

        return $this->referPage;
    }
}
