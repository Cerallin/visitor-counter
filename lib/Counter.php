<?php

namespace lib;

class Counter
{
    private $db;

    public function __construct()
    {
        $this->db = new DB;
        $this->db->connect();
    }

    public function countHost($host)
    {
        return [
            'pv' =>
            $this->db->select("COUNT(*) count FROM {$this->db->table} WHERE `host` = ?", [$host]),
            'uv' =>
            $this->db->select("COUNT(*) count FROM (
                SELECT DISTINCT `ip`
                FROM {$this->db->table} WHERE `host` = ?) tmp", [$host]),
        ];
    }

    public function countPage($page)
    {
        return [
            'pv' =>
            $this->db->select("COUNT(*) count FROM {$this->db->table} WHERE `page` = ?", [$page]),
            'uv' =>
            $this->db->select("COUNT(*) count FROM (
                SELECT DISTINCT `ip`
                FROM {$this->db->table} WHERE `page` = ?) tmp", [$page]),
        ];
    }

    public function insertNew($ip, $host, $page)
    {
        $this->db->createTable();

        $res = $this->db->insert([
            'ip'    => $ip,
            'host'  => $host,
            'page'  => $page,
        ]);

        Log::info("Inserted record: (ip: $ip, host: $host, page: $page)");

        return $res;
    }
}
