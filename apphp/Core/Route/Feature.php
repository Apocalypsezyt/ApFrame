<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/28/2018
 * Time: 12:18
 */

namespace apphp\Core\Route;


trait Feature
{
    /**
     * @method protected 判断路由第一个字符是不是斜杠,如果存在就去掉斜杠
     *
     *  @param string route 路由名
     *
     *  @return string 返回以数组形式存在的数据
     * */
    protected function removalSlash($route)
    {
        //注册主页的路由
        if($route[0] == '/' && strlen($route) == 1){
            return '/';
        }
        return $route[0] == '/' ? substr($route, 1) : $route;
    }
}