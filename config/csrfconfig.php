<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/11/3
 * Time: 10:03
 */

// CSRF要验证的HTTP请求
define('CHECK_CSRF_HTTP',array('post','put','delete'));
// 不经过框架CSRF验证的路由
define('CSRF_ROUTE', array('/info'));