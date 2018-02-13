<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/26
 * Time: 10:35
 */

namespace apphp\Core;


use apphp\Core\request\Obtain;
use apphp\error\error;

class Request
{

    use Obtain;

    private static $instance;

    /**
     *
     *  构造方法
     *
     * */
    function __construct()
    {

    }

    /**
     * __GET 方法
     *
     * 用于获取参数
     *
     * @param $name string 键值
     *
     * @return string 获取到的数据
     * */
    function __get($name)
    {
        return $this->achieve($name) ?? 'undefined';
    }

    /**
     *
     *  将该类实例化并返回
     *
     * */
    public static function instance() : Request
    {
        if(empty(self::$instance))
        {
            self::$instance = new Request();
        }

        return self::$instance;
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
            case 'put':
                        $_PUT = array();
                        parse_str(file_get_contents('php://input'), $_PUT);
                        $is_has = isset($_PUT[$key]) ? true :false;
                        break;
            case 'delete':
                        $_DELETE = array();
                        parse_str(file_get_contents('php://input'), $_DELETE);
                        $is_has = isset($_DELETE[$key]) ? true :false;
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