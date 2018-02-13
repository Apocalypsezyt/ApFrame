<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/10/26
 * Time: 10:49
 */

namespace apphp\Core;


use apphp\database\Database;
use apphp\error\error;

abstract class Model
{
    /**
     * @access protected
     * @name string $model 当前模型名称
     * */
    protected $model;
    /**
     * @access protected
     * @name string $table 表的名称
     * */
    protected $table;
    /**
     * @access protected
     * @name string $primary_key 表的名称
     * */
    protected $primary_key;
    /**
     * @access protected
     * @name Database $connection 当前使用的数据库信息
     * */
    protected $connection;
    /**
     * @access protected
     * @name string $sql 使用的 sql 语句
     * */
    protected $sql;
    /**
     * @access protected
     * @name string $sql_param 当前 sql 语句的参数
     * */
    protected $sql_param;
    /**
     * @access protected
     * @name string $specific_field 特定的字段
     * */
    protected $specific_field;
    /**
     * @access protected
     * @name string $limit 要获取多少列
     * */
    protected $limit;
    /**
     * @access protected
     * @name string $where 当前条件
     * */
    protected $where;

    /**
     *  @method public 构造函数
     *
     * */
    function __construct()
    {
        // 当前使用的数据库
        $NowDb = APP_SERVICE['database'][NOW_USE_DB];
        $this->connection = new $NowDb();
        // 未指定当前模型的表 将默认指定
        if(is_null($this->table))
        {
            // 获取子类名
            $class = get_class($this);
            $model_name = explode('\\',$class);
            $this->table = end($model_name);
        }
        // 未指定当前模型的主键 将默认指定
        if(is_null($this->primary_key))
        {
            $this->primary_key = "id";
        }
    }

    /**
     * @method public 用来存储字段和值
     *
     * @param string $key 键
     * @param string $value 值
     * */
    function __set($key, $value)
    {
        $this->sql_param[$key] = $value;
    }

    /**
     *  @method public 用来获取通过__set存储字段和值
     *
     *  @param string $key 键
     *
     *  @return string 返回对应的值
     * */
    function __get($key)
    {
        return $this->sql_param[$key];
    }

    /**
     *  @method protected 把获取字段的数组转换成字符串
     *
     *  @return string 返回转换好的字符串
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

    /**
     *  通过id查找数据
     *
     * @param int|string $id 提供数据库的id
     *
     *  @return Model 返回表中的以ID为主要的数据
     * */
    public  function find($id)
    {
        $this->where($this->primary_key, $id);

        return $this->connection->selectSpecificField("*", $this->table, $this->where);
    }

    /**
     *  获取表中所有信息
     *
     *  @return Model 返回表中的所有数据
     * */
    public function getAll()
    {
        $specif_field = $this->specificFieldToString();

        return $this->connection->selectSpecificField($specif_field,$this->table);
    }

    /**
     *  @method public 获取特定的信息
     *
     *  @return Collection 返回信息
     * */
    public function get()
    {
        $specif_field = $this->specificFieldToString();
        $info = $this->connection->selectSpecificField($specif_field, $this->table, $this->where, $this->limit);
        $group = new Collection($info);

        //return $group;

        return$info;
    }

    /**
     *  @method public 插入一条数据信息
     * */
    public function create()
    {
        return $this->connection->insert($this->sql_param,$this->table);
    }

    /**
     *  @method public 更新信息
     * */
    public function update()
    {
        if(!$this->where && DB_FRIEND)
        {
            error::ActiveError('f_upw');
        }
        return $this->connection->update($this->sql_param, $this->table, $this->where);
    }

    /**
     *  @method public 删除信息
     * */
    public function remove()
    {
        if(!$this->where && DB_FRIEND)
        {
            error::ActiveError('m_delete');
        }

        return $this->connection->delete($this->table,$this->where);
    }

    /**
     * @method public 查询的条件
     *
     * @param string $column 列名
     * @param string $value 条件
     *
     * @return Model
     * */
    public function where($column,$value)
    {
        if(is_null($this->where)) {
            $this->where .= " WHERE ${column} = '${value}'";
        }
        else{
            $this->where .= " AND ${column} = '${value}'";
        }

        return $this;
    }

    /**
     * @method public 或条件
     *
     * @param string $column 列名
     * @param string $value 条件
     *
     * @return Model
     * */
    public function orWhere($column, $value)
    {
        if(is_null($this->where)) {
            $this->where .= " WHERE ${column} = '${value}'";
        }
        else{
            $this->where .= " OR ${column} = '${value}'";
        }

        return $this;
    }

    /**
     * @method public 显示多少行
     *
     * @param int $limit
     *
     * @return Model
     * */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     *  @method protected 清空sql语句和条件
     * */
    protected function emptySql()
    {
        $this->sql_param = null;
        $this->where = null;
        $this->limit = null;
        $this->sql = null;
    }

    /**
     * @method protected 一对一
     *
     * @param string $model 模型名
     * @param string $foreign_key 外键名
     *
     * @return Model
     * */
    protected function hasOne($model, $foreign_key)
    {
        $refection = new \ReflectionClass($model);
        $method = $refection->getMethod('where');
        $info = $method->invokeArgs($refection, [$foreign_key, '1']);

        return $info;
    }

    /**
     * @method protected 一对多
     *
     * @param string $model 模型名
     * @param string $foreign_key 外键名
     *
     * @return Model
     * */
    protected function hasMany($model, $foreign_key)
    {
        $refection = new \ReflectionClass($model);
        $method = $refection->getMethod('where');
        $info = $method->invokeArgs($refection, [$foreign_key, '1']);

        return $info;
    }

}