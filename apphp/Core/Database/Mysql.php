<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 23:56
 */

namespace apphp\Core\database;


class MySql implements Database
{
    private $conn;

    function __construct()
    {
        $this->conn = new \mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE, MYSQL_PORT);
        $this->conn->set_charset(MYSQL_CHARSET);
    }

    /**
     * @method public 执行一条 sql 语句
     *
     * @param string $sql sql 语句
     *
     * @return bool 执行 sql 语句的是否成功
     * */
    public function query($sql)
    {
        htmlspecialchars($sql);
        return $this->conn->query($sql);
    }

    /**
     * @method public 查询所有数据
     *
     * @param string $field 查询的列
     * @param string $table 表名
     * @param string $where 查询的条件
     * @param string $limit 查询几列
     * @param string $order 倒序还是正序
     *
     * @return array 返回查询回来的数据
     *
     * */
    public function selectSpecificField($field, $table, $where=null, $limit=null, $order=null)
    {
        $sql = "SELECT {$field} FROM {$table}";
        if(!is_null($where))
        {
            $sql .= $where;
        }
        if(!is_null($limit))
        {
            $sql .= " LIMIT $limit";
        }
        $query = $this->query($sql);

        return $query->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @method public 插入一条数据
     *
     * @param array $fileValue 填充的数据
     * @param string $table 表的名称
     *
     * @return bool 插入的结果
     * */
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

        $sql .= $where;

        return $this->exec($sql);
    }

    /**
     * @method public 删除一条数据
     *
     * @param string $table 表的名称
     * @param string $where 条件
     *
     * @return bool 执行是否成功
     * */
    public function delete($table, $where = null)
    {
        $sql = "DELETE FROM {$table} WHERE {$where['column']}='{$where['value']}'";

        return $this->conn->query($sql);
    }

    public function exec($sql)
    {
        htmlspecialchars($sql);

        return $this->conn->query($sql);
    }

    /**
     * @method public 关闭数据库连接
     *
     * @return bool 关闭数据库是否成功
     * */
    public function close()
    {
        return $this->conn->close();
    }
}