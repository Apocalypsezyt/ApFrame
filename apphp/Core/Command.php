<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/27/2018
 * Time: 10:52
 */

namespace apphp\Core;


abstract class Command
{
    /**
     * @access public 所有程序命令行都走这个命令
     * @param array $argv 参数
     * */
    abstract public function fire($argv);

    /**
     * @access protected 将数据打印到命令界面上
     * @param string $info 数据信息
     * */
    protected function line($info)
    {
        echo $info;
    }
}