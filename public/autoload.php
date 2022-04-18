<?php

// 注册 autoloader
spl_autoload_register(function ($className) {
    $file_path = __DIR__ . "/../" . str_replace(array('\\', '_'), '/', $className) . ".php";

    if (is_readable($file_path)) {
        return require $file_path;
    }
});
