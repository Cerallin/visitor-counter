<?php

namespace lib\Traits;

/**
 * 封装数据表相关操作。
 */
trait table {
    public function createTable()
    {
        $this->pdo->exec(<<<EOF
CREATE TABLE IF NOT EXISTS `{$this->table}` (
    `id`          int NOT NULL AUTO_INCREMENT,
    `ip`          varchar(255) NOT NULL,
    `host`        varchar(255) NOT NULL,
    `page`        varchar(255) NOT NULL,
    `created_at`  DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB
EOF);
    }
}