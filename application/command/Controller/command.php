<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/11/2
 * Time: 13:07
 */

namespace app\command\Controller;


use apphp\Core\Cache;
use apphp\Core\Request;

class command
{

    protected $namespace; // 命名空间

    public function __construct()
    {
        $this->namespace = APP_NAMESPACE; // 获取默认命名空间
    }

    public function index()
    {
        echo <<<EOF
            <html>
                <head>
                    <meta charset="UTF-8">
                    <title>apphp command 工具</title>
                    <style>
                        body{
                            background: #000000;
                        }
                        .line{
                            font-size: 12px;
                            color:lawngreen;
                            width:100%;
                        }
                        .line input::-moz-placeholder{
                            color:lawngreen;
                        }
                        .line input{
                            width: 20%;
                            font-size: 12px;
                            color: lawngreen;
                            background: #000000;
                            border: none;
                        }
                    </style>
                </head>
                <body>
                    <div class="line">
                        <span>&gt;</span> apphp Ver 1.0 Beta Command 命令行工具
                    </div>
                    <div class="line">
                        <span>&gt;</span>
                        <input onkeydown="excute(this)" placeholder="请输入">
                    </div>
                </body>
                <script>
                
                    function excute(obj)
                    {
                        let e = window.event || arguments.callee.caller.arguments[0];
                        let Text = obj.value;
                        let keyword = Text.split(' '); 
                        
                        if(e.keyCode == 13)
                         {   
                             switch(keyword[0])
                             {
                                 case 'cls':
                                            cls();
                                            break;
                                 case 'help':
                                 case 'h':
                                            gethelp();
                                            break;
                                 case 'get':
                                            switch (keyword[1])
                                            {
                                                case 'module':
                                                case 'mod':
                                                      getAllmodule();
                                                break;
                                                case 'controller':
                                                case 'c':
                                                      getcontroller(keyword[2]);
                                                break;
                                                case 'model':
                                                case 'm':
                                                      getmodel(keyword[2]);
                                                break;
                                                case 'version':
                                                case 'v':
                                                        getversion();
                                                break;
                                            }
                                            break;
                                 case 'make':
                                            let module;
                                            let controller;
                                            let model;
                                            switch (keyword[1])
                                            {
                                                case 'module':
                                                    module = keyword[2];
                                                    add_module(module);
                                                break;
                                                case 'controller':
                                                    module = keyword[2];
                                                    controller = keyword[3];
                                                    add_controller(module,controller);
                                                break;
                                                case 'model':
                                                    module = keyword[2];
                                                    model = keyword[3];
                                                    add_model(module,model);
                                                break;
                                                default:
                                                        notice("make 中不存在该命令");
                                                break;
                                            }
                                            break;
                                 case 'cache':
                                        switch (keyword[1])
                                            {
                                                case 'config':
                                                    cache_config();
                                                break;
                                            }
                                            break;
                                 default:
                                                notice("不存在该命令");
                                                gethelp();
                                        break;
                             }
                             obj.setAttribute('disabled','disabled');
                             setTimeout(addLine,200);
                         }
                    }
                    
                    function notice(str)
                    {
                        let div = document.createElement('div');
                        div.setAttribute('class','line');
                        let gt = document.createElement('span'); gt.innerHTML = "&gt; ";
                        let input = document.createElement('input'); input.value = str; input.setAttribute('disabled','disabled'); input.setAttribute('onkeydown','excute(this)');
                        div.appendChild(gt);
                        div.appendChild(input);
                        document.body.appendChild(div);
                        let line = document.getElementsByTagName('input');
                        let last_line = line.length-1;
                        line[last_line].focus();
                    }
                    
                    function addLine()
                    {
                        let div = document.createElement('div');
                        div.setAttribute('class','line');
                        let gt = document.createElement('span'); gt.innerHTML = "&gt; ";
                        let input = document.createElement('input'); input.setAttribute('placeholder','请输入'); input.setAttribute('onkeydown','excute(this)');
                        div.appendChild(gt);
                        div.appendChild(input);
                        document.body.appendChild(div);
                        let line = document.getElementsByTagName('input');
                        let last_line = line.length-1;
                        line[last_line].focus();
                    }
                    
                    function cls()
                    {
                        document.body.innerHTML = '';
                    }
                    
                    function add_module(name)
                    {
                        if(name == '')
                        {
                            notice("模块名未输入");
                            return false;
                        }
                        let xmlHttp = new XMLHttpRequest();
                        xmlHttp.open("post","/command/command/addModule",true);
                        xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                        xmlHttp.onreadystatechange = function() {
                            if(this.readyState == 4)
                             {
                                 let json = JSON.parse(xmlHttp.responseText);
                                 notice(json.status);
                             }
                        }
                         xmlHttp.send("name="+name);
                    }
                    
                    function add_controller(module,name)
                    {
                        if(typeof(module) == 'undefined')
                        {
                            notice("模块名未输入");
                            return false;
                        }
                        
                        if(typeof(name) == 'undefined')
                        {
                            notice("控制器名未输入");
                            return false;
                        }
                        
                        let xmlHttp = new XMLHttpRequest();
                        xmlHttp.open("post","/command/command/addController",true);
                        xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                        xmlHttp.onreadystatechange = function() {
                            if(xmlHttp.readyState == 4)
                             {
                                 let json = JSON.parse(xmlHttp.responseText);
                                 notice(json.status);
                             }
                        }
                         xmlHttp.send("module="+module+"&controller="+name);
                    }
                    
                    function add_model(module,name)
                    {
                        if(typeof(module) == 'undefined')
                        {
                            notice("模块名未输入");
                            return false;
                        }
                        
                        if(typeof(name) == 'undefined')
                        {
                            notice("模型名未输入");
                            return false;
                        }
                        
                        let xmlHttp = new XMLHttpRequest();
                        xmlHttp.open("post","/command/command/addModel",true);
                        xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                        xmlHttp.onreadystatechange = function(json) {
                            if(this.readyState == 4)
                             {
                                 json = JSON.parse(xmlHttp.responseText);
                                 notice(json.status);
                             }
                        }
                         xmlHttp.send("module="+module+"&model="+name);
                        
                    }
                    
                    function getAllmodule()
                    {
                        let xmlHttp = new XMLHttpRequest();
                        xmlHttp.open("post","/command/command/getAllModule",true);
                        xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                        xmlHttp.onreadystatechange = function(json) {
                            if(this.readyState == 4)
                             {
                                 json = JSON.parse(xmlHttp.responseText);
                                 notice("模块："+json.dir);
                             }
                        }
                         xmlHttp.send();
                    }
                    
                    function getcontroller(module)
                    {
                        let xmlHttp = new XMLHttpRequest();
                        xmlHttp.open("post","/command/command/getModuleController",true);
                        xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                        xmlHttp.onreadystatechange = function(json) {
                            if(this.readyState == 4)
                             {
                                 json = JSON.parse(xmlHttp.responseText);
                                 notice("模块："+json.dir);
                             }
                        }
                         xmlHttp.send('module='+module);
                    }
                    
                    function getmodel(module)
                    {
                        let xmlHttp = new XMLHttpRequest();
                        xmlHttp.open("post","/command/command/getModuleModel",true);
                        xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                        xmlHttp.onreadystatechange = function(json) {
                            if(this.readyState == 4)
                             {
                                 json = JSON.parse(xmlHttp.responseText);
                                 notice("模块："+json.dir);
                             }
                        }
                         xmlHttp.send('module='+module);
                    }
                    
                    function getversion()
                    {
                        let xmlHttp = new XMLHttpRequest();
                        xmlHttp.open("post","/command/command/getVersion",true);
                        xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                        xmlHttp.onreadystatechange = function(json) {
                            if(this.readyState == 4)
                             {
                                 json = JSON.parse(xmlHttp.responseText);
                                 notice("当前版本是："+json.ver);
                             }
                        }
                         xmlHttp.send();
                    }
                    
                    function cache_config()
                    {
                        let xmlHttp = new XMLHttpRequest();
                        xmlHttp.open("post","/command/command/cacheConfig",true);
                        xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                        xmlHttp.onreadystatechange = function(json) {
                            if(this.readyState == 4)
                             {
                                 json = JSON.parse(xmlHttp.responseText);
                                 notice(json.status);
                             }
                        }
                         xmlHttp.send();
                    }
                    
                    function gethelp()
                    {
                        notice(`————————————————————`);
                        notice(`获取方法：`);
                        notice(`  get module/mod`);
                        notice(`  get controller/c %s<模块名>`);
                        notice(`  get model/m %s<模块名>`);
                        notice(`  get version/v`);
                        notice(`————————————————————`);
                        notice(`创建模块、控制器、模型：`);
                        notice(`  make module %s<模块名>`);
                        notice(`  make controller %s<模块名> %s<控制器名> `);
                        notice(`  make model %s<模块名> %s<模型名>`);
                        notice(`————————————————————`);
                        
                    }
                    
                </script>
            </html>
EOF;

    }

