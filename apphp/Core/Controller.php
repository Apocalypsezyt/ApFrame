<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/26
 * Time: 10:37
 */

namespace apphp\Core;


abstract class Controller
{
    protected static $instance;
    protected $ApTemplet;

    /*
     * 该方法主要初始化一下模板方法
     *
     *
     * */
    function __construct()
    {
        $this->ApTemplet = new ApTemplet();
//        $this->smarty->template_dir = ROOT_PATH.'view/';
//        $this->smarty->compile_dir = COMPILE_DIR;
//        $this->smarty->left_delimiter = LEFT_DELIMITER;
//        $this->smarty->right_delimiter = RIGHT_DELIMITER;
    }

    /*
     *
     *  将控制器实例化并返回
     *
     * */
    public static function instance()
    {
        if(empty(self::$instance))
        {
            self::$instance = new static();

            return self::$instance;
        }
        return self::$instance;
    }

    /**
     *  @method protected 渲染模板
     *
     *  @param string $view_name 模板名称
     *  @param array $data 数据
     *
     * @return string 返回渲染好的 HTML 模板
     *
     * */
    protected function view($view_name, array $data = null)
    {
        return $this->ApTemplet->show($view_name,$data);
    }

    /**
     * @method protected 重定向
     *
     * @param string $url 指定路径
     *
     * */
    protected function redirect($url)
    {
        header("Location:{$url}");
    }
}