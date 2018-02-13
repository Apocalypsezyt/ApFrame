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
require_once "init.php";

Init::initAll();
