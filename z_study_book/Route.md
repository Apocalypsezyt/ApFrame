
### 在 ApFrame 中定义路由十分简单

> GET 方法

```
Route::get('/test', function(){
    echo "ApFrame 测试";
});
```

> POST 方法

```
Route::post('/test', function(){
    echo "ApFrame POST 测试";
});
```

> PUT 方法

```
Route::put('/test', function(){
    echo "ApFrame PUT 测试";
});
```

> DELETE 方法

```
Route::delete('/test', function(){
    echo "ApFrame DELETE 测试";
});
```

> 控制器的方法写法

[module]模块名 
[controller] 控制器名
[function] 方法名

```
Route::get('/test', 'module.controller.function');
```

> Restful 风格路由

[module]模块名
[controller] 控制器名

```
Route::restful('/test', 'module.controller');
```

> 路由组
```
Route::group(['middleware' => 'test'], function(){
    Route::get('/get', function(){
    
    });
    
    Route::post('/test', 'module.controller.function');
    
    Route::put('/test', 'module.controller.put');
    
    Route::delete('/test', 'module.controller.delete');
});
```