<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/10/27
 * Time: 8:35
 * File:路由注册表
 */

use apphp\Core\Route;

Route::get('/cms',function (){
    echo "123";
});

Route::get('index','Apocalypse.happy.index');

Route::post('/info','test.test.info');

Route::get('gg','test.test.test');

Route::get('json','Apocalypse.happy.json');

Route::restful('abc', 'Apocalypse.restful');

Route::delete('delete', 'test.test.delete');