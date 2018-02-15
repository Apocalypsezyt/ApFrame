<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/10/27
 * Time: 8:35
 * File:路由注册表
 */

use apphp\Core\Route;

Route::get('/', function(){
   echo "Welcome to ApFrame";
});

Route::get('/test', function (){
    $test = new \app\test\model\test();
    dd($test->where('name', 'kotori')->get());
});

Route::get('/test' , ['middleware' => 'test', 'function' => function(){

}]);

Route::get('/m/test', ['middleware' => 'test', 'function' => 'test.test.index']);