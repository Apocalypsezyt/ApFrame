<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 23:57
 */

namespace apphp\database;


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
}