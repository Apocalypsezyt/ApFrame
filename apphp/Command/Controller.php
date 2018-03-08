<?php

namespace apphp\Command;

use apphp\Core\Command;

class Controller extends Command
{

    protected $namespace = APP_NAMESPACE;

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
            case 'make-resource':
                $module = $argv[1];
                $controller = $argv[2];
                $this->makeResource($module, $controller);
                break;
            case 'get':
                $module = $argv[1];
                $this->get($module);
                break;
            default:
        }
    }

    /**
     * @access protected 新建控制器
     * @param string $module_name 模块名
     * @param string $controller_name 控制器名
     * @return bool
     * */
    protected function make($module_name, $controller_name)
    {
        $dir = APP_PATH . $module_name;

        if (!file_exists($dir)) {
            $this->line("控制器所在的模块未被创建");
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

    /**
     * @access protected 新建资源控制器
     * @param string $module_name 模块名
     * @param string $controller_name 控制器名
     * @return bool
     * */
    protected function makeResource($module_name, $controller_name)
    {
        $dir = APP_PATH . $module_name;

        if (!file_exists($dir)) {
            $this->line("控制器所在的模块未被创建");
        }

        $content = <<<EOF
<?php

namespace {$this->namespace}\\{$module_name}\\Controller;

use apphp\\Core\\Controller;

class {$controller_name} extends Controller
{
    /**
      *  @access public 显示该模块中的所有数据 GET方法
      */
    public function index()
    {
        
    }
    
    /**
      * @access public 显示对应id的数据 GET方法
      * @param int | string \$id 
      */
    public function show(\$id)
    {
    
    }
    
    /**
      * @access public 显示创建的表单 GET方法
      */
    public function create()
    {
    
    }
    
    /**
      * @access public 显示对应id的修改表单 GET方法
      * @param int | string \$id 
      */
    public function edit(\$id)
    {
    
    }
    
    /**
      * @access public 创建数据
      */
    public function store()
    {
    
    }
    
    /**
      * @access public 修改对应ID的数据
      * @param int | string \$id 
      */
    public function update(\$id)
    {
    
    }
    
    /**
      * @access public 删除对应ID的数据
      * @param int | string \$id 
      */
    public function delete(\$id)
    {
    
    }
}
EOF;
        $filename = $dir . '/Controller/' . $controller_name . '.php';
        if (!file_exists($filename)) {
            $phpfile = fopen($filename, "w");
            fwrite($phpfile, $content);

            $this->line("创建资源控制器成功");
        }
        else
            $this->line("控制器已经存在");

        return true;
    }

    /**
     * @access protected 获取模块中所有控制器
     * @param string $module 模块名
     * */
    protected function get($module)
    {
        $dir =  APP_PATH . $module .'/controller/';
        $dirs = array();
        foreach (scandir($dir) as $file)
        {
            if($file === '.' || $file == '..')
            {
                continue;
            }
            $controller = substr($file,0,stripos($file,'.php'));
            $this->line($controller);
        }
    }
}
