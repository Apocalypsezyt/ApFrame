<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/15/2018
 * Time: 20:43
 */

namespace app\Auth\Middleware;


use apphp\Core\Middleware;
use apphp\Core\Request;

class CheckCsrf extends Middleware
{
    public function handle(\Closure $next)
    {
        $request = Request::instance();
        $csrf_token = $request->achieve('csrf_token');

        if($request->method() == 'get'){
            $csrf_token = password_hash($_SERVER['SERVER_NAME'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['REMOTE_PORT'], PASSWORD_DEFAULT);
            session()->set('csrf_token', $csrf_token, 'Auth');
            return true;
        }
        else{
            if($csrf_token == session()->get('csrf_token', 'Auth')){
                return true;
            }
            return false;
        }
    }
}