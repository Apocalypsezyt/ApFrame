<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/10/26
 * Time: 10:12
 */

namespace apphp\database;


class Redis implements NoSql
{
    protected $redis;

    function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1',6379);
    }

    /*
     *  将字符串存储到redis
     *
     *  $key 键 $value 值
     * */
    public function set($key,$value)
    {
        $this->redis->set($key,$value);
    }

    /*
     *  通过key值获取到值
     *
     *  $key键
     *
     *  @return 通过key值查找的value值
     * */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /*
     *  通过key值存储列表
     *
     *  $key 键 $value 值
     * */
    public function lpush($key,$value)
    {
        $this->redis->lPush($key,$value);
    }

    /*
     *  通过key值获取数值
     *
     *  $key 键 $start 开始位置 $end 结束位置
     *
     *
     *  @return 返回列表的值
     * */
    public function lrange($key, $start, $end)
    {
        return $this->redis->lRange($key,$start,$end);
    }

}