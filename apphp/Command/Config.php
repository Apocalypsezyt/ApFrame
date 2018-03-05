<?php

namespace apphp\Command;

use apphp\Core\Cache;
use apphp\Core\Command;

class Config extends Command
{
    public function fire($argv)
    {
        $command = $argv[0];
        switch ($command)
        {
            case 'cache':
                        $this->cache();
                        break;
        }
    }

    public function cache()
    {
        $cache = Cache::instance();
        $cache->cacheConfig();
        $this->line("缓存配置文件成功");
    }
}
