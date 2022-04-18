<?php

namespace lib\Traits;

/**
 * CRUD意为增删改查，是数据库的四种基本操作。
 *
 * 该trait封装了所有SQL语句的执行。
 */
trait crud
{
    public function __call($name, $arguments)
    {
        $query = array_shift($arguments);
        $statement = $this->pdo->prepare($query['string']);
        foreach ($query['params'] as $i => $param) {
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

    public function insert($array = [])
    {
        if (!$array)
            return false;

        $query = [
            'string' => "",
            'params' => [],
        ];
        $values = "";
        foreach ($array as $key => $value) {
            $query['string'] .= "`{$key}`,";
            $values .= "?,";
            $query['params'][] = $value;
        }

        // Trim the last commas
        $query['string'] = substr($query['string'], 0, -1);
        $values = substr($values, 0, -1);

        $query['string'] = "INSERT INTO {$this->table} ({$query['string']}) VALUES ({$values})";

        return $this->execute($query);
    }

    public function select($queryString, $params = [])
    {
        return $this->fetchColumn([
            'string' => "SELECT " . $queryString,
            'params' => $params,
        ]);
    }
}
