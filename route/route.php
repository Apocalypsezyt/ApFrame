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
    dd($test->getAll());
    dd($test->where('name', 'kotori')->get());
});

Route::restful('user', ['middleware' => 'test', 'controller' => 'test.User']);

Route::get('/m/test', 'test.test.all');

Route::get('/m/test/{name}', ['middleware' => 'test', 'function' => 'test.test.index']);

Route::group([], function (){
   Route::get('/kotori', function (){
       dd('123');
       dd('南小鸟');
   });
});

Route::restful('/resource', 'test.resource');