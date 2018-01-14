<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/10/26
 * Time: 16:19
 */

namespace apphp\Core;


use apphp\Core\Safe\Csrf;
use apphp\error\error;

class Route
{

    protected static $instance; // 初始化的类
    protected $module;  // 模块
    protected $controller; // 控制器
    protected $action;  // 方法
    protected $param;   // 值
    protected $route_group; // 所有路由请求组


    /*
     *
     *  用于初始化路由
     *
     *
     * */
    function __construct()
    {

    }

    /*
     *
     *  初始化该类
     *
     * */
    public static function instance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /*
     *
     *  开始进行路由分配
     *
     * */
    protected function run()
    {
        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/' )
        {
            $path = $this->hasSlash($_SERVER['REQUEST_URI']);
//            排除掉?后面的字符串
            if(strpos($path,'?'))
                $path = substr($path,0,strpos($path,'?'));
            $path_arr = explode('/',trim($path,'/'));

//            获得当前请求方式
            $now_method = $this->getNowMethod();
//            验证csrf
            $csrf_token = isset($_REQUEST['CSRF_TOKEN']) ?? '';
            if(!Csrf::instance()->checkCsrf($csrf_token) && !in_array($path,CSRF_ROUTE))
            {
                error::ActiveError('403_CSRF');
            }
//            查找是否在路由注册表中
            if($group = $this->foundHasRoute($this->hasSlash($path),$now_method))
            {
                call_user_func($group['groups'][$group['route']],$group['param']);
            }
//            不在路由注册表中走默认的方式
            else
            {
//                判断是否开启command命令行模式
                if($path_arr[0] == 'command' && !USE_COMMAND)
                {
                    error::ActiveError('has_command');
                }

                if (isset($path_arr[0]))
                {
                    $this->module = $path_arr[0];
                    unset($path_arr[0]);
                }

                if (isset($path_arr[1]))
                {
                    $this->controller = $path_arr[1];
                    unset($path_arr[1]);
                }

                if (isset($path_arr[2]))
                {
                    $this->action = $path_arr[2];
                    unset($path_arr[2]);
                }

                if (count($path_arr) != 0)
                {
                    $this->param = $path_arr;
                }

                /*
                 * $module 获取当前指向模块
                 * $controller 获取当前指向控制器
                 * $action 获取当前指向方法
                 * */
                $module = $this->getModule();
                $controller = $this->getController();
                $action = $this->getAction();
                $param = $this->getParam();

                //        执行指向模块控制器的方法
                $controller = '\\' . APP_NAMESPACE . '\\' . $module . '\\Controller\\' . $controller;
                $controller = new $controller();

//                不存在 get 参数时
                if($param)
                    call_user_func_array(array($controller,$action),$param);
                else
                    $controller->$action();
            }

            return false;

        }

//        当什么路由都找不到的时候
        error::ActiveError('found_route_nohas');

        return true;
    }

    public static function __callStatic($name, $arguments)
    {
        $route = self::instance();
        call_user_func_array([$route,$name],$arguments);
    }

    /*
     *
     *  获得当前的请求方式
     *
     * */
    public function getNowMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $method = strtolower($method);

