<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/10
 * Time: 0:00
 */

//当前使用的数据库
define("NOW_USE_DB",'mysql');

//数据库友好提示模式
define("DB_FRIEND",true);

/*
 *
 * sqlite 数据库配置区
 *
 * */
define("SQLITE_FILE", "xxx.db");

/*
 *
 * mysql 数据库配置区
 *
 * 以下分别对应 地址 用户名 密码 数据库 端口 字符编码
 *
 * */
define("MYSQL_HOST", "localhost");
define("MYSQL_USER", "root");
define("MYSQL_PASSWORD", "callofduty321");
define("MYSQL_DATABASE", "test");
define("MYSQL_PORT", 3306);
define("MYSQL_CHARSET", 'utf8');

/*
 *
 *  mssql 数据库配置区
 *
 *
 * 以下分别对应 地址 用户名 密码 数据库 端口 字符编码
 *
 * */
define("MSSQL_HOST", "localhost");
define("MSSQL_USER", "root");
define("MSSQL_PASSWORD", "callofduty321");
define("MSSQL_DATABASE", "test");
define("MSSQL_PORT", 1433);
define("MSSQL_CHARSET", 'utf8');