<?php


namespace app\test\Controller;

use apphp\Core\Controller;
use apphp\Core\Request;

class test extends Controller
{
    public function index(Request $request, $name)
    {
        dd($request->nxn);
        dd('南小鸟');
    }

    public function all()
    {
        echo "南小鸟";
    }
}