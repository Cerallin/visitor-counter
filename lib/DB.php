<?php

namespace lib;

use Exception;
use lib\Exceptions\DBConnectException;
use lib\Traits\crud;
use lib\Traits\table;
use PDO;

/**
 * SQL操作的抽象类。
 */
class DB
{
    /* 引入两个trait */
    use crud, table;

    /**
     * @var \PDO 数据库连接实例
     */
    private $pdo;

    /**
     * @var array 数据库连接配置
     */
    private $conf;

    /**
     * @var string 数据表名
     */
    public $table = 'records';

    /**
     * 实例化时加载数据库连接选项。
     */
    public function __construct()
    {
        $this->conf = Config::getAll('db');
    }

    /**
     * 建立一个数据库连接。
     *
     * @throws lib\Exceptions\DBConnectException
     */
    public function connect()
    {
        $dsn = sprintf(
            "%s:host=%s;port=%s;dbname=%s;charset=utf8",
            $this->conf['type'],
            $this->conf['host'],
            $this->conf['port'],
            $this->conf['dbname']
        );
        try {
            $this->pdo = new PDO(
                $dsn,
                $this->conf['username'],
                $this->conf['password']
            );
            Log::debug("DB Connected.");
        } catch (Exception $e) {
            Log::error("Connect to DB failed.");
            Log::info("dsn: " . $dsn);
            // 不直接抛出$e，保护数据库账号密码隐私
            throw new DBConnectException("Connect to database failed.", 500, $e);
        }
    }
}
