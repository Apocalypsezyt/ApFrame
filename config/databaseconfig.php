<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/10
 * Time: 0:00
 */

//当前使用的数据库
define("NOW_USE_DB", readSet('NOW_USE_DB', 'mysql'));

//数据库友好提示模式
define("DB_FRIEND",true);

/*
 *
 * sqlite 数据库配置区
 *
 * */
define("SQLITE_FILE", readSet('SQLITE_FILE'));

/*
 *
 * mysql 数据库配置区
 *
 * 以下分别对应 地址 用户名 密码 数据库 端口 字符编码
 *
 * */
define("MYSQL_HOST", readSet('MYSQL_HOST'));
define("MYSQL_USER", readSet('MYSQL_USER'));
define("MYSQL_PASSWORD", readSet('MYSQL_PASSWORD'));
define("MYSQL_DATABASE", readSet('MYSQL_DATABASE'));
define("MYSQL_PORT", readSet('MYSQL_PORT'));
define("MYSQL_CHARSET", readSet('MYSQL_CHARSET'));

/*
 *
 *  mssql 数据库配置区
 *
 *
 * 以下分别对应 地址 用户名 密码 数据库 端口 字符编码
 *
 * */
define("MSSQL_HOST", readSet('MSSQL_HOST'));
define("MSSQL_USER", readSet('MSSQL_USER'));
define("MSSQL_PASSWORD", readSet('MSSQL_PASSWORD'));
define("MSSQL_DATABASE", readSet('MSSQL_DATABASE'));
define("MSSQL_PORT", readSet('MSSQL_PORT'));
define("MSSQL_CHARSET", readSet('MSSQL_CHARSET'));

/*
 *
 *  pgsql 数据库配置区
 *
 *
 * 以下分别对应 地址 用户名 密码 数据库 端口 字符编码
 *
 * */
define("PGSQL_HOST", readSet('PGSQL_HOST'));
define("PGSQL_USER", readSet('PGSQL_USER'));
define("PGSQL_PASSWORD", readSet('PGSQL_PASSWORD'));
define("PGSQL_DATABASE", readSet('PGSQL_DATABASE'));
define("PGSQL_PORT", readSet('PGSQL_PORT'));
define("PGSQL_CHARSET", readSet('PGSQL_CHARSET'));

/*
 *  Redis 配置区
 * */
define("REDIS_DRIVER", readSet('REDIS_DRIVER', 'predis'));
define("REDIS_HOST", readSet('REDIS_HOST'));
define("REDIS_PORT", readSet('REDIS_PORT'));