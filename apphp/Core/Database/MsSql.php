<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 23:57
 */

namespace apphp\Core\database;


class MsSql implements Database
{

    protected $conn;

    function __construct()
    {
        $this->conn = new \PDO('mssql:dbname='.MSSQL_DATABASE.';host:'.MSSQL_HOST,MSSQL_USER,MSSQL_PASSWORD);
    }

    public function query($sql)
    {
        htmlspecialchars($sql);
        return $this->conn->query($sql);
    }

    public function exec($sql)
    {
        $this->conn->exec($sql);
    }

    public function close()
    {

    }

    public function insert(array $fileValue, $table)
    {
        // TODO: Implement insert() method.
    }

    public function selectSpecificField($field, $table, $where, $limit, $order)
    {
        // TODO: Implement selectSpecificField() method.
    }

    public function update(array $fileValue, $table, $where)
    {
        // TODO: Implement update() method.
    }
}