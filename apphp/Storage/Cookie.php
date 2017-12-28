<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/10
 * Time: 0:21
 */

namespace apphp\Storage;


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

    /*
     *
     *  设置 session 值
     *
     *  $key键值 $scope作用域 $value值
     *
     * */
    public static function set($key, $value, $time = 3600 * 24 * 2, $scope = null)
    {
        setcookie($key,$value,$time);
    }

    /*
     *
     *  获取 session 值
     *
     *  $key键值 $scope作用域
     *
     * */
    public static function get($key, $scope = null)
    {
        if(is_null($scope))
            return $_SESSION[$key];
        else
            return$_SESSION[$scope][$key];
    }

    /*
     *
     *  注销所有 session 值
     *
     * */
    public static function destory()
    {
        session_destroy();
    }

}