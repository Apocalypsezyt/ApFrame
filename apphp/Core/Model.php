<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/10/26
 * Time: 10:49
 */

namespace apphp\Core;


use apphp\error\error;

abstract class Model
{
//    当前模型名称
    protected $model;
//    表名称
    protected $table;
//    当前使用的数据库信息
    protected $connection;
//    存储SQL语句
    protected $sql;
//    存储SQL语句的参数
    protected $sql_param;
//    获取特定的字段
    protected $specific_field;
//    获取多少列
    protected $limit;
//    获取的条件
    protected $where;

    /*
     *  构造函数
     *
     * */
    function __construct()
    {
//        当前使用的数据库
        $NOWDB = "\\apphp\\database\\".NOW_USE_DB;
        $this->connection = new $NOWDB();
//        未指定当前模型的表 将默认指定
        if(is_null($this->table))
        {
//            获取子类名
            $class = get_class($this);
            $model_name = explode('\\',$class);
            $this->table = end($model_name);
        }
    }

    /*
     *  用来存储字段和值
     *
     *  $key 键 $value 值
     * */
    function __set($key, $value)
    {
        $this->sql_param[$key] = $value;
    }

    /*
     *  用来获取通过__set存储字段和值
     *
     *  $key 键
     *
     *  @return 返回值
     * */
    function __get($key)
    {
        return $this->sql_param[$key];
    }

    /*
     *  把获取字段的数组转换成字符串
     *
     *  @return 返回转换好的字符串
     * */
    protected function specificFieldToString()
    {
        $specific_field = '';

        if(is_null($this->specific_field))
        {
            return '*';
        }

        foreach ($this->specific_field as $key=>$field)
        {
            $specific_field .= $field;
            if(!$key == count($this->specific_field) - 1)
            {
                $specific_field = $specific_field.',';
            }
        }

        return $specific_field;
    }

    /*
     *  获取表中所有信息
     *
     *  @return 返回信息
     * */
    public function getAll()
    {
        $specif_field = $this->specificFieldToString();
        return $this->connection->selectSpecificField($specif_field,$this->table);
    }

    /*
     *  获取特定的信息
     *
     *  @return 返回信息
     * */
    public function get()
    {
        $specif_field = $this->specificFieldToString();
        return $this->connection->selectSpecificField($specif_field,$this->table,$this->where,$this->limit);
    }

    /*
     *  插入一条信息
     * */
    public function create()
    {
        return $this->connection->insert($this->sql_param,$this->table);
    }

    /*
     *  更新信息
     * */
    public function update()
    {
        if(!$this->where && DB_FRIEND)
        {
            error::ActiveError('f_upw');
        }
        return $this->connection->update($this->sql_param,$this->table,$this->where);
    }

    /*
     *  删除信息
     * */
    public function remove()
    {
        if(!$this->where && DB_FRIEND)
        {
            error::ActiveError('m_delete');
        }

        return $this->connection->delete($this->table,$this->where);
    }

    /*
     *  查询的条件
     *
     *  $column 列名 $value 值
     * */
    public function where($column,$value)
    {
        $this->where = ['column' => $column, 'value' => $value];

        return $this;
    }

    /*
     *  显示多少行
     *
     *  $limit
     * */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /*
     *  清空sql语句
     *
     *  及条件
     * */
    protected function emptySql()
    {
        $this->sql_param = null;
        $this->where = null;
        $this->limit = null;
        $this->sql = null;
    }
}