# ApFrame 更新记录

> beta V1.1

该版本完成了以下功能：

1. 中间件

中间件在 `application\kernel.php` 定义，为了方便大家使用，在生成模块时，会在生成 `controller` 和 `model` 目录基础下又生成 `middleware` 目录

路由现在的第二个参数可以传入数组，数组的参数分别为 `middleware` 中间件的注册键名，`function` 原本定义的方法

> beta V 1.01

该版本是 ApFrame 的第二个版本

该版本完善了路由，因为本人技术问题，路由只能传第一个值，第二个值不知道怎么写

添加了 `bootstrap` 目录，该目录用于一些应用开始时直接读取该目录下的缓存，加快了速度。

添加了自己写的一个模板引擎（ApTemplate），跟 laravel blade 的语法非常相似

> beta V 1.0

该版本是 ApFrame 的第一个版本

该版本完成了以下功能：

1. 路由（暂时还不完美）
2. 控制器
3. 模型（不支持ORM）

以上都是非常普通常见的功能

