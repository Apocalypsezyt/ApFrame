<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/11/3
 * Time: 9:48
 */

namespace apphp\Core\Safe;


use apphp\Storage\Session;

class Csrf
{
    protected static $instance;

    public static function instance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /*
     *
     *  验证 CSRF TOKEN 值
     *
     *  $csrf_token token值
     *
     *  @return true or false
     * */
    public function checkCsrf($csrf_token)
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        if(in_array($method,CHECK_CSRF_HTTP))
        {
            return $csrf_token == Session::get('csrf_token','csrf') ? true : false;
        }

        return true;
    }

    /*
     *
     *  生成 csrf token 值
     *
     *
     *  @return 生成的token
     * */
    public function buildCsrf()
    {
        $token = '';
        $pass = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
        foreach(range(1,16,1 ) as $number)
        {
            $token.= $pass[rand(0,61)];
            if($number %3 == 0)
            {
                $token.= ',';
            }
        }

        $token_arr = explode(',',$token);
        sort($token_arr);
        $token = sha1(implode('',$token_arr));

        Session::set('csrf_token',$token,'csrf');

        return $token;
    }
}