    /**
     *
     *  添加模块
     *
     * */
    public function addModule()
    {
        $module_name = Request::instance()->obtain('post.name');
        $dir = APP_PATH.$module_name;

        if(file_exists($dir))
        {
            return json(['code' => 'error', 'status' => '模块目录已存在！']);
        }

        if(!file_exists($dir))
        {
            mkdir($dir, 777, true);
            mkdir($dir . '/Controller', 777, true);   // 创建控制器文件夹
            mkdir($dir . '/Model', 777, true);   // 创建模型文件夹
            mkdir($dir . '/Middleware', 777, true); // 创建中间件文件夹

            return json(['code' => 'success', 'status' => '创建模块成功']);
        }
        else
            return json(['code' => 'error', 'status' => '文件夹已存在']);
    }

    /**
     *
     *  添加控制器
     *
     * */
    public function addController()
    {
        $module_name = Request::instance()->obtain('post.module');
        $controller_name = Request::instance()->obtain('post.controller');
        $dir = APP_PATH.$module_name;

        if(!file_exists($dir))
        {
            return json(['code' => 'error', 'status' => '模块尚未创建']);
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
        $filename = $dir.'/Controller/'.$controller_name.'.php';
        if(!file_exists($filename))
        {
            $phpfile = fopen($filename,"w");
            fwrite($phpfile,$content);

            return json(['code' => 'success', 'status' => '创建控制器成功']);
        }
        else
            return json(['code' => 'error', 'status' => '该控制器已存在']);
    }

    public function addModel()
    {
        $module_name = Request::instance()->obtain('post.module');
        $model_name = Request::instance()->obtain('post.model');
        $dir = APP_PATH.$module_name;
        $content = <<<EOF
<?php


namespace {$this->namespace}\\{$module_name}\\model;

use apphp\\Core\\Model;

class {$model_name} extends Model
{
    
}
EOF;
        $filename = $dir.'/Model/'.$model_name.'.php';
        if(!file_exists($filename))
        {
            $phpfile = fopen($filename,"w");
            fwrite($phpfile,$content);

            return json(['code' => 'success', 'status' => '创建模型成功']);
        }
        else
            return json(['code' => 'error', 'status' => '该模型已存在']);
    }

    /**
     *
     *  获取所有模块
     *
     * */
    public function getAllModule()
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
        }

