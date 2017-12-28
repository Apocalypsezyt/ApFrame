<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/26
 * Time: 12:12
 */

namespace app\test\controller;


use apphp\Core\Controller;
use apphp\Core\Request;
use apphp\Core\Route;
use apphp\database\Redis;

use app\test\model\test as t;

class test extends Controller
{
    public function index()
    {
        $welcome = "欢迎来到 By APOCALYPSE 的框架";
        $ver = " beta 0.1 ";
        $this->view('test.index',compact('welcome','ver'));
    }

    public function test()
    {
        $this->view('test');
    }

    public function info()
    {
        phpinfo();
    }

    public function login()
    {
        $this->view('test.login');
    }

    public function delete()
    {

    }
}