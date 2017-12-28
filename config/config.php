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
define('APP_NAMESPACE', 'app');
define('APPHP_VER','beta v 1.0');

// 是否启用command
define('USE_COMMAND', true);

// 是否开启session
define("USE_SESSION", true);

// 使用的数据库
define("USE_DATABASE", "mysql");

// 是否启用自动加载CSS和JS
define("USE_STYLE", true);

// 是否开启严格模式
define('STRICT_MODE',true);

// 加载自定义错误
define('APPHP_ERROR',require_once ROOT_PATH."/config/errorconfig.php");

// 框架自带服务
// 是否打开微信类
define('USE_WECHAT',true);

// 加载数据库配置文件
require_once ROOT_PATH."/config/databaseconfig.php";
// 加载样式配置文件
require_once ROOT_PATH."/config/styleconfig.php";
// 加载微信配置文件
require_once ROOT_PATH. "/config/custom/wechat.config.php";
// 加载CSRF配置文件
require_once ROOT_PATH. "/config/csrfconfig.php";