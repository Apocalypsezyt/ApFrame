<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 18:32
 */

namespace apphp;


use apphp\Core\Route;
use apphp\Core\Storage\ApSession;
use apphp\Core\Error\error;

class Init
{

    protected static $app;

    /**
     * @access public 初始化
     * */
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

    /**
     * @access public 执行程序
     * */
    public function exec()
    {

        // 启动或者不启用session
        ApSession::start();

        // 路由配置文件
        require_once ROOT_PATH.'route/route.php';

        // 开始使用路由
        return Route::run();
    }

    /**
     * @access protected 容器初始化
     * */
    protected static function initContainer()
    {
        $app = new Container();
        $app->bind('session', ApSession::class);
        $app->bind('route', Route::class);
        self::$app = $app;
    }
}