<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/10/27
 * Time: 12:31
 */

namespace app\Apocalypse\controller;


use apphp\Core\Controller;
use apphp\Core\Safe\Csrf;
use apphp\Storage\Session;

class happy extends Controller
{
    public function index()
    {
        echo "欢迎来到世界末日的框架";
        echo Csrf::instance()->bulidCsrf();
        $a = '1';
        return $this->view('index',compact('a'));
    }

    public function json()
    {
        json([
           'a' => '123',
           'b' => '456',
        ]);
    }
}