<?php

namespace lib;

use JsonSerializable;
use lib\Exceptions\InvalidResponseException;

/**
 * 返回的抽象。
 */
class Response
{
    /**
     * @var string|Stringable 返回的内容，类型为字符串或能转换成字符串的类。
     */
    private $content;

    /**
     * @var int 状态码
     */
    private $statusCode;

    /**
     * @var array 请求头数组
     */
    private $headers = [];

    /**
     * 实例化时可以设置返回内容、状态码和返回头。
     *
     * @param string $content
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct($content, $statusCode = 200, $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;

        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }
    }

    /**
     * 自动转换为字符串的魔术方法。本项目中总是结合 die() 使用
     *
     * @return string
     */
    public function __toString()
    {
        return $this->dispatch();
    }

    /**
     * 添加一个返回头。
     *
     * @param string $key
     * @param string $value
     *
     * @return lib\Response
     */
    public function addHeader($key, $value)
    {
        // 先全小写再首字母大写
        $key = ucfirst(strtolower($key));
        // 把value强制转换为字符串
        $this->headers[$key] = (string) $value;
        // 返回自身指针从而实现流式API
        return $this;
    }

    /**
     * 设置 HTTP 状态码。
     *
     * @param int $statusCode
     *
     * @return lib\Response
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * 打包发送。
     */
    public function dispatch()
    {
        if (is_string($this->content)) {
            //
        } elseif ($this->content instanceof JsonSerializable) {
            // 此处假定 $this->content 可以被编码为JSON格式。
            $this->headers['Content-type'] = 'Application/json';
            $this->content = json_encode($this->content);
        } else {
            throw new InvalidResponseException("Invalid response format.");
        }

        // 设置请求头
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        // 设置状态码
        http_response_code($this->statusCode);

        return $this->content;
    }
}
