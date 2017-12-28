<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/11/24
 * Time: 9:06
 */

namespace apphp\Core;


class Group
{

    protected $info = array();

    public function __set($name, $value)
    {
        $this->info[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->info[$name]))
        {
            return $this->info[$name];
        }

        return "暂未定义";
    }

    public function isEmpty()
    {
        if(count($this->info)==0||empty($this->info))
        {
            return true;
        }

        return false;
    }

    public function count()
    {
        return count($this->info);
    }
}