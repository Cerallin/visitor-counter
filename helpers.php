<?php

/**
 * 驼峰转下划线，或者使用其他分隔符。
 *
 * @param string $str String to uncamelize
 * @param string $sep Seperator
 */
function uncamelize($str, $sep = '_')
{
    return strtolower(
        preg_replace('/([a-z])([A-Z])/', "$1{$sep}$2", $str)
    );
}
