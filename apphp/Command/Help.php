<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/28/2018
 * Time: 13:36
 */

namespace apphp\Command;

use apphp\Core\Command;

class Help extends Command
{
    public function fire($argv)
    {
        $this->serverHelp();
        $this->moduleHelp();
        $this->controllerHelp();
    }

    /**
     * 服务器启动帮助信息
     * */
    public function serverHelp()
    {
        $this->line("启动内置服务器 — server");
    }

    /**
     * 模块帮助信息
     * */
    public function moduleHelp()
    {
        $this->line("创建模块 — module make [模块名]");
    }

    /**
     * 控制器帮助信息
     * */
    public function controllerHelp()
    {
        $this->line("创建普通控制器 — controller make [模块名] [控制器名]");
        $this->line("创建资源控制器 — controller make-resource [模块名] [控制器名]");
    }
}