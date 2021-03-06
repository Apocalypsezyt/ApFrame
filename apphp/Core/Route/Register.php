<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/11/2018
 * Time: 18:32
 */

namespace apphp\Core\Route;


use apphp\Core\Request;
use apphp\error\error;

trait Register
{

    protected $middleware; // 中间件
    protected $global_middleware; // 全局中间件

    public function __construct()
    {
        $kernel = require APP_PATH . 'kernel.php';
        $this->middleware = $kernel['middleware'];
        $this->global_middleware = $kernel['global_middleware'];
    }

    /**
     *  注册一个路由
     *
     * @param string $key 键值
     * @param \Closure | string | array 控制器或方法
     * @param string 组名
     * 
     * */
    protected function registerRoute($key, $function, $group = 'get')
    {
        $middleware = '';
        $as = '';

        if(is_array($function)){
            $middleware = $function['middleware'] ?? null;
            $as = $function['as'] ?? null;
            $function = $function['function'] ?? null;
        }

        // 检查首尾是否有斜杠
        $key = $this->removalSlash($key);
        // 当注册自定义的路由方法
        if($function instanceof \Closure)
        {
            // 如果有中间件则注册中间件
            $middleware_class = $this->middleware[$middleware] ?? null;
            $this->route_group[$group][$key] = [
                'as' => $as,
                'middleware' => $middleware_class,
                'function' => function($param) use ($function){
                    $param = $param[0] ?? [];
                    call_user_func($function, $param);
                }
            ];
        }

        // 当注册控制器里的方法时
        else
        {
            $array = explode('.',$function);
            $module = $array[0];
            $controller = $array[1] ?? 'Index';
            $action = $array[2] ?? 'Index';

            // 如果有中间件则注册中间件
            $route = $this;
            $middleware_class = $this->middleware[$middleware] ?? null;
            $this->route_group[$group][$key] = [
                'as' => $as,
                'middleware' => $middleware_class,
                'function' => function ($param) use ($route, $module, $controller, $action, $middleware){
                    $controller = '\\' . APP_NAMESPACE . '\\' . $module . '\\Controller\\' . $controller;
                    $reflection_method = new \ReflectionMethod($controller, $action);
                    $reflection_class = new \ReflectionClass($controller);
                    // 如果第一个是 Request对象
                    if($route->isRequest($reflection_method->getParameters())){
                        $class = $reflection_method->getParameters()[0]->getClass();
                        $tmp_param = $param;
                        $param[0] = new $class->name;
                        if(isset($tmp_param[0])){
                            $param[1] = $tmp_param[0];
                        }
                    }
                    // 无法实例化该控制器
                    if(!$reflection_class->isInstantiable()){
                        error::ActiveError('found_class_function_nohas', "该${controller}控制器不存在");
                    }
                    // 不存在该方法时
                    if(!method_exists($controller, $action)){
                        error::ActiveError('found_class_function_nohas', "${controller}中的${action}方法不存在");
                    }
                    // 无法实例化该控制器
                    if(!$reflection_class->isInstantiable()){
                        error::ActiveError('found_class_function_nohas', "该${controller}控制器不存在");
                    }
                    $controller = $reflection_class->newInstance([]);
                    $reflection_method->invokeArgs($controller, $param);
                }
            ];
        }
    }

    /**
     * @method protected 注册一个 GET 路由
     *
     * @param  string $key 路由键
     * @param \Closure | string $function 一个闭包方法或者控制器的方法字符串又或者集成的一个数组
     * */
    protected function get($key, $function)
    {
        $this->registerRoute($key, $function, 'get');
    }

    /**
     * @method protected 注册一个 POST 路由
     *
     * @param  string $key 路由键
     * @param \Closure | string $function 一个闭包方法或者控制器的方法字符串又或者集成的一个数组
     * */
    protected function post($key, $function)
    {
        $this->registerRoute($key, $function, 'post');
    }

    /**
     * @method protected 注册一个 PUT 路由
     *
     * @param  string $key 路由键
     * @param \Closure | string $function 一个闭包方法或者控制器的方法字符串又或者集成的一个数组
     * */
    protected function put($key, $function)
    {
        $this->registerRoute($key, $function, 'put');
    }

    /**
     * @method protected 注册一个 DELETE 路由
     *
     * @param  string $key 路由键
     * @param \Closure | string $function 一个闭包方法或者控制器的方法字符串又或者集成的一个数组
     * */
    protected function delete($key, $function)
    {
        $this->registerRoute($key, $function, 'delete');
    }

    /**
     * @method protected 注册一组 restful 路由 (index,create,show,edit,store,update,delete)
     *
     * @param string $key 路由键
     * @param string | array $controller 控制器名又或者集成的一个数组
     *
     * @return bool
     * */
    protected function restful($key, $controller) : bool
    {
        $middleware = null;
        if(is_array($controller)){
            $middleware = $controller['middleware'] ?? null;
            $controller = $controller['controller'] ?? exit();
        }

        // 首先注册 get 方法
        $index = ['middleware' => $middleware, 'function' => $controller . '.index'];
        $create = ['middleware' => $middleware, 'function' => $controller . '.create'];
        $show = ['middleware' => $middleware, 'function' => $controller . '.show'];
        $edit = ['middleware' => $middleware, 'function' => $controller . '.edit'];
        $this->get($key, $index);
        $this->get($key.'/create', $create);
        $this->get($key.'/{id}', $show);
        $this->get($key.'/edit/{id}', $edit);

        // 然后注册 post 方法
        $store = ['middleware' => $middleware, 'function' => $controller . '.store'];
        $this->post($key, $store);

        // 然后注册 put 方法
        $update = ['middleware' => $middleware, 'function' => $controller . '.update'];
        $this->put($key . '/{id}', $update);

        // 最后注册 delete 方法
        $delete = ['middleware' => $middleware, 'function' => $controller . '.delete'];
        $this->delete($key . '/{id}', $delete);

        return true;
    }

    /**
     * @method protected 注册一组路由组
     *
     * @param array $config 路由配置参数
     * @param \Closure $function 路由的方法
     * */
    protected function group($config, $function)
    {
        $this->registerGroup($config, $function);
    }

    /**
     * @method protected 判断是否存在 Request对象，传参中只能是第一个
     *
     * @return bool 存在 or 不存在
     * */
    protected function isRequest($parameters)
    {
        foreach ($parameters ?? [] as $parameter){
            if($parameters[0]->getClass()){
                return true;
            }
        }

        return false;
    }
}