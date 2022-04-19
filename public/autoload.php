<?php

// 注册 autoloader
spl_autoload_register(function ($className) {
    $filePath = __DIR__ . "/../" . str_replace(array('\\', '_'), '/', $className) . ".php";

    if (is_readable($filePath)) {
        return require $filePath;
    }
});
