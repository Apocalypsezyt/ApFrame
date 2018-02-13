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
use apphp\Core\Storage\session;

class Init
{
    public static function initAll()
    {
        // composer vendor 组件自动加载
        require_once "../vendor/autoload.php";
        // ApFrame自动加载文件并注册
        require_once ROOT_PATH . "apphp/autoload.php";
        spl_autoload_register("\\apphp\\autoload::autoload");
        // 加载全局函数
        require_once "general/globalFunction.php";
        // 加载配置文件
        require_once ROOT_PATH . 'config/config.php';

        // 启用本库自定义错误
        set_error_handler("\\apphp\\error\\error::showError");

        // 自动加载样式
        //loadStyle::loadCss(CSS_ARRAY);
        //loadStyle::loadScript(JS_ARRAY); DEBUG 1 问题无法完全输出js_array只输出了一个

        // 开始执行程序
        self::exec();

    }

    /*
     *
     *  执行程序
     *
     * */
    private static function exec()
    {
        // 加载所使用的数据库

        // 启动或者不启用session
        session::start();

        // 路由配置文件
        require_once ROOT_PATH.'route/route.php';

        // 开始使用路由
        Route::run();
    }
}