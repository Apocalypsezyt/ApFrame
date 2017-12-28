<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/10
 * Time: 12:05
 */


/*
 *  全局函数文件
 * */

/*
 *  快速使用session操作
 *
 *  $key键值 $value如果为null 则为获取
 * */
function session($key, $value = null)
{
    if(is_null($value))
        return \apphp\session\session::get($key);
    else
        \apphp\session\session::set($key,$value);
}

/*
 *  快速使用方法获取数据
 *
 *  $request要求
 * */
function obtain($request)
{
    return \apphp\Core\Request::instance()->obtain($request);
}

/*
 *  快速打印值并退出运行
 *
 *  $var 变量
 * */
function dd($var)
{
    var_dump($var);
    exit();
}

/*
 *  输出 JSON 数据
 *
 *  $info 要格式化的数组
 * */
function json(array $info)
{
    \apphp\Facilitate\Format\Json::echoEncode($info);
}