<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/27/2018
 * Time: 22:47
 */

namespace apphp\Core;


class Password
{
    /**
     * @access public 加密字符串
     * @param string $password 要加密的字符串
     * @return string 加密成功的字符串
     * */
    public static function hash($password)
    {
        $hash_password = password_hash($password, PASSWORD_HASH);

        return $hash_password;
    }

    /**
     * @access public 判断密码是否正确
     * @param string $password 要加密的字符串
     * @param string $hash_password 被加密的字符串
     * @return boolean 返回是否相等
     * */
    public static function verify($password, $hash_password)
    {
        return password_verify($password, $hash_password);
    }
}