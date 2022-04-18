<?php

use lib\Config;
use lib\Counter;
use lib\Request;
use lib\Response;

require_once __DIR__ . '/autoload.php';

try {
    $request = new Request('POST');
    $ip = $request->ip();
    $host = $request->host();
    $page = $request->page();

    $counter = new Counter;

    $counter->insertNew($ip, $host, $page);

    $response = new Response($counter->countHost($host));
    $response->addHeader('Access-Control-Allow-Origin', '*');

    die($response);
} catch (Exception $e) {
    if (Config::debug())
        die(new Response($e->getMessage()));
    die(new Response("Error occured, for more information, please enable debugging.", 500));
}
