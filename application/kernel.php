<?php
    return [
    /*
     * 中间件注册
     * 键名为中间件的名字，值为中间件的属性
     * */
      'middleware' => [
          'test' => \app\test\Middleware\CheckTest::class
      ]
    ];