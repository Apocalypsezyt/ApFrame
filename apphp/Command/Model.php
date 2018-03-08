<?php

namespace apphp\Command;

use apphp\Core\Command;

class Model extends Command
{
    public function fire($argv)
    {
        $command = $argv[0];
        switch ($command)
        {
            case 'get':
                $module = $argv[1];
                $this->get($module);
                break;
            default:
        }
    }

    /**
     * @access public 获取模块中所有模型
     * @param string $module 模块名
     * */
    public function get($module)
    {
        $dir =  APP_PATH . $module . '/model/';
        $dirs = array();
        foreach (scandir($dir) as $file)
        {
            if($file === '.' || $file == '..')
            {
                continue;
            }
            $model = substr($file,0,stripos($file,'.php'));
            $this->line($model);
        }
    }
}
