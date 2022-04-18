<?php

namespace lib\Traits;

trait table {
    public function createTable()
    {
        $this->pdo->exec(<<<EOF
CREATE TABLE IF NOT EXISTS `{$this->table}` (
    `id`    int NOT NULL AUTO_INCREMENT,
    `ip`    varchar(255) NOT NULL,
    `host`  varchar(255) NOT NULL,
    `page`  varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB
EOF);
    }
}