<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 23:56
 */

namespace apphp\database;


class MySql implements Database
{
    private $conn;

    function __construct()
    {
        $this->conn = new \mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE, MYSQL_PORT);
        $this->conn->set_charset(MYSQL_CHARSET);
    }

    public function query($sql)
    {
        htmlspecialchars($sql);
        return $this->conn->query($sql);
    }

    public function selectSpecificField($field, $table, $where=null,$limit=null,$order=null)
    {
        $sql = "SELECT {$field} FROM {$table}";

        if(!is_null($where))
        {
            $sql .= " WHERE {$where['column']} = '{$where['value']}'";
        }
        if(!is_null($limit))
        {
            $sql .= " LIMIT $limit";
        }
        $query = $this->query($sql);
        return $query->fetch_all(MYSQLI_ASSOC);
    }

    public function insert(array $fileValue ,$table)
    {
        $array_keys = array_keys($fileValue);
        $end_key = end($array_keys);
        $sql = "INSERT INTO $table(";
        $values = " VALUES(";
        foreach ($fileValue as $column=>$value)
        {
            if($column != $end_key)
            {
                $sql .= "$column,";
                $values .= is_string($value) ? "'$value'," : "$value,";
            }
            else
            {
                $sql .= "$column)";
                $values .= is_string($value) ? "'$value')" : "$value)";
            }
        }

        $sql .= $values;

        return $this->exec($sql);
    }

    public function update(array $fileValue, $table ,$where = null)
    {
        $array_keys = array_keys($fileValue);
        $end_key = end($array_keys);
        $sql = "UPDATE {$table} SET ";
        foreach ($fileValue as $column=>$value)
        {
            if($column != $end_key)
            {
                $sql .= "{$column}='{$value}',";
            }
            else
            {
                $sql .= "{$column}='{$value}'";
            }
        }

        $sql .= " WHERE {$where['column']}='{$where['value']}'";

        return $this->exec($sql);
    }

    public function delete($table, $where = null)
    {
        $sql = "DELETE FROM {$table} WHERE {$where['column']}='{$where['value']}'";

        return $this->conn->query($sql);
    }

    public function exec($sql)
    {
        htmlspecialchars($sql);
        $this->conn->query($sql);
    }

    public function close()
    {
        $this->conn->close();
    }
}