<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/10
 * Time: 0:21
 */

namespace apphp\Core\Storage;


class Session
{

    protected static $instance;

    /**
     * Session 做初始化
     * 首先先指定它存储的目录
     * */
    function __construct()
    {
        $driver = strtolower(SESSION_DRIVER);
        switch ($driver){
            case 'file':
                        ini_set('session.save_path', ROOT_PATH . 'runtime/session/');
                        break;
            case 'redis':
                        ini_set('session.save_path', 'redis');
                        ini_set('session.save_handler', 'tcp://127.0.0.1');
                        break;
        }
    }

    /**
     * 静态方法实例化
     *
     * @return Session 返回实例化好的 Session 对象
     * */
    public static function instance() : Session
    {
        if(is_null(self::$instance)){
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
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
     *  设置 session 值
     *
     * @param $key string 键值
     * @param $value string 存储的值
     * @param $scope string 作用域
     * */
    public static function set($key, $value, $scope = null) : void
    {
        if(is_null($scope))
            $_SESSION[$key] = $value;
        else
            $_SESSION[$scope][$key] = $value;
    }

    /**
     *  获取 session 值
     *
     * @param $key string key键值
     * @param $scope string 作用域
     *
     * @return string 返回的值
     * */
    public static function get($key, $scope = null)
    {
        if(is_null($scope))
            return $_SESSION[$key] ?? null;
        else
            return $_SESSION[$scope][$key] ?? null;
    }

    /**
     *
     *  注销掉当前用户的 session 值
     *
     * */
    public static function destroy() : void
    {
        session_destroy();
    }

}