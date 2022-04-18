<?php

namespace lib;

/**
 * 计数器的抽象类。
 *
 */
class Counter
{
    /**
     * @var lib\DB 数据库对象的实例
     */
    private $db;

    /**
     * 构造函数，初始化数据库连接。
     */
    public function __construct()
    {
        $this->db = new DB;
        $this->db->connect();
    }

    /**
     * 计算全站访问量。
     *
     * @param string $host
     *
     * @return array
     */
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

    /**
     * 计算单页访问量。
     *
     * @param string $page
     *
     * @return array
     */
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

    /**
     * 插入一条访问记录。
     *
     * @param string $ip    IP地址
     * @param string $host  源站域名
     * @param string $page  当前页面
     *
     * @return bool
     */
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
