#!/usr/bin/env php
<?php
    // 网站根路径
    define('ROOT_PATH', __DIR__ . '/');
    // 加载自动加载文件
    require_once ROOT_PATH . 'bootstrap/autoload/autoload.php';
    // 加载全局函数文件
    require_once ROOT_PATH . 'apphp/General/globalFunction.php';
    // 加载配置文件
    require_once 'config/config.php';
    // 加载命令行配置文件
    require_once CONFIG_PATH . 'command.php';

    // 获取想要执行的命令
    $command = strtolower($argv[1]);
    // 删除掉 command.php 和 $argv[1]
    array_shift($argv);
    array_shift($argv);

    // 获取到 command 配置文件要使用的类
    $class = $command_app[$command];
    // 执行一系列的操作
    $reflection_class = new ReflectionClass($class);
    if(!$reflection_class->isInstantiable()){
        echo "无法被实例化";
        exit();
    }
    $reflection_method = $reflection_class->getMethod('fire');
    $reflection_method->invoke($reflection_class->newInstance(), $argv);

