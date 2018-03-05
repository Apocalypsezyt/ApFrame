<?php
    // ApFrame 自动加载文件并注册
    require_once ROOT_PATH . "apphp/autoload.php";
    spl_autoload_register("\\apphp\\autoload::autoload");
    // composer vendor 组件自动加载
    require_once ROOT_PATH . "/vendor/autoload.php";