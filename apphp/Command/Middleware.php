<?php

namespace apphp\Command;

use apphp\Core\Command;

class Middleware extends Command
{

    protected $namespace = APP_NAMESPACE;

    public function fire($argv)
    {
        $command = $argv[0];

        switch ($command)
        {
            case 'make':
                        $module = $argv[1];
                        $middleware = $argv[2];
                        $this->make($module, $middleware);
                        break;
        }
    }

    /**
     * @access protected 创建中间件
     * @param string $module_name 模块名
     * @param string $middleware_name 中间件名
     * @return bool
     * */
    protected function make($module_name, $middleware_name)
    {
        $dir = APP_PATH . $module_name;

        if (!file_exists($dir)) {
            $this->line("中间件所在的模块未被创建");
        }

        $content = <<<EOF
<?php

namespace {$this->namespace}\\{$module_name}\\Middleware;

use apphp\Core\Middleware;

class {$middleware_name} extends Middleware
{
    public function handle(\Closure \$next)
    {
        return \$next();
    }
}
EOF;
        $filename = $dir . '/Middleware/' . $middleware_name . '.php';
        if (!file_exists($filename)) {
            $phpfile = fopen($filename, "w");
            fwrite($phpfile, $content);

            $this->line("创建中间件成功");
        }
        else
            $this->line("中间件已经存在");

        return true;
    }
}
