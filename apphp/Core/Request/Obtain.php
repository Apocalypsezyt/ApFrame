<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/13/2018
 * Time: 23:29
 */

namespace apphp\Core\Request;


trait Obtain
{
    /**
     *  获取数据
     *
     *  @param string $info 格式为 obtain('get.xxx')
     *
     *  @return string|array 返回值
     * */
    public function obtain($info)
    {
        $info = explode('.',$info,2);
        if(!$info)
        {
            error::ActiveError('r_obtain');
        }

        $obtain_info = '';
        $request_type = $info[0];
        $request_key = $info[1] ?? false;

        switch ($request_type)
        {
            case 'get':
                $obtain_info = $request_key ? $this->obtainGet($request_key) : $_GET;
                break;
            case 'post':
                $obtain_info = $request_key ? $this->obtainPost($request_key) : $_POST;
                break;
            case 'put':
                $_PUT = array();
                parse_str(file_get_contents('php://input'), $_PUT);
                $obtain_info = $request_key ? $_PUT[$request_key] : $_PUT;
                break;
            case 'delete':
                $_DELETE = array();
                parse_str(file_get_contents('php://input'), $_DELETE);
                $obtain_info = $request_key ? $_DELETE[$request_key] : $_DELETE;
                break;
        }

        return $obtain_info;
    }

    /**
     * 根据当前请求获取数据
     *
     * @param string $request_key 键值
     *
     * @return string 获取到的值并转义
     * */
    public function achieve($request_key) : string
    {

        $obtain_info = '';
        $request_type = strtolower($_SERVER['REQUEST_METHOD']);

        switch ($request_type)
        {
            case 'get':
                $obtain_info = $this->obtainGet($request_key);
                break;
            case 'post':
                $obtain_info = $this->obtainPost($request_key);
                break;
            case 'put':
                $_PUT = array();
                parse_str(file_get_contents('php://input'), $_PUT);
                $obtain_info = $request_key ? $_PUT[$request_key] : $_PUT;
                break;
            case 'delete':
                $_DELETE = array();
                parse_str(file_get_contents('php://input'), $_DELETE);
                $obtain_info = $request_key ? $_DELETE[$request_key] : $_DELETE;
                break;
        }

        // 转义获取到的数据
        $obtain_info = htmlspecialchars($obtain_info);

        return $obtain_info;
    }

    /**
     * 获取get数据
     *
     * @param $key string 键值
     *
     * @return string 获取到的数据
     * */
    protected function obtainGet($key) : string
    {
        return $_GET[$key] ?? false;
    }

    /**
     * 获取post数据
     *
     * @param $key string 键值
     *
     * @return string 获取到的数据
     * */
    protected function obtainPost($key) : string
    {
        return $_POST[$key] ?? false;
    }

    /**
     * 获取put数据
     *
     * @param $key string 键值
     *
     * @return string 获取到的数据
     * */
    protected function obtainPut($key) : string
    {
        $_PUT = array();
        parse_str(file_get_contents('php://input'), $_PUT);
        $obtain_info = $_PUT[$key] ?? null;

        return $obtain_info;
    }

    /**
     * 获取delete数据
     *
     * @param $key string 键值
     *
     * @return string 获取到的数据
     * */
    protected function obtainDelete($key) : string
    {
        $_DELETE = array();
        parse_str(file_get_contents('php://input'), $_DELETE);
        $obtain_info = $_DELETE[$key] ?? null;

        return $obtain_info;
    }
}