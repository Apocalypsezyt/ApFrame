<?php

namespace apphp\Command;

use apphp\Core\Command;

class Controller extends Command
{

    protected $namespace;

    function __construct()
    {
        $this->namespace = APP_NAMESPACE;
    }

    public function fire($argv)
    {
        $command = $argv[0];
        switch ($command)
        {
            case 'make':
                $module = $argv[1];
                $controller = $argv[2];
                $this->make($module, $controller);
                break;
            default:
        }
    }

    protected function make($module_name, $controller_name)
    {
        $dir = APP_PATH . $module_name;

        if (!file_exists($dir)) {
            $this->line("控制器模块未被创建");
        }

        $content = <<<EOF
<?php

namespace {$this->namespace}\\{$module_name}\\Controller;

use apphp\\Core\\Controller;

class {$controller_name} extends Controller
{
    public function index()
    {
        
    }
}
EOF;
        $filename = $dir . '/Controller/' . $controller_name . '.php';
        if (!file_exists($filename)) {
            $phpfile = fopen($filename, "w");
            fwrite($phpfile, $content);

            $this->line("创建控制器成功");
        }
        else
            $this->line("控制器已经存在");

        return true;
    }
}
