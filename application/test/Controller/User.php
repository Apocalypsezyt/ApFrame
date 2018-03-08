<?php


namespace app\test\Controller;

use apphp\Core\Controller;
use app\User\model\User as U;
use apphp\Core\Facilitate\ImgCompress;
use apphp\Core\OperatingSystem;
use apphp\Core\Request;
use apphp\Core\Storage\ApSession;
use apphp\database\Redis;

class User extends Controller
{
    public function index(Request $request)
    {
        //echo "主页";
        //echo csrf_token();
        $session = new ApSession();
        $session->set('lp', 'kotori');
        dd($session->get('session')->get('lp'));
    }

    public function show($name)
    {
        return $this->view('websocket', compact('name'));
    }

    public function edit($id)
    {
        echo $id;
    }

    public function store()
    {
        echo csrf_token();
    }

    public function create()
    {
        echo "创造";
    }

    public function update($id)
    {
        echo $id;
    }

    public function delete($id)
    {
        echo $id;
    }
}