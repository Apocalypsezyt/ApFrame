# ApFrame

我学习了其他人开发的开源PHP框架

请开始时使用 ``` composer install ```

> laravel,Thinkphp

这个 PHP 的 MVC 框架模仿了他人的功能，你可以在使用的方法中看到它与别人的许多相似

> 文件的目录结构
```
project  应用部署目录
├─apphp           框架核心目录
│  ├─Core              核心代码
│  ├─Database              操作数据库核心代码
├─application           应用目录（可设置）
│  ├─index              模块目录(可更改)
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
├─bootstrap             引导目录
│  │  ├─config          缓存的配置的文件
├─config                框架一系列的配置文件
│  │  ├─config          缓存的配置的文件
├─public                WEB 部署目录（对外访问目录）
│  ├─static             静态资源存放目录(css,js,image)
│  ├─index.php          应用入口文件
│  └─.htaccess          用于 apache 的重写
├─resource               框架需要用到的资源
│  ├─view               模板文件的存储
├─route                  路由配置目录
│  └─route.php          用于配置你的路由
├─vendor                第三方类库目录（Composer）
├─runtime              框架的运行目录
│  ├─cache             模板的缓存目录
├─composer.json         composer 定义文件
├─.set                  框架配置文件
├─README.md             README 文件
```

> 路由
```
// 普通使用闭包函数
Route::get('/', function(){
    echo "Hello World";
});

// 使用控制器 其中第一个是模块，第二个是控制器名，第三个是方法
Route::get('/', 'Index.Index.Index');

// 甚至还能简单的注册一个 restful 路由，这个方法不需要提供方法也就是第三个参数
Route::get('/', 'Test.test');
```

> 自带的命令系统

通过根目录 `.set` 文件中修改为 `USE_COMMAND=true` 才能使用

通过在浏览器页面输入 `/command/command/index` 进行使用内置的命令行系统


 
