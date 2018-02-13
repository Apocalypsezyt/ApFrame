<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/10/26
 * Time: 10:12
 */

namespace apphp\Core\database;


class Redis implements NoSql
{
    protected $redis;

    function __construct()
    {
        switch (REDIS_DRIVER){
            case 'phpredis':
                            $this->redis = new \Redis();
                            $this->redis->connect(REDIS_HOST, REDIS_PORT);
                        break;
            case 'predis':
            default:
                            $this->redis = new \Predis\Client([
                                'scheme' => 'tcp',
                                'host' => REDIS_HOST,
                                'port' => REDIS_PORT
                            ]);
                        break;
        }
    }

    /**
     *  @method public 将字符串存储到redis
     *
     *  @param string $key 键
     *  @param string $value 值
     * */
    public function set($key,$value)
    {
        $this->redis->set($key,$value);
    }

    /**
     *  @method public 通过key值获取到值
     *
     *  @param key键
     *
     *  @return string|int|bool 通过key值查找的value值
     * */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * @method public 通过key值存储列表
     *
     * @param string $key 键
     * @param string $value 值
     * */
    public function lpush($key,$value)
    {
        $this->redis->lPush($key,$value);
    }

    /**
     * @method public 通过key值获取数值
     *
     * @param string $key 键
     * @param string $start 开始位置
     * @param string $end 结束位置
     *
     *  @return array 返回列表的值
     * */
    public function lrange($key, $start, $end)
    {
        return $this->redis->lRange($key,$start,$end);
    }

}