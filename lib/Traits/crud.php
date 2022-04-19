<?php

namespace lib\Traits;

use lib\DBQuery;
use lib\Exceptions\DBQueryException;

/**
 * CRUD意为增删改查，是数据库的四种基本操作。
 * 该trait封装了所有SQL语句的执行。
 */
trait crud
{
    public function __call($name, $arguments)
    {
        $query = array_shift($arguments);
        if (!$query instanceof DBQuery) {
            throw new DBQueryException(
                "Invalid DBQuery, please use " . DBQuery::class . " instead of array."
            );
        }
        $statement = $this->pdo->prepare($query->string);
        foreach ($query->params as $i => $param) {
            $statement->bindValue($i + 1, $param);
        }

        $res = $statement->execute();

        if ($name == 'execute')
            return $res;
        // TODO suppport more methods, e.g., fetch, fetchAll.
        if ($name == 'fetchColumn') {
            return $statement->fetchColumn();
        }
    }

    /**
     * 插入一条记录。
     *
     * @param array $array 键值对
     *
     * @return bool
     */
    public function insert($array = [])
    {
        if (!$array)
            return false;

        $query = new DBQuery();
        $values = "";
        foreach ($array as $key => $value) {
            $query->string .= "`{$key}`,";
            $query->params[] = $value;
            $values .= "?,";
        }

        // Trim the last commas
        $query->string = substr($query->string, 0, -1);
        $values = substr($values, 0, -1);

        $query->string = "INSERT INTO {$this->table} ({$query->string}) VALUES ({$values})";

        return $this->execute($query);
    }

    /**
     * 执行一条SELECT语句。
     *
     * @param string $queryString
     * @param array $params
     *
     * @return array
     */
    public function select($queryString, $params = [])
    {
        return $this->fetchColumn(new DBQuery(
            "SELECT " . $queryString,
            $params
        ));
    }
}
