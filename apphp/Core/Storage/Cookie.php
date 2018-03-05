<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/10
 * Time: 0:21
 */

namespace apphp\Core\Storage;


class Cookie
{
    /*
     *
     * 启用 session 或者 关闭 session
     *
     * */
    public static function start()
    {
        if(USE_SESSION)
            session_start();
    }

    /**
     *  设置 cookie 值
     *
     * @param string $key 键值
     * @param string $value 值
     * @param mixed $time 过期时间
     * @param string $scope 作用域
     *
     * */
    public static function set($key, $value, $time = 3600 * 24 * 2, $scope = '/')
    {
        setcookie($key, $value, $time, $scope);
    }

    /**
     *  获取 cookie 值
     *
     * @param string $key 键值
     * @param string $scope 作用域
     *
     * @return string 获取到的 cookie 值
     * */
    public static function get($key, $scope = null)
    {
        if(is_null($scope))
            return $_COOKIE[$key];
        else
            return $_COOKIE[$scope][$key];
    }

    /**
     *  注销指定 cookie 值
     *
     * @param $key string 键值
     *
     * */
    public static function destroy($key)
    {
        setcookie($key, '', time()-10);
    }

}