        return $method;
    }

    /*
     *
     *  注册一个路由
     *
     *
     * */
    protected function registerRoute($key,$function,$group = 'get')
    {
//        检查首尾是否有斜杠
        $key = $this->hasSlash($key);
//        当注册自定义的路由方法
        if($function instanceof \Closure)
        {
            $this->route_group[$group][$key] = $function;
        }
/*//        当注册控制器里的方法时
        else
        {
            $array = explode('.',$function);
            $module = $array[0];
            $controller = $array[1];
            $action = $array[2];
            $controller = '\\' . APP_NAMESPACE . '\\' . $module . '\\Controller\\' . $controller;
            $controller = new $controller();
            $this->route_group[$group][$key] = function () use ($controller,$action){
                $controller->$action();
            };
        }*/

        //        当注册控制器里的方法时
        else
        {
            $array = explode('.',$function);
            $module = $array[0];
            $controller = $array[1];
            $action = $array[2];
            $controller = '\\' . APP_NAMESPACE . '\\' . $module . '\\Controller\\' . $controller;
            $reflection_method = new \ReflectionMethod($controller,$action);
            $controller = new $controller;
            $this->route_group[$group][$key] = function ($param) use ($controller,$reflection_method){
                $reflection_method->invokeArgs($controller,$param);
            };
        }
    }

    /**
     * @mthod protected 注册一个 GET 路由
     * @param  string $key 路由键
     * @param \Closure $function 一个闭包方法
     * */
    protected function get($key, $function)
    {
        $this->registerRoute($key, $function, 'get');
    }

    /**
     * @mthod protected 注册一个 POST 路由
     * @param  string $key 路由键
     * @param \Closure $function 一个闭包方法
     * */
    protected function post($key,$function)
    {
        $this->registerRoute($key, $function, 'post');
    }

    /**
     * @mthod protected 注册一个 PUT 路由
     * @param  string $key 路由键
     * @param \Closure $function 一个闭包方法
     * */
    protected function put($key,$function)
    {
        $this->registerRoute($key, $function, 'put');
    }

    /**
     * @mthod protected 注册一个 DELETE 路由
     * @param  string $key 路由键
     * @param \Closure $function 一个闭包方法
     * */
    protected function delete($key,$function)
    {
        $this->registerRoute($key, $function, 'delete');
    }

    /*
     *
     *
     *  注册一组 restful 路由
     *
     * */
    protected function restful($key,$controller)
    {
//        声明 restful 路由的方法
        $all = $controller . '.showAll';
        $show = $controller . '.show';
        $update = $controller . '.update';
        $delete = $controller . '.delete';
        $create = $controller . '.create';
//        首先注册 get 方法
        $this->get($key, $all);
        $this->get($key.'/{id}', $show);
//        然后注册 post 方法
        $this->post($key, $create);
//        然后注册 put 方法
        $this->put($key . '/{id}', $update);
//        最后注册 delete 方法
        $this->delete($key . '/{id}', $delete);

        return true;
    }

    /**
     * @method protected 判断路由第一个字符是不是斜杠,如果存在就去掉斜杠
     *
     *  @param string route 路由名
     *
     *  @return array|bool 返回以数组形式存在的数据
     * */
    protected function hasSlash($route)
    {
        return $route[0] == '/' ? substr($route,1) : $route;
    }

    /*
     *
     *  从方法组里查找，是否存在该路由
     *
     *  $route路由名 $method_group 哪个方法组 get,post,put,delete
     *
     *  @return 返回这个路由组级路由名和参数
     * */
    protected function foundHasRoute($route,$method_group)
    {
//        路由key
        $route = $route;
//        路由地址
        $route_url = '';
//        所需要的路由组
        $groups = $this->route_group[$method_group];
//        参数组
        $param = array();
//        将路由key划分成数组
        $route_arr = explode('/',$route);
//        获取所需要路由组的所有key
        $groups_key = array_keys($groups);;
//        遍历路由组key

        foreach ($groups_key as $keys)
        {
//            每遍历一次将route_url初始化
            $route_url = '';
//            将路由组key值划分成数组
            $route_keys = $keys;
            $route_key_arr = explode('/',$route_keys);
//            遍历并返回出一个路由地址
            $i = 0; // 数组param参数的指针 每开始一次就初始化一次
            foreach ($route_key_arr as $route_key)
            {
//                当其中一个与路由地址其中一个key对应
                if (in_array($route_key,$route_arr))
                {
                    $route_url .= $route_key;
                }
                else if (preg_match('/{(\w)+}/',$route_key))
                {
                    $route_url .= '/'.$route_key;
                    $param[] = $route_arr[$i];
                }
                else
                {
                    break;
                }


                $i++; // 路由参数值数组指针递增
            }
//          当key值已经获取完毕并且匹配成功 将退出该遍历 先注册的优先级高
            if(count($route_key) == count($route_arr) && $route_url == $keys)
            {
                break;
            }
        }

        return isset($groups[$route_url]) ? ['route' => $route_url ,'groups' => $groups , 'param' => $param] : false;
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