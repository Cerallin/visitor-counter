<?php

namespace lib;

use Exception;
use lib\Exceptions\HostUndeclaredException;

/**
 * HTTP 请求的抽象。
 */
class Request
{
    /**
     * @var string 请求方法，PHP 7 没有 enum
     */
    private $method;

    /**
     * @var string 源网页
     */
    private $referPage;

    /**
     * 初始化请求方法以接收请求参数。
     */
    public function __construct($method)
    {
        $method = strtoupper($method);
        if (!in_array($method, ['GET', 'POST']))
            throw new Exception("Undefined request method: " . $method);

        $this->method = $method;
    }

    /**
     * 获取一个请求参数。
     *
     * @param string $key       参数名
     * @param string $default   默认值
     *
     * @return string
     */
    public function input($key, $default = null)
    {
        if ($this->method === "GET")
            return $_GET[$key] ?? null;

        if ($this->method === "POST")
            // TODO handle complex conditions, e.g., HTTP_RAW_POST_DATA
            return $_POST[$key] ?? null;
    }

    /**
     * 获取客户端IP。
     *
     * @return string
     */
    public function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];

        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * 获取源网站域名。
     *
     * @return string
     */
    public function host()
    {
        $referPage = $this->refer();
        preg_match('/^https?\:\/\/([^\/]+)/i', $referPage, $matched);
        return $matched[1] ?? null;
    }

    /**
     * 获取源网页路径。
     *
     * @return string
     */
    public function page()
    {
        return $this->refer();
    }

    /**
     * 获取请求头中Referer的内容。
     * 本函数存在的意义是防止 host() 和 page() 调用的先后顺序不同导致的频繁取变量。
     *
     * @return string
     */
    private function refer()
    {
        // 当 $this->referPage 有内容时直接返回
        if ($this->referPage)
            return $this->referPage;

        // 否者从请求头中解析
        $this->referPage = $_SERVER['HTTP_REFERER'] ?? null;

        // 没有Referer的请求是不被允许的
        if (!$this->referPage)
            throw new HostUndeclaredException("Host not declared as REFERER.");

        Log::debug("Got a request from referer: " . $this->referPage);

        return $this->referPage;
    }
}
