<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 18:34
 */

namespace apphp;


class autoload
{
    public static function autoload($class)
    {
//        判断是不是控制器
        if(explode('\\',$class)[0] == APP_NAMESPACE)
        {
            $class = str_replace('\\', '/', $class); // 转换换行符
//            避免替换掉类里包含 APP_NAMESPACE 所写的
            $class = str_replace(APP_NAMESPACE.'/',APP_PATH.'/', $class);
            $file_name = $class.'.php';
        }
        else
        {
            $class = str_replace('\\', '/', $class); // 转换换行符
            $file_name = ROOT_PATH . '/' . $class . '.php';
        }

        require_once $file_name;
    }
}