        return json([ 'dir' =>  $dirs]);
    }

    /**
     *
     *  获取模块中所有控制器
     *
     * */
    public function getModuleController()
    {
        $module = Request::instance()->obtain('post.module');
        $dir =  APP_PATH . $module .'/controller/';
        $dirs = array();
        foreach (scandir($dir) as $file)
        {
            if($file === '.' || $file == '..')
            {
                continue;
            }
            $dirs[] = substr($file,0,stripos($file,'.php'));
        }

        return json([ 'dir' =>  $dirs]);
    }

    /**
     *
     *  获取模块中所有模型
     *
     * */
    public function getModuleModel()
    {
        $module = Request::instance()->obtain('post.module');
        $dir =  APP_PATH . $module . '/model/';
        $dirs = array();
        foreach (scandir($dir) as $file)
        {
            if($file === '.' || $file == '..')
            {
                continue;
            }
            $dirs[] = substr($file,0,stripos($file,'.php'));
        }

        return json([ 'dir' =>  $dirs]);
    }

    /**
     *
     *  缓存配置文件
     *
     * */
    public function cacheConfig()
    {
        $cache = Cache::instance();

        return $cache->cacheConfig() ? json([ 'status' =>  "缓存成功"]) : json([ 'status' =>  "缓存失败"]);
    }

    /**
     *
     *  获取当前版本
     *
     * */
    public function getVersion()
    {
        return json(['ver' => APPHP_VER]);
    }
}