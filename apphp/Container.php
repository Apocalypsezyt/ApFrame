<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/16/2018
 * Time: 16:52
 */

namespace apphp;

// 容器类，容器类负责容纳实例或提供实例的回调函数
class Container
{
    /**
     * @access protected
     * 用于存储实例，从而实现单例等高级功能
     * */
    protected $bindings = [];

    /**
     * 绑定接口和生成相应实例的回调函数
     *
     * @param string $abstract 实例名称
     * @param \Closure $concrete 回调函数
     * @param bool $shared 是否共享
     *
     * @return void
     * */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        // 如果不是提供的不是回调函数，则返回默认的回调函数
       if( ! $concrete instanceof \Closure) {
            $this->bindings[$abstract] = $this->getClosure($abstract, $concrete);
       }

       $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    /**
     * 用于生成默认的回调函数
     *
     * @param string $abstract 实例的名称
     * @param string $concrete 默认的实例
     *
     * @return \Closure 默认的回调函数
     * */
    protected function getClosure($abstract, $concrete)
    {
        // 生成实例的回调函数，$c是 IOC 容器对象，在调用回调实例时提供
        // 这里是 build 函数中的 $concrete($this)
        return function ($c) use ($abstract, $concrete)
        {
            // 用于判断实例名称和实例名字相同不相同
            $method = ($abstract == $concrete) ? 'build' : 'make';
            // 调用的是容器 build 或 make 方法生成实例
            return $c-$method($concrete);
        };
    }

    /**
     * 用于生成实例对象，首先解决接口和要实例化类之间的依赖关系
     *
     * @param string $abstract 实例名称
     *
     * @return object 返回生成的实例对象
     * */
    public function make($abstract)
    {
        // 获取回调函数
        $concrete = $this->getConcrete($abstract);

        // 判断是不是回调函数或者实例名称
        if($this->isBuildable($concrete, $abstract)){
            $object = $this->build($concrete);
        }
        else{
            $object = $this->make($concrete);
        }

        return $object;
    }

    /**
     * 判断参数是否是回调函数，若不是回调函数则判断是否与实例名相同
     *
     * @param string|\Closure $concrete 回调函数或者实例名称
     * @param string $abstract 实例名称
     *
     * @return bool 返回判断的结果
     * */
    protected function isBuildable($concrete, $abstract)
    {
        return $concrete === $abstract || $concrete instanceof \Closure;
    }

    /**
     * 获取绑定的回调函数
     *
     * @param string $abstract 实例名称
     *
     * @return string|object 如果不存在回调函数返回实例名称，否则返回回调函数
     * */
    public function getConcrete($abstract)
    {
        // 判断存不存在指定的回调函数
        if(!isset($this->bindings[$abstract])){
            return $abstract;
        }

        // 返回回调函数
        return $this->bindings[$abstract]['concrete'];
    }

    /**
     * 实例化对象
     *
     * @param \Closure|string $concrete 回调函数
     *
     * @return object 返回实例化成功的对象
     * */
    public function build($concrete)
    {
        // 判断是否是回调函数
        if($concrete instanceof \Closure){
            return $concrete($this);
        }

        $reflector = new \ReflectionClass($concrete);
        if(!$reflector->isInstantiable()){
            echo "目标[${concrete}]无法被实例化";
        }

        // 获取初始化方法
        $constructor = $reflector->getConstructor();
        // 如果没有初始化返回直接返回实例化的对象
        if(is_null($constructor)){
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $instances = $this->getDependencies($dependencies);
        return $reflector->newInstanceArgs($instances);
    }

    /**
     * 用于解决反射机制实例化对象时的依赖
     *
     * @param array $parameters 参数
     *
     * @return array 参数
     * */
    protected function getDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter){
            $dependency = $parameter->getClass();
            if(is_null($dependency)){
                $dependencies[] = NULL;
            }
            else{
                $dependencies[] = $this->resolveClass($parameters);
            }
        }

        return (array) $dependencies;
    }

    protected function resolveClass(\ReflectionParameter $parameter)
    {
        return $this->make($parameter->getClass()->name);
    }
}