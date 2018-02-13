<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 23:57
 */

namespace apphp\Core\database;


class Sqlite implements Database
{
    private $sqlite;

    function __construct()
    {
        $this->sqlite = new \SQLite3(RESOURCE_PATH . 'database/' . SQLITE_FILE);
    }

    public function query($sql)
    {
        return $this->sqlite->query($sql);
    }

    public function exec($sql)
    {
        return $this->sqlite->exec($sql);
    }

    /**
     * @method public 关闭数据库连接
     * @return bool 是否关闭成功
     * */
    public function close(): bool
    {
        return $this->sqlite->close();
    }

    /**
     * @method public 插入一条数据
     *
     * @param array $fileValue 填充的数据
     * @param string $table 表的名称
     *
     * @return bool 插入的结果
     * */
    public function insert(array $fileValue, $table)
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

        return $this->fetch_all($query);
    }

    /**
     * 实现mysql的fetch_all
     * @param \SQLite3Result $query
     * @param int $flag
     *
     * @return array
     * */
    protected function fetch_all(\SQLite3Result $query, $flag = SQLITE3_ASSOC)
    {
        $info = array();
        while($row = $query->fetchArray($flag)){
            $info[] = $row;
        }

        return $info;
    }
}