<?php

namespace lib;

use JsonSerializable;

/**
 * 计数器的抽象类。
 *
 */
class Counter implements JsonSerializable
{
    /**
     * @var lib\DB 数据库对象的实例
     */
    private $db;

    /**
     * @var int 网站总访问量
     */
    private $site_pv;

    /**
     * @var int 网站独立访问量
     */
    private $site_uv;

    /**
     * @var int 页面访问量
     */
    private $page_pv;

    /**
     * @var int 页面独立访问量
     */
    private $page_uv;

    /**
     * 构造函数，初始化数据库连接。
     */
    public function __construct()
    {
        $this->db = new DB;
        $this->db->connect();
    }

    /**
     * 计算访问量。
     *
     * @param string $host 源站域名
     * @param string $page 源站页面
     *
     * @return array
     */
    public function get($host, $page)
    {
        // TODO 减少查询次数
        $this->page_pv = $this->db->select("COUNT(*) count
                    FROM {$this->db->table} WHERE `page` = ?", [$page]);

        $this->page_uv = $this->db->select("COUNT(*) count FROM (
                SELECT DISTINCT `ip`
                FROM {$this->db->table} WHERE `page` = ?) tmp", [$page]);

        $this->site_pv = $this->db->select("COUNT(*) count
                    FROM {$this->db->table} WHERE `host` = ?", [$host]);

        $this->site_uv = $this->db->select("COUNT(*) count FROM (
                    SELECT DISTINCT `ip`
                    FROM {$this->db->table} WHERE `host` = ?) tmp", [$host]);

        return $this;
    }

    /**
     * 插入一条访问记录。
     *
     * @param string $ip    IP地址
     * @param string $host  源站域名
     * @param string $page  源站页面
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

    /**
     * 当作为json_encode参数时的序列化处理。
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'site_pv' => $this->site_pv,
            'site_uv' => $this->site_uv,
            'page_pv' => $this->page_pv,
            'page_uv' => $this->page_uv,
        ];
    }
}
