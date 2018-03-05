<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 3/5/2018
 * Time: 18:20
 */

namespace apphp\Core\Response;


trait Json
{
    /**
     * @access public 将数组变成JSON格式数据
     * @param array $info 以数组形式存在的数据
     * @return string 返回json字符串
     * */
    public function json($info)
    {
        return json_encode($info);
    }
}