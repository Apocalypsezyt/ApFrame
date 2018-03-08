<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/10/26
 * Time: 16:19
 */

namespace apphp\Core;


use apphp\Core\Route\Feature;
use apphp\Core\Route\Found;
use apphp\Core\Route\Register;
use apphp\Core\Error\Error;

class Route
{

    protected static $instance; // 初始化的类
    protected $module;  // 模块
    protected $controller; // 控制器
    protected $action;  // 方法
    protected $param;   // 值
    protected $route_group; // 所有路由请求组

    use Register,Found,Feature{
        Register::__construct as registerInit;
        Register::get as registerGet;
        Register::post as registerPost;
        Register::put as registerPut;
        Register::delete as registerDelete;
        Register::restful as registerRestful;
        Register::group as registerGroup;
    }

    /**
     *
     *  用于初始化路由
     *
     * */
    function __construct()
    {
        $this->registerInit();
    }

    /**
     *
     *  初始化该类
     *
     * */
    public static function instance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * 获取当前路由名称
     * */
    protected function getRouteName()
    {
        $path = $this->removalSlash($_SERVER['REQUEST_URI']);
        // 排除掉?后面的字符串
        if(strpos($path,'?'))
            $path = substr($path,0,strpos($path,'?'));

        return $path;
    }

    /**
     *
     *  开始进行路由分配
     *
     * */
    protected function run()
    {
        if(isset($_SERVER['REQUEST_URI']))
        {
            $path = $this->getRouteName();
            $path_arr = explode('/',trim($path,'/'));

            // 获得当前请求方式
            $now_method = $this->getNowMethod();
            // 查找是否在路由注册表中

            if($group = $this->foundHasRoute($path, $now_method)) {
                // 获取别名、中间件、方法
                $as = $group['route']['as'];
                $middleware = $group['route']['middleware'];
                $function = $group['route']['function'];

                if(!$this->checkGlobalMiddleware()){
                    return false;
                }

                $reflection_function = new \ReflectionFunction($function);

                if($middleware){
                    $middleware_class = new \ReflectionClass($middleware);
                    $middleware = $middleware_class->newInstance();
                    $function = function() use ($reflection_function, $group){
                        $reflection_function->invoke($group['param']);
                    };
                    $middleware->handle($function);
                }
                else{
                    return $reflection_function->invoke($group['param']);
                }
            }
            // 不在路由注册表中走默认的方式
            else {
                error::ActiveError('found_route_nohas');
            }

            return false;

        }

        // 当什么路由都找不到的时候
        error::ActiveError('found_route_nohas');

        return true;
    }

    public static function __callStatic($name, $arguments)
    {
        $route = self::instance();
        call_user_func_array([$route,$name], $arguments);
    }

    /**
     * 注册 get 路由
     * @param string $key 路由名
     * @param \Closure | string | array $function 函数或控制器的方法字符串又或者集成的一个数组
     * */
    public static function get($key, $function)
    {
        $route = self::instance();
        $route->registerGet($key, $function);
    }

    /**
     * 注册 post 路由
     * @param string $key 路由名
     * @param \Closure | string | array $function 函数或控制器的方法字符串又或者集成的一个数组
     * */
    public static function post($key, $function)
    {
        $route = self::instance();
        $route->registerPost($key, $function);
    }

    /**
     * 注册 put 路由
     * @param string $key 路由名
     * @param \Closure | string | array $function 函数或控制器的方法字符串又或者集成的一个数组
     * */
    public static function put($key, $function)
    {
        $route = self::instance();
        $route->registerPut($key, $function);
    }

    /**
     * 注册 delete 路由
     * @param string $key 路由名
     * @param \Closure | string | array $function 函数或控制器的方法字符串又或者集成的一个数组
     * */
    public static function delete($key, $function)
    {
        $route = self::instance();
        $route->registerDelete($key, $function);
    }

    /**
     * 注册 restful 路由
     * @param string $key 路由名
     * @param array | string $controller 控制器名的字符串或者集成的一个数组
     * */
    public static function restful($key, $controller)
    {
        $route = self::instance();
        $route->registerRestful($key, $controller);
    }

    /**
     * 注册路由组
     * @param array $config 配置数组
     * @param \Closure $function 路由组方法
     * */
    public static function group($config, $function)
    {
        $function();
    }

    /**
     * 检测全局中间件
     *
     * @return bool 如果通过全局检测中间件 返回true，若不通过返回 false
    * */
    protected function checkGlobalMiddleware()
    {
        foreach ($this->global_middleware as $item){
            $middleware = new $item();
            // 没有方法的中间件
            if(!$middleware->handle(function (){})){
                return false;
            }
        }

        return true;
    }

    /**
     * 获得当前的请求方式
     * @return string 当前请求方式
     * */
    public function getNowMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $method = strtolower($method);

        return $method;
    }

    /**
     * @method public 获取当前路由模块的名字
     * @return  string|bool  返回当前路由的名字，如果没有返回false
     * */
    public function getModule()
    {
        if(!is_null($this->module))
            return $this->module;
        return false;
    }

    /*
     *
     *  获取当前路由控制器
     *
     * */
    public function getController()
    {
        if(!is_null($this->controller))
            return $this->controller;
        return false;
    }

    /*
     *
     *  获取当前路由方法
     *
     * */
    public function getAction()
    {
        if(!is_null($this->action))
            return $this->action;
        return false;
    }

    /*
     *
     *  获取当前路由参数
     *
     * */
    public function getParam()
    {
        if(!is_null($this->param))
            return $this->param;
        return false;
    }

}