<?php

namespace lib;

use Exception;
use lib\Exceptions\DBConnectException;
use lib\Traits\crud;
use lib\Traits\table;
use PDO;

class DB
{
    use crud, table;

    private $pdo;

    private $conf;

    public $table = 'records';

    public function __construct()
    {
        $this->conf = Config::getAll('db');
    }

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
            throw new DBConnectException("Connect to database failed.", 500, $e);
        }
    }
}

$db = new DB;
$db->connect();
