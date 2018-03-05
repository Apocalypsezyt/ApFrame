<?php
    return [

        /*
         * 框架使用的关系数据库服务注册区域
         * 如果需要模型支持你要使用的数据库类请绑定指定的命名空间并实现 \apphp\Core\Database\ 下的 Database接口 中的约束方法
         * 并注册于此以及修改配置文件使用的数据库
         * */
        'database'  => [
            'mysql' => \apphp\Core\database\MySql::class,
            'postgresql' => \apphp\Core\database\PostgreSql::class,
            'mssql' => \apphp\Core\database\MsSql::class,
            'sqlite' => \apphp\Core\database\Sqlite::class,
        ]

    ];