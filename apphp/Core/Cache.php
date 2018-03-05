<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/11/2018
 * Time: 15:47
 */

namespace apphp\Core;


use apphp\Core\Cache\Config;
//use apphp\Core\Cache\Route;

class Cache
{

    protected static $instance;

    use Config/*,Route*/{
        Config::cache as CacheConfigs;
        Config::read as ReadConfigs;
        //Route::cache as RouteConfigs;
    }

    /**
     * 实例化一个 Cache 对象
     *
     * @return Cache
     * */
    public static function instance()
    {
        if(is_null(self::$instance)){
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * 缓存配置文件
     *
     * @return bool
     * */
    public function cacheConfig() : bool
    {
        return $this->CacheConfigs();
    }

    /**
     * 读取配置文件中的一项
     *
     * @param $key string 键值
     * @param $default string 默认值
     *
     * @return string|bool
     * */
    public function readConfigKey($key, $default = 'undefined')
    {
        $key = strtoupper($key);
        $cache_arr = $this->ReadConfigs();

        return $cache_arr[$key] ?? $default;
    }
}