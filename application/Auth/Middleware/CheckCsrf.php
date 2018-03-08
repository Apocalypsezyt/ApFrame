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
    /**
     * @access protected
     * 无需经过csrf验证的路由
     * */
    protected $no_verify_route = [

    ];

    /**
     * @access public 处理Csrf逻辑
     * @param \Closure $next 闭包函数
     * @return bool 返回是否验证正确
     * */
    public function handle(\Closure $next)
    {
        $request = Request::instance();
        $csrf_token = $this->getSessionCsrfToken();

        if($request->method() == 'get'){
            $csrf_token = $this->getHashCsrfToken();
            $this->setSessionCsrfToken($csrf_token);
            return true;
        }
        else{
            if($this->ifCsrfToken($csrf_token)){
                return true;
            }
            return false;
        }
    }

    /**
     * @access protected 对比提交过来的Token和Session中的token是否相同
     * @param string $csrf_token 提供的csrf_token
     * @return bool 返回csrf_token是否正确
     * */
    protected function ifCsrfToken($csrf_token)
    {
        return $csrf_token == session()->get('csrf_token', 'Auth') ? true : false;
    }

    /**
     * @access protected 设置Session中的token
     * @param string $csrf_token 传入的csrf_token
     * */
    protected function setSessionCsrfToken($csrf_token)
    {
        session()->set('csrf_token', $csrf_token, 'Auth');
    }

    /**
     * @access protected 获取Session中的token
     * @return string csrf_token
     * */
    protected function getSessionCsrfToken()
    {
        $request = Request::instance();

        return $request->achieve('csrf_token');
    }

    /**
     * @access 生成加密后CsrfToken
     * @return string csrf_token
     * */
    protected function getHashCsrfToken()
    {
        return password_hash($_SERVER['SERVER_NAME'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['REMOTE_PORT'], PASSWORD_DEFAULT);
    }
}