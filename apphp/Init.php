<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 18:32
 */

namespace apphp;


use apphp\Core\Request;
use apphp\Core\Route;
use apphp\Core\Storage\Session;
use apphp\Core\Error\error;

class Init
{

    protected static $app;

    public static function initAll()
    {
        // 加载自动加载文件
        require ROOT_PATH . 'bootstrap/autoload/autoload.php';
        // 加载全局函数
        require_once "General/globalFunction.php";
        // 加载配置文件
        require_once ROOT_PATH . 'config/config.php';

        // 初始化错误
        Error::Init();

        // 自动加载样式
        //loadStyle::loadCss(CSS_ARRAY);
        //loadStyle::loadScript(JS_ARRAY); DEBUG 1 问题无法完全输出js_array只输出了一个

        // 容器初始化
        self::initContainer();

        return new static();
    }

    /*
     *
     *  执行程序
     *
     * */
    public function exec()
    {
        // 加载所使用的数据库

        // 启动或者不启用session
        Session::start();

        // 路由配置文件
        require_once ROOT_PATH.'route/route.php';

        // 开始使用路由
        return Route::run();
    }

    /**
     * 容器初始化
     *
     *
     * */
    protected static function initContainer()
    {
        self::$app = new Container();
    }
}