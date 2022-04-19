<?php

use lib\Config;
use lib\Counter;
use lib\Request;
use lib\Response;

require_once __DIR__ . '/autoload.php';

try {
    $request = new Request('GET');
    $ip = $request->ip();
    $host = $request->host();
    $page = $request->page();

    $counter = new Counter;

    // 先插入一条访问记录
    $counter->insertNew($ip, $host, $page);

    // 然后封装返回信息
    $response = new Response($counter->get($host, $page));
    // 设置跨域
    $response->addHeader('Access-Control-Allow-Origin', '*');

    die($response);
} catch (Exception $e) {
    // 如果开启调试就返回更丰富的信息
    if (Config::debug())
        die(new Response($e->getMessage(), 500));
    // 否则劝劝你
    die(new Response("Error occured, for more information, please enable debugging.", 500));
}
