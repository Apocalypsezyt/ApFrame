<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/27/2018
 * Time: 10:59
 */

namespace apphp\Command;


use apphp\Core\Command;

class Server extends Command
{
    public function fire($argv)
    {
        chdir($this->getPublicPath());
        $this->line("ApFrame 测试服务器已经运行在 < http://127.0.0.1:8000 > \n");
        passthru($this->serverCommand());
    }

    /**
     * @access protected 获取当前执行的命令
     * @return string 返回执行的命令
     * */
    protected function serverCommand()
    {
        return "php -S localhost:8000";
    }

    /**
     * @access public 获取当前public路径
     * @return string 获取当前的路径
     * */
    public function getPublicPath()
    {
        return ROOT_PATH . 'public';
    }
}