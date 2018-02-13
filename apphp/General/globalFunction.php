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
 * 使用 public 文件夹中的文件
 *
 * $src 文件名
 * */
function asset($src)
{
    $src = $src[0] == '/' ? $src : '/' . $src;

    return $src;
}

/*
 *  快速使用session操作
 *
 *  $key键值 $value如果为null 则为获取
 * */
function session($key, $value = null)
{
    if(is_null($value))
        return \apphp\Core\Storage\Session::get($key);
    else
        \apphp\Core\Storage\Session::set($key,$value);
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
    $type = gettype($var);
    switch ($type)
    {
        case 'int':
        case 'string':
                    echo "${type}(${var})";
                    break;
        case 'array':
                    var_dump($var);
                    break;
        default:
                    var_dump($var);
                    break;
    }
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


/*
 *  实例化 Csrf 对象
 * */
function csrf()
{
    return new \apphp\Core\Safe\Csrf();
}

/*
 *  读取目录下 .set 的配置
 * */
function readSet($key, $default = 'undefined')
{
    $config = \apphp\Core\Cache::instance();

    return $config->readConfigKey($key, $default);
}

function one_to_one($to_table, $fk_id, $value)
{
    $mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
    $query = $mysqli->query("SELECT * FROM ${to_table} WHERE ${fk_id} = ${value}");

    $assoc = $query->fetch_assoc();

    return $assoc;
}

function one_to_many($to_table, $fk_id, $value)
{
    $mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
    $query = $mysqli->query("SELECT * FROM ${to_table} WHERE ${fk_id} = ${value}");

    $assoc = $query->fetch_all(MYSQLI_ASSOC);

    return $assoc;
}

function many_to_many($pk_id, $and_table, $to_table, $fk_id, $value)
{
    $mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
    $query = $mysqli->query("SELECT * FROM ${and_table} WHERE ${pk_id} = ${value} INNER JOIN ${to_table} ON ${and_table}.${fk_id} = ${to_table}.${fk_id}");

    $assoc = $query->fetch_all(MYSQLI_ASSOC);

    return $assoc;
}