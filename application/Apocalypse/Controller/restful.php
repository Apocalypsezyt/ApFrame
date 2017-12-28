<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/11/1
 * Time: 22:17
 */

namespace app\Apocalypse\Controller;


class restful
{
    public function showAll()
    {
        echo "123";
    }

    public function show($id)
    {
        echo $id;
    }

    public function create()
    {
        echo "这里是创建一个路由哦";
    }

    public function update($id)
    {
        echo "更新理由哦";
    }

    public function delete($id)
    {
        echo "删除东西的路由哦";
    }
}