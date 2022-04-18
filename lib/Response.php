<?php

namespace lib;

class Response
{
    private $message;

    private $code;

    private $headers = [];

    public function __construct($message, $code = 200, $headers = [])
    {
        $this->message = $message;
        $this->code = $code;

        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }
    }

    public function __toString()
    {
        return $this->dispatch();
    }

    public function addHeader($key, $value)
    {
        $key = ucfirst(strtolower($key));
        $this->headers[$key] = (string) $value;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function dispatch()
    {
        if (is_string($this->message)) {
            //
        } else {
            $this->headers['Content-type'] = 'Application/json';
            $this->message = json_encode($this->message);
        }

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        http_response_code($this->code);

        return $this->message;
    }
}
