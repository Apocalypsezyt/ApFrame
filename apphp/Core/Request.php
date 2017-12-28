<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/26
 * Time: 10:35
 */

namespace apphp\Core;


use apphp\error\error;

class Request
{

    private static $instance;

    /*
     *
     *  将该类实例化并返回
     *
     * */
    public static function instance()
    {
        if(empty(self::$instance))
        {
            self::$instance = new Request();
        }

        return self::$instance;
    }

    /*
     *  获取数据
     *
     *  $info 格式为 obtain('get.xxx')
     *
     *  @return 返回值
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
        $request_info = $info[1];;

        switch ($request_type)
        {
            case 'get':
                        $obtain_info = $_GET[$request_info];
                        break;
            case 'post':
                        $obtain_info = $_POST[$request_info];
                        break;
        }

        return $obtain_info;
    }

    /*
     *  判断一个变量是否设置
     *
     *
     *  $request_type 请求方法 $key 键值
     *
     *  @return true or false;
     * */
    public function has($request_type,$key)
    {

        $is_has = '';

        switch ($request_type)
        {
            case 'get':
                       $is_has = isset($_GET[$key]) ? true :false;
                       break;
            case 'post':
                        $is_has = isset($_POST[$key]) ? true :false;
                        break;
            default:
                        if(STRICT_MODE)
                            error::ActiveError('r_noIn');
        }

        return $is_has;
    }

    /*
     *  获取当前模块名
     *
     *  @return 模块名
     * */
    public function module()
    {

    }

    /*
     *  获取当前控制器名
     *
     *  @return 控制器
     * */
    public function controller()
    {

    }

    /*
     *  获取当前方法
     *
     *  @return 方法
     * */
    public function action()
    {

    }


}