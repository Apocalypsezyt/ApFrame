<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 18:40
 */

namespace apphp;


define('ROOT_PATH', dirname(__DIR__).'/'); // 网站根路径

require_once ROOT_PATH.'config/config.php'; // 配置文件

/*
 *
 *  加载配置文件和自动加载文件
 *
 * */
//require_once "config.php";
require_once "init.php";

Init::initAll();
