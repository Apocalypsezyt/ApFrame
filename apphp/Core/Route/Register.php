<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/11/2018
 * Time: 18:32
 */

namespace apphp\Core\Route;


use apphp\error\error;

trait Register
{

    /**
     *  注册一个路由
     *
     * @param string $key 键值
     * @param \Closure | string 控制器或方法
     * @param string 组名
     * 
     * */
    protected function registerRoute($key, $function, $group = 'get')
    {
        // 检查首尾是否有斜杠
        $key = $this->hasSlash($key);
        // 当注册自定义的路由方法
        if($function instanceof \Closure)
        {
            $this->route_group[$group][$key] = function($param) use ($function){
                $param = $param[0] ?? [];
                call_user_func($function, $param);
            };
        }

        // 当注册控制器里的方法时
        else
        {
            $array = explode('.',$function);
            $module = $array[0];
            $controller = $array[1] ?? 'Index';
            $action = $array[2] ?? 'Index';

            $this->route_group[$group][$key] = function ($param) use ($module, $controller, $action){
                $controller = '\\' . APP_NAMESPACE . '\\' . $module . '\\Controller\\' . $controller;
                // 不存在该方法时
                if(!method_exists($controller, $action)){
                    error::ActiveError('found_class_function_nohas', "${controller}中的${action}方法不存在");
                }
                $reflection_method = new \ReflectionMethod($controller, $action);
                $controller = new $controller;
                $reflection_method->invokeArgs($controller, $param);
            };
        }
    }

    /**
     * @method protected 注册一个 GET 路由
     *
     * @param  string $key 路由键
     *
     * @param \Closure | string $function 一个闭包方法或者控制器的方法字符串
     * */
    protected function get($key, $function)
    {
        $this->registerRoute($key, $function, 'get');
    }

    /**
     * @method protected 注册一个 POST 路由
     *
     * @param  string $key 路由键
     *
     * @param \Closure | string $function 一个闭包方法或者控制器的方法字符串
     * */
    protected function post($key, $function)
    {
        $this->registerRoute($key, $function, 'post');
    }

    /**
     * @method protected 注册一个 PUT 路由
     *
     * @param  string $key 路由键
     *
     * @param \Closure | string $function 一个闭包方法或者控制器的方法字符串
     * */
    protected function put($key, $function)
    {
        $this->registerRoute($key, $function, 'put');
    }

    /**
     * @method protected 注册一个 DELETE 路由
     *
     * @param  string $key 路由键
     *
     * @param \Closure | string $function 一个闭包方法或者控制器的方法字符串
     * */
    protected function delete($key, $function)
    {
        $this->registerRoute($key, $function, 'delete');
    }

    /**
     * @method protected 注册一组 restful 路由 (index,create,show,edit,store,update,delete)
     *
     * @param string $key 路由键
     * @param string $key 控制器名
     *
     * @return bool
     * */
    protected function restful($key,$controller) : bool
    {
        // 声明 restful 路由的方法
        $index = $controller . '.index';
        $create = $controller . '.create';
        $show = $controller . '.show';
        $edit = $controller . '.edit';
        $store = $controller . '.store';
        $update = $controller . '.update';
        $delete = $controller . '.delete';
        // 首先注册 get 方法
        $this->get($key, $index);
        $this->get($key.'/create', $create);
        $this->get($key.'/{id}', $show);
        $this->get($key.'/edit/{id}', $edit);
        // 然后注册 post 方法
        $this->post($key, $store);
        // 然后注册 put 方法
        $this->put($key . '/{id}', $update);
        // 最后注册 delete 方法
        $this->delete($key . '/{id}', $delete);

        return true;
    }
}