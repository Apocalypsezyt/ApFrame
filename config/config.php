<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 18:27
 */

/*
 *
 * 目录
 *
 * 对应 APPHP库的路径 你上级的的路径
 *
 * */
//define('ROOT_PATH', dirname(__DIR__).'/'); // 网站根路径
define('APPHP_PATH', ROOT_PATH.'apphp/'); // 网站核心路径
define('APP_PATH', ROOT_PATH.'application/');  // 框架应用目录
define('BOOT_PATH', ROOT_PATH.'bootstrap/');  // 引导应用目录
define('CONFIG_PATH', ROOT_PATH.'config/');  // 框架的配置目录
define('RESOURCE_PATH', ROOT_PATH.'resource/');  // 资源目录
define('APP_NAMESPACE', 'app');
define('APPHP_VER','beta v 1.1');

// 注册服务
define('APP_SERVICE', require CONFIG_PATH . '/app.php');
// 是否启用command
define('USE_COMMAND', readSet('USE_COMMAND', true));
// 是否开启session
define("USE_SESSION", readSet('USE_SESSION', true));
// 使用的数据库
define("USE_DATABASE", readSet('USE_DATABASE'));
// 是否启用自动加载CSS和JS
define("USE_STYLE", true);
// 是否开启严格模式
define('STRICT_MODE',true);
// 报错方式 frame为框架自带报错 whoops则为该报错
define('APPHP_ERROR_MODE', readSet('APPHP_ERROR_MODE', 'frame'));
// 加载自定义错误
define('APPHP_ERROR',require_once CONFIG_PATH . "/errorconfig.php");
// 加载数据库配置文件
require_once CONFIG_PATH . "databaseconfig.php";
// 加载样式配置文件
require_once CONFIG_PATH . "styleconfig.php";
// 加载 CSRF 配置文件
require_once CONFIG_PATH . "csrfconfig.php";
// 加载 session 配置文件
require_once CONFIG_PATH . 'session.php';

// 使用什么方式加密
define('PASSWORD_HASH', PASSWORD_DEFAULT);

// 框架自带服务
// 微信类服务的启用
define('USE_WECHAT',true);
// 加载微信配置文件
require_once CONFIG_PATH . "custom/wechat.config.php";