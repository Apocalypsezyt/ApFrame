<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 18:40
 */

namespace apphp;


define('ROOT_PATH', dirname(__DIR__).'/'); // 网站根路径


// 加载自动初始化文件
require_once "Init.php";
// 框架初始化
$app = Init::initAll();
// 开始执行
$app->exec();