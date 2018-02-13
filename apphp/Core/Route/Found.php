<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/11/2018
 * Time: 18:40
 */

namespace apphp\Core\Route;


trait Found
{
    /**
     * @method protected 判断路由第一个字符是不是斜杠,如果存在就去掉斜杠
     *
     *  @param string route 路由名
     *
     *  @return array|bool 返回以数组形式存在的数据
     * */
    protected function hasSlash($route)
    {
        return $route;
    }

    /**
     *
     * @method protected 从方法组里查找，是否存在该路由
     *
     * @param string $url 路由名key
     * @param string $method_group 哪个方法组 get|post|put|delete
     *
     *  @return array | bool 返回这个路由组级路由名和参数
     * */
    protected function foundHasRoute($url,$method_group)
    {
        // 没有参数默认执行该方法
        if(isset($this->route_group[$method_group][$url])){
            return ['route' => $this->route_group[$method_group][$url] , 'param' => array()];
        }
        // 有参数则执行该方法
        else{
            $param = array();
            $group_key_arr = isset($this->route_group[$method_group]) ? array_keys($this->route_group[$method_group]) : [];
            $url_arr = explode('/', $url);

            $temp_url_arr = $url_arr;
            $temp_url_arr[count($temp_url_arr)-1] = "\{(\w+)\}";
            $preg_url = implode('\/', $temp_url_arr);
            foreach ($group_key_arr as $item) {
                if (preg_match("/^${preg_url}$/", $item)) {
                    $param[] = $url_arr[count($url_arr)-1];
                    return ['route' => $this->route_group[$method_group][$item] , 'param' => $param];
                }
            }
        }

        return false;
    }
}