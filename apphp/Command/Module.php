<?php

namespace apphp\Command;

use apphp\Core\Command;

class Module extends Command
{
    public function fire($argv)
    {
        $command = $argv[0];
        switch ($command)
        {
            case 'get':
                $this->get();
                break;
            case 'make':
                $name = $argv[1];
                $this->make($name);
                break;
            default:
        }
    }

    protected function make($name)
    {
        $dir = APP_PATH . $name;

        if (file_exists($dir)) {
            $this->line("该模块已经被创建过");
        }

        if (!file_exists($dir)) {
            mkdir($dir, 777, true);
            mkdir($dir . '/Controller', 777, true);   // 创建控制器文件夹
            mkdir($dir . '/Model', 777, true);   // 创建模型文件夹
            mkdir($dir . '/Middleware', 777, true); // 创建中间件文件夹

            $this->line("该模块已经创建成功");
        }
        else
            $this->line("该模块已被创建过");
    }

    protected function get()
    {
        $dir =  APP_PATH;
        $dirs = array();
        foreach (scandir($dir) as $file)
        {
            if($file === '.' || $file == '..')
            {
                continue;
            }
            $dirs[] = $file;
            $this->line(" ${file}");
        }
    }
}
