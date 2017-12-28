<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/9
 * Time: 23:54
 */

namespace apphp\database;


interface Database
{
//    构造方法
    function __construct();
//    查询方法 有返回值
    public function query($sql);
//    查找指定数据并返回
    public function selectSpecificField($field,$table,$where,$limit,$order);
//    插入数据
    public function insert(array $fileValue,$table);
//    更新数据
    public function update(array $fileValue,$table,$where);
//    执行方法 无返回值
    public function exec($sql);
//    关闭方法
    public function close